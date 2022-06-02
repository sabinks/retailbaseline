<?php
namespace App\Http\Controllers;
use App\Creator;
use App\User;
use App\Region;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
class RegionalAdminController extends Controller
{
    public function index(){
        $regional_admins = Auth::user()->users()->role('Regional Admin')
            ->with('regions')
            ->withTrashed()
            ->get();
        return view('components.regionalAdmin.listRegionalAdmin',[
            'users'=>$regional_admins
        ]);
}

    public function create()
    {
        $new_region=[];
        $next_region=[];
        $my_regions = Auth::user()->regions;
        foreach($my_regions as $region){
            if($region->pivot->region_name!=NULL){
                $next_region[]=$region->pivot->region_name;
            }
            // else if($region->pivot->region_name==NULL){
            //     $new_region[]=$region->name;
            // }
        }
        $regions = Region::all();
        foreach($regions as $region){
            $data = DB::table('region_user')->where([
                ['region_id','=',$region->id],
                ['user_id','=',Auth::user()->id]
            ])->first();
            if(!$data){
                $total[]=$region->name;
            }
        }
        return view('components.regionalAdmin.addRegionalAdmin',[
            'regions'=>array_unique(array_merge($total,$next_region))
        ]);

        // return view('practise',[
        //     'regions'=>Auth::user()->regions
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user,Request $request)
    {
        try{
            $this->validInputData();
            DB::beginTransaction();
            // Fetch the id of login admin , which is later need for fetching only his company's regions having same group name 
            $id = Auth::user()->id;
            $user = new User(request(['name','phone_number','address','profile_image','email'])); 
            $user->password = bcrypt(request('password'));
            if($request->has('profile_image')){
                $image = $request->file('profile_image');
                $destinationpath ='public/images/profiles';
                $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
                $path = $image->storeAs($destinationpath, $imagename);
                $user->profile_image = $imagename;
            }
            if($user->save()){
                $role = Role::where('name','Regional Admin')->first();
                $user->assignRole($role);
                Auth::user()->users()->attach($user->id);
                //assign one region to one admin only. check either the submitted region has regional admin (same company) or not
                //the regional admin must be the of same company X
                $region = Region::where('name',request('region'))->first();
                $company = Auth::user()->companies[0];
                $user->companies()->attach($company);
                if($region){
                    Auth::user()->regions()->attach($region);
                    $user->regions()->attach($region);
                }
                if(!$region){
                    $regions = DB::table('region_user')->where([
                        ['region_name',$request['region']],
                        ['user_id',$id]
                    ])->get();
                    // return $regions;
                    if($regions!=NULL){
                        foreach($regions as $region){
                            $Tregion = Region::where('id',$region->region_id)->first();
                            // $user->regions()->attach($Tregion);
                            $my_regional_admins = Auth::user()->users()->role('Regional Admin')->get();
                            foreach($my_regional_admins as $regional_admin){
                                $data[] = DB::table('region_user')->where([
                                    ['region_id',$Tregion->id],
                                    ['user_id','=',$regional_admin->id]
                                ])->get();
                            }
                            $Total_region[]= $Tregion;
                        }
                        $collection = collect($data);
                        $data = $collection->filter()->flatten()->all();
                        if($data!=NULL){
                            return back()
                            ->with('notice','The Region '.$request['region'].' has regional admin already. Please Select an empty region')
                            ->withInput($request->input());
                        }
                        else{
                            $Tcollection = collect((object)$Total_region);
                            foreach($Tcollection as $region){
                                $user->regions()->attach(['region_id'=>$region->id],['region_name'=>$request['region']]);
                            }
                        }
                    }
                }
                DB::commit(); 
            }
        }catch(Exceptoion $exception){
            DB::rollBack();
            if($exception instanceof \Illuminate\Validation\ValidationException){
                return back()
                ->withErrors($exception->validator->errors())
                ->withInput();
            }
            dd($exception);
        }
        return redirect('/regionalAdmins')->with('message','Regional Admin '.$user->name.' is  Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $regionalAdmin)
    {
        return view('components.regionalAdmin.show',[
            'user'=>$regionalAdmin
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $regionalAdmin)
    {
        if($regionalAdmin->hasRole('Regional Admin')){
            $user = Auth::user();
            $creator = $regionalAdmin->creators()->first();
            $role = $user->getRoleNames()->first();
            if($role =='Admin' && $user->id == $creator->id){
                return view('components.regionalAdmin.update',[
                    'user'=>$regionalAdmin
                ]);
            }
            else{
                return redirect('/regionalAdmins')->with('notice', $regionalAdmin->name.' is not created by you. Only regional admin created by you can edit or update');
            }
        }
        else{
            return redirect('/regionalAdmins')->with('notice', $regionalAdmin->name.' is not Regional Admin');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $regionalAdmin)
    {
        $regionalAdminInputs = request()->validate([
            'name'=>'required',
            'phone_number'=>'required|unique:users,phone_number,'.$regionalAdmin->id,
            'address'=>'required',
            'email'=>'required|email|unique:users,email,'.$regionalAdmin->id
        ]);

        $profileImage = public_path("storage/images/profiles/{$regionalAdmin->profile_image}");
        if($request->has('profile_image')){
            if(File::exists($profileImage))
            {
                File::delete($profileImage);
            }
            $image = $request->file('profile_image');
            $destinationpath ='public/images/profiles';
            $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs($destinationpath, $imagename);
            $regionalAdmin['profile_image'] = $imagename;
        }
        $regionalAdmin->update($regionalAdminInputs);
        return redirect('/regionalAdmins')->with('message','Data of '.$regionalAdmin->name.' is Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $regionalAdmin)
    {
        $profileImage = public_path("storage/images/profiles/{$regionalAdmin->profile_image}");
        if(File::exists($profileImage))
        {
            File::delete($profileImage);
        }
        if($regionalAdmin->delete()){
            return redirect('/regionalAdmins')->with('notice','Regional Admin '.$regionalAdmin->name.' is Deleted');
        }
    }

    protected function validInputData(){
        return request()->validate([
            'name'=>'required',
            'phone_number'=>'required|unique:users,phone_number',
            'address'=>'required',
            'region'=>'required',
            // 'profile_image'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'confirm_password'=>'required|same:password'
        ]);
    }
    
    // public function enableDisableRegionalAdmin($id){
    //     $user= Auth::user();
    //     $role= $user->getRoleNames()->first();
    //     $error = false;
    //     $staff = User::whereId($id)->withTrashed()->first();
    //     if($role == 'Admin') {
    //         $staff_creator = DB::table('creator_user')->whereUserId($user->id)->whereStaffId($id)->first();
    //         if($staff_creator){
    //             if($staff->deleted_at){
    //                 $staff->restore();
    //             }
    //             else{
    //                 $staff->delete();
    //             }
    //         }else{
    //             $error = true;
    //         }
    //         $regional_admins = User::with(['bosses','regions'])->role('Regional Admin')->withTrashed()->get();
    //     }
    //     else{
    //         abort(410);
    //     }

    //     return redirect('regionalAdmins')->with([
    //         'users' => $regional_admins,
    //         'message' => !$error ? $user->deleted_at ? 'Regional Admin is disabled.' : 'Regional Admin is enabled.' : 'Action Not Authorized!'
    //     ]);       
    // }
}