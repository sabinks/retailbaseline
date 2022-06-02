<?php

namespace App\Http\Controllers;

use App\Company;
use App\Creator;
use App\User;
use App\Region;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(['companies','users','fieldstaffs'=>function($query){
            $query->whereIn('associate_user.staff_status',[3]);
        }])->role('Admin')->get();
        // $company = Company::
        return view('components.client.listClient',[
            'users' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.client.addClient');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $request)
    {
        try {
            DB::beginTransaction();
            $this->validateInputData();
            // return redirect('/admins')->with('message','New Admin Created');
            $user = new User(request(['name','phone_number','address','profile_image','email']));
            // $user->company_id = $company->id;
            $user->password = bcrypt(request('password'));
            if($request->has('profile_image')){
                $image = $request->file('profile_image');
                $destinationpath ='public/images/profiles';
                $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
                $path = $image->storeAs($destinationpath, $imagename);
                $user->profile_image = $imagename;
            }
            if($user->save()){
                $company = new Company(request(['company_name','company_phone_number','company_address','webaddress']));
                // $company->company_logo = "My Logo";
                $company->theme = "themePurple";
                if($request->has('company_logo')){
                    $image = $request->file('company_logo');
                    $destinationpath ='public/images/logos';
                    $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
                    $path = $image->storeAs($destinationpath, $imagename);
                    $company->company_logo = $imagename;
                }
                $company->save();
                $user->companies()->attach($company);
                $role = Role::where('name','Admin')->first();
                $user->assignRole($role);
            }
            DB::commit();
        }
        catch (Exception $exception) {
            DB::rollBack();
            if($exception instanceof \Illuminate\Validation\ValidationException){
                return back()
                ->withErrors($exception->validator->errors())
                ->withInput();
            }
            dd($exception);
        }
        return redirect('/admins')->with('message','New Admin '.$user->name.' is Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $admin)
    {
        if($admin->hasRole('Admin')){
            return view('components.client.showAdmin',[
                'user'=>$admin
            ]);
        }
        else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = User::with('companies')->where('id',$id)->first();
        $company = $admin->companies()->first();
        if($admin->hasRole('Admin')){
            return view('components.client.update',[
                'user'=>$admin,
                'company'=>$company
            ]);
        }
        else{
            return redirect('/admins')->with('notice',$admin->name.' is not admin or owner of company');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $admin)
    {
        try {
            DB::beginTransaction();
            $company = $admin->companies()->first();
            $adminInputs = request()->validate([
                'name'=>'required',
                'phone_number'=>'required|unique:users,phone_number,'.$admin->id,
                'address'=>'required',
                'profile_image'=>'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'email'=>'required|email|unique:users,email,'.$admin->id
            ]);
            $companyInputs= request()->validate([
                'company_name'=>'required',
                'company_phone_number'=>'required|unique:companies,company_phone_number,'.$company->id,
                'company_address'=>'required',
                'company_logo'=>'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'webaddress'=>'required|unique:companies,webaddress,'.$company->id
            ]);

            if($request->has('profile_image')){
                $profileImage = public_path("storage/images/profiles/{$admin->profile_image}");
                if(File::exists($profileImage))
                {
                    File::delete($profileImage);
                }
                $image = $request->file('profile_image');
                $destinationpath ='public/images/profiles';
                $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
                $path = $image->storeAs($destinationpath, $imagename);
                $adminInputs["profile_image"] = $imagename;
            }
            $admin->update($adminInputs);

            if($request->has('company_logo')){
                $logo = public_path("storage/images/logos/{$company->company_logo}");
                if(File::exists($logo))
                {
                    File::delete($logo);
                }
                $image = $request->file('company_logo');
                $destinationpath ='public/images/logos';
                $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
                $path = $image->storeAs($destinationpath, $imagename);
                $companyInputs["company_logo"] = $imagename;
            }
            $company->update($companyInputs);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            if($exception instanceof \Illuminate\Validation\ValidationException){
                return back()
                ->withErrors($exception->validator->errors())
                ->withInput();
            }
            dd($exception);
        }
        
        return redirect('/admins')->with('message','Data of '.$admin->name.' is Updated');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $admin)
    {
        try {
            DB::transaction();
            DB::begin();
            $admin->companies()->first()->delete();
            DB::commit();
            if($admin->delete()){
                
                $profileImage = public_path("storage/images/profiles/{$admin->profile_image}");
                if(File::exists($profileImage))
                {
                    File::delete($profileImage);
                }
                $company_logo = public_path("storage/images/logos/{$admin->companies()->first()->company_logo}");
                if(File::exists($company_logo))
                {
                    File::delete($company_logo);
                }
                return redirect('/admins')->with('notice',$admin->name.' is deleted.');
            }
        } catch (\Throwable $th) {
            return redirect('/admins')->with('notice', 'Company cannot be deleted.');
        }  
    }

    protected function validateInputData(){
        return request()->validate([
            'company_name'=>'required',
            'company_phone_number'=>'required|unique:companies,company_phone_number',
            'company_address'=>'required',
            'webaddress'=>'required|unique:companies,webaddress',
            'company_logo'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'name'=>'required',
            'phone_number'=>'required|unique:users,phone_number',
            // 'profile_image'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'address'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'confirm_password'=>'required|same:password'
        ]);
    }

    //assign staff to admin process
    //list form 
    public function viewassign(Request $request, User $user){
        // return $total_filed_staffs;
        if($user->hasRole('Admin')){
            $regions = $user->regions;
            return view('components.assign.assignStaffToAdmin',[
                'user'=>$user,
                'regions'=>$regions
                ]);
        }
        else{
            abort(403);
        }
    }
    //store staffs list to admin
    public function storeassign(Request $request){
        $client = User::find(request('client'));
        $client_fieldstaffs = $client->fieldstaffs;
        $regional_field_staff=[];
        $regional_admins = $client->users()->role('Regional Admin')->get();
        foreach($regional_admins as $regional_admin){
            $datas = $regional_admin->users()->role('Field staff')->get();
            if(!$datas->isEmpty()){
                foreach($datas as $data){
                    $regional_field_staff[]=$data;
                }
            }
        }
        $total_field_staffs = array_merge($client_fieldstaffs->toArray(),$regional_field_staff);
        $inputData = request()->validate([
            'staffs'=>'required|array|min:1'
        ]);
        foreach($inputData['staffs'] as $staff){
            $user = User::where('id',$staff)->first();
            if($user!=NULL){
                $new_field_staffs[] = $user;
            }
        }
        $string='';
        $repeated_string='';
        $count=[];
        for($i=0;$i<count($total_field_staffs);$i++){
            for($j=0;$j<count($new_field_staffs);$j++){
                if($total_field_staffs[$i]['id']==$new_field_staffs[$j]['id']){
                    $count[] = $new_field_staffs[$j];
                    $repeated_string .= $new_field_staffs[$j]['name'].',';
                }
                else{
                    $string .= $new_field_staffs[$j]['name'].',';
                }
            }
        }
        if($count!=NULL){
            $string1 = implode(',', array_unique(explode(',', $repeated_string)));
            return back()->with('error',$string1.' already assigned !!');
        }
        else{
            $string1 = implode(',', array_unique(explode(',', $string)));
            $client->fieldstaffs()->attach(request('staffs'),['staff_status' =>'3']);
            return redirect('/admins')->with('message','Staff(s) ,'.$string1.' assigned successfully');
        }
        
        // $result = array_intersect($total_field_staffs,$new_field_staffs);
        // dd($result);
        // $not_associated_staffs =[];
        // $associated_staffs =[];
        // foreach($inputData['staffs'] as $staff){
        //     $data = DB::table('associate_user')->where([
        //         ['user_id',(request('client'))],
        //         ['staff_id',$staff],
        //         ])->first();
        //     if($data==NULL){
        //         $not_associated_staffs[]=$staff;
        //         $string='';
        //         $user = User::select('name')->where('id',$staff)->first();
        //         $string .=  $user->name.',';
        //     }
        //     else{
        //         $associated_staffs[]=$staff;
        //         $string='';
        //         foreach ($associated_staffs as $value){
        //             $user = User::select('name')->where('id',$value)->first();
        //             $string .=  $user->name.',';
        //         }
        //     }
    //     }
    //     if($associated_staffs==NULL){
    //         $client->fieldstaffs()->attach(request('staffs'),['staff_status' =>'3']);
    //         return redirect('/admins')->with('message','Staff(s) ,'.$string.' assigned successfully');
    //     }
    //     else{
    //         return back()->with('error',$string.' already assigned !!');
    //     }
    }

    public function viewrAssociatedStaffs(User $user){
        $client_fieldstaffs = $user->fieldstaffs;
        $total_removal_staff=[];
        foreach($client_fieldstaffs as $fieldstaff){
            if($fieldstaff->pivot->staff_status==3){
                $total_removal_staff[] = $fieldstaff;
            }
        }
        return  view('components.assign.removeStaffs',[
            'user'=>$user,
            'fieldstaffs'=>$total_removal_staff
        ]);
    }

    public function removeStaffs(Request $request,$user){
        $client = User::find($user);
        $inputData = request()->validate([
            'staffs'=>'required|array|min:1'
        ]);
        $string='';
        foreach($inputData['staffs'] as $staff){
            $user = User::where('id',$staff)->first();
            if($user!=NULL){
                $string .= $user['name'].",";
            }
        }
        if($client->fieldstaffs()->detach(request('staffs'))){
            return redirect('/admins')->with('message','Staff(s) ,'.$string.' removed successfully from '.$client->name);
        }
        else{
            return back()->with('error','Staff(s) ,'.$string.' remove unsuccessfull');
        }
    }
}
