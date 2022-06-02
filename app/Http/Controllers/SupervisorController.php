<?php

namespace App\Http\Controllers;

use App\Creator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; 
class SupervisorController extends Controller
{
    function __construct()
    {
        $this->middleware('role:Admin|Regional Admin', ['only' => ['index','create','store','show','edit','update','destroy']]);
        $this->middleware('role:Admin', ['only' => ['regionalAdminSupervisors']]);
        // $this->middleware('role:Regional Admin', ['only' => ['allRegionalSupervisors']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){   
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        if($role == 'Admin'){
            $supervisor_staffs = Auth::user()->users()->with('regions')
            ->role('Supervisor')
            ->withTrashed()
            ->get();
        }
        elseif($role == 'Regional Admin'){
            $admin =  $user->creators[0];//find the admin of regional Admin A
            $regions = $user->regions;
            $supervisor_staffs=[];
            $my_supervisors = $user->users()->role('Supervisor')
                                ->withTrashed()
                                ->get();//get the supervisors created by him
            //find the region of regional admin(login regional admin)
            $supervisors = $admin->users()->role('Supervisor')
                            ->withTrashed()
                            ->get();//find all supervisors created by the admin
            // //find the supervisors that has region as same as regional admin
            foreach($supervisors as $supervisor){
                foreach($regions as $region){
                    $data = DB::table('region_user')->where([
                        ['user_id','=',$supervisor->id],
                        ['region_id','=',$region->id]
                    ])->first();
                    if($data!=NULL){
                        $supervisor_staffs[] = $supervisor;
                    }
                }
            }
            //add your supervisor to admin supervisor list(same region)
            foreach($my_supervisors as $supervisor){
                $supervisor_staffs[]=$supervisor;
            }
        }else{
            abort(410);
        }
        return view('components.supervisor.index',[
            'users' => $supervisor_staffs
        ]);
    }

    public function create()
    {
        $regions = Auth::user()->regions;
        return view('components.supervisor.create',[
            'regions'=>$regions
        ]);
    }

    public function store(User $user, Request $request)
    {
        try{
            DB::beginTransaction();
            $this->validInputData();
            $user = new User(request(['name','phone_number','address','profile_image','email']));
            $user->password = bcrypt(request('password'));
            $regionId = request('region');
            // $user->company_id = Auth::user()->company->id;
            if($request->has('profile_image')){
                $image = $request->file('profile_image');
                $destinationpath ='public/images/profiles';
                $imagename = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                $path = $image->storeAs($destinationpath, $imagename);
                $user->profile_image = $imagename;
            }
            if($user->save()){
                $role = Role::where('name','Supervisor')->first();
                $user->assignRole($role);
                $company = Auth::user()->companies[0];
                $user->companies()->attach($company);
                Auth::user()->users()->attach($user->id);
            }
            $user->regions()->attach($regionId);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            if($exception instanceof \Illuminate\Validation\ValidationException){
                return back()
                ->withErrors($exception->validator->errors())
                ->withInput();
            }
            dd($exception);
        }
        return redirect('/supervisors')->with('message','Supervisor '.$user->name.' is created Successfully');
    }

    public function show($supervisor_id)
    {
        $supervisor = User::with('creators')->find($supervisor_id);
        $supervisor_company= $supervisor->companies()->first();
        $supervisor_region_id = $supervisor->regions()->first()->id;
        $user = Auth::user();
        $user_company = $user->companies()->first();
        $role = $user->getRoleNames()->first();
        if($role == 'Admin' && ($supervisor_company->id == $user_company->id)){
            
            return view('components.supervisor.show',[
                'user'=>$supervisor
            ]);
        }elseif($role == 'Regional Admin' && ($supervisor_company->id == $user_company->id)){
            $user_regions = $user->regions->pluck('id')->toArray();
            if(in_array($supervisor_region_id, $user_regions)){
                
                return view('components.supervisor.show',[
                    'user' => $supervisor
                ]);
            }else{
                abort(403);
            }
        }
        else{
            abort(403);
        }
    }

    public function edit($supervisor_id){
        $supervisor = User::with('creators')->findOrFail($supervisor_id);
        if( $supervisor->hasRole('Supervisor')){
            $supervisor_company= $supervisor->companies()->first();
            $user = Auth::user();   //logged in user
            $user_company = $user->companies()->first();
            $role = $user->getRoleNames()->first();
            $creator  = $supervisor->creators()->first();
            // if(($role == 'Supervisor' && $supervisor->id == $user->id) ||
            //     ($role == 'Admin' && $supervisor_company->id == $user_company->id ) ||
            //     ($role == 'Regional Admin' && $creator->id == $user->id)
            // ){
                
            //     return view('components.supervisor.update',[
            //         'user' => $supervisor
            //     ]);
            // }
            // else{
            //     abort(403);
            // }   
            if($supervisor_company->id == $user_company->id && ($role == 'Admin' || $role == 'Regional Admin') 
            && ($creator->id == $user->id) ){
                    return view('components.supervisor.update',[
                        'user' => $supervisor
                    ]);
            }
            else if($role == 'Supervisor' || $role == 'Super Admin'){
                return redirect()->back()->with('message', 'You do not have permission to perform this action');
            }   
            else{
                return redirect('/supervisors')->with('notice', $supervisor->name.' is not created by you. Only supervisor created by you can edit or update');
            }
        }    
        else{
            return redirect('/supervisors')->with('notice',$supervisor->name.' is not supervisor');
        }
    }

    public function update(Request $request, User $supervisor)
    {
        $inputData = request()->validate([
            'name'=>'required',
            'phone_number'=>'required|unique:users,phone_number,'.$supervisor->id,
            'address'=>'required',
            'email'=>'required|email|unique:users,email,'.$supervisor->id
        ]);
        $profileImage = public_path("storage/images/profiles/{$supervisor->profile_image}");
        if($request->has('profile_image')){
            if(File::exists($profileImage))
            {
                File::delete($profileImage);
            }
            $image = $request->file('profile_image');
            $destinationpath ='public/images/profiles';
            $imagename = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
            $path = $image->storeAs($destinationpath, $imagename);
            $$inputData['profile_image'] = $imagename;
        }
        $supervisor->update($inputData);
        return redirect('/supervisors')->with('message','Data Updated');
    }

    public function destroy(User $supervisor)
    {
        //allow to delete this user only by his creator
        if($supervisor->creators[0]->id==Auth::user()->id){
            $profileImage = public_path("storage/images/profiles/{$supervisor->profile_image}");
            if(File::exists($profileImage))
            {
                File::delete($profileImage);
            }
            if($supervisor->delete()){
                return redirect('/supervisors')->with('notice','Superviosr '.$supervisor->name. ' is  Deleted');
            }
        }
        else{
            abort(403);
        }
    }

    protected function validInputData(){
        return request()->validate([
            'name'=>'required',
            'phone_number'=>'required|unique:users,phone_number',
            'address'=>'required',
            // 'profile_image'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'region'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'confirm_password'=>'required|same:password'
        ]);
    }
    //show  supervisors list created by the regional admin belongs to client X 
    public function regionalAdminSupervisors(){
        $user = Auth::user();
        $regional_admins = $user->users()->role('Regional Admin')->get();
        $supervisors=[];
        $regionalAdmin_supervisors;
        foreach($regional_admins as $regional_admin){
            $supervisors = $regional_admin->users()->role('Supervisor')->get();
            if($supervisors){
                foreach($supervisors as $supervisor){
                    $regionalAdmin_supervisors[] = $supervisor;
                }
            }
        }
        return json_encode([
            'data' => $regionalAdmin_supervisors
        ]);
    }

    //show all supervisors for admin
    public function allRegionalSupervisors(){
        $user = Auth::user();
        $admin =  $user->creators()->first();
        $regions = $user->regions->pluck('id')->toArray();
        $admin_supervisors = $admin->users()->role('Supervisor')->get()->toArray();
        $mine_supervisors = $user->users()->role('Supervisor')->get()->toArray();
        $supervisors = array_merge($mine_supervisors, $admin_supervisors);
        foreach($supervisors as $supervisor){
            $data = DB::table('region_user')->whereUserId($supervisor['id'])
                            ->whereIn('region_id', $regions)
                            ->first();
            if($data){
                $all_supervisor[] = $supervisor;
            }
        }
      
        return json_encode(array('data' => $all_supervisor));
    }

    public function assignStaff(User $supervisor){
        // dd($supervisor);
        $user = Auth::user();
        $Admin = $user->creators()->first();
        $company = $supervisor->companies()->first();
        $region = $supervisor->regions()->first();
        // dd($region);

        $fieldstaff =new Collection();
        $total_fieldstaff = [];
        $staffs = $company->users()->role('Field Staff')->get();
        $admin_staffs = $Admin->fieldstaffs()->where('staff_status',3)->get();
        $fieldstaff = $staffs->merge( $admin_staffs);
        foreach($fieldstaff as $staff){
            if($staff->regions()->first()->id == $region->id){
                $total_fieldstaff[]=$staff;
            }
        }   
        $final_result=[];
        foreach($total_fieldstaff as $employee){
            $data = DB::table('fieldstaffs_supervisors')->where([
                        ['company_id','=',$company->id],
                        ['fieldstaff_id','=',$employee->id]
                        ])->first();
            if($data==NULL){
                $final_result[]=$employee;
            }
        }
        return view('components.assign.assignStaffToSupervisor',[
            'user'=>$supervisor,
            'staffs'=>$final_result
            ]);
    }

    public function storeStaff(Request $request, User $supervisor){
        request()->validate([
            'staffs'=>'required',
        ]);
        $company = $supervisor->companies()->first();
        $input['fieldstaff_id']=request('staffs');
        $input['company_id']=$company->id;
        $supervisor->assignStaffs()->attach( $input['fieldstaff_id'],['company_id'=>$input['company_id']]);
        return redirect('/supervisors')->with('message','Staffs assigned successfull');
    }

    public function removeStaff(User $supervisor){
        $fieldstaff = $supervisor->assignStaffs;
        return view('components.assign.removeStaffFromSupervisor',[
            'user'=>$supervisor,
            'fieldstaffs'=>$fieldstaff
        ]);
    }

    public function updateStorage(Request $request, User $supervisor){
        $inputData = request()->validate([
            'staffs'=>'required',
        ]);
        $string='';
        foreach($inputData['staffs'] as $staff){
            $user = User::where('id',$staff)->first();
            if($user!=NULL){
                $string .= $user['name'].",";
            }
        }
        if($supervisor->assignStaffs()->detach(request('staffs'))){
            return redirect('/supervisors')->with('message','Staff(s) ,'.$string.' removed successfully from '.$supervisor->name);
        }
        else{
            return back()->with('error','Staff(s) ,'.$string.' remove unsuccessfull');
        }
    }

    public function enableDisableSupervisor($id){
        $user= Auth::user();
        $role= $user->getRoleNames()->first();
        $error = false;
        $supervisor = User::whereId($id)->withTrashed()->first();
        DB::beginTransaction();
        try{
            if($role == 'Admin' || $role == 'Regional Admin') {
                $staff_creator = DB::table('creator_user')->whereUserId($user->id)->whereStaffId($id)->first();
                if($staff_creator){
                    if($supervisor->deleted_at){
                        $supervisor->restore();
                        DB::commit();
                    }
                    else{
                        $supervisor->supervisorStaffs()->sync([]);
                        $supervisor->delete();
                        DB::commit();
                    }
                }else{
                    $error = true;
                }
                $supervisors = User::with(['bosses','regions'])->role('Supervisor')->withTrashed()->get();
            }
            else{
                abort(410);
            }
            
            return redirect('supervisors')->with([
                'users' => $supervisors,
                'message' => !$error ? $supervisor->deleted_at ? 'Supervisor is disabled.' : 'Supervisor is enabled.' : 'Action Not Authorized!'
            ]);
         
        }catch (Throwable $th) {
            DB::rollback();
            return redirect('supervisors')->with([
                'users' => $supervisors,
                'message' => $th->getMessage()
            ]);   
        }
    }
}
