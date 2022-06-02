<?php

namespace App\Http\Controllers;
use App\User;
use App\Region;
use App\EntitiesForm;
use Illuminate\Http\Request;
use App\Models\StaffAttendance;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    function __construct(){
        // $this->middleware('permission:viewAdmin', ['only' => ['index']]);
        // $this->middleware('permission:manageFieldStaff',['only'=>['create','store','show','edit','destroy','update']]);
        // $this->middleware('role:Admin|Regional Admin|Supervisor',['only'=>'mystaffIndex']);
        // $this->middleware('permission:MyStaff',['only'=>'mystaffIndex']);
        // $this->middleware('role:Admin',['only'=>'staffByRegionalAdmin']);
    }

    public function index(){   
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        if($role == 'Super Admin'){
            $field_staffs = $company->users()->with(['bosses','regions'])->role('Field Staff')           
                                ->withTrashed()->get();

            return view('components.staff.listStaff', [
                'users' => $field_staffs,
                'message' => 'Staffs List'
            ]);
        }elseif($role == 'Admin' || $role == 'Regional Admin'){
            $field_staffs = $user->users()->role('Field Staff')->withTrashed()->get();

            return view('components.staff.myStaff', [
                'users' => $field_staffs,
                'message' => 'Staffs List'
            ]);
        }
        elseif($user->hasRole('Supervisor')){ 
            $field_staffs = $user->assignStaffs()->get();

            return view('components.staff.myStaff', [
                'users' => $field_staffs,
                'message' => 'Staffs List'
            ]);
        }
        else{
            abort(403);
        }        
    }

    public function getFormStaffs(EntitiesForm $form)
    {
        $role= Auth::user()->getRoleNames()->first();
        $staffs = [];
        if($role == 'Super Admin'){
            $other_field_staffs = DB::select(DB::raw('SELECT  ST.*
            FROM users CR INNER JOIN creator_user ON CR.id <> creator_user.user_id 
            inner JOIN user_has_roles ON creator_user.staff_id = user_has_roles.user_id 
            inner JOIN roles ON user_has_roles.role_id = roles.id 
            inner JOIN users ST ON creator_user.staff_id = ST.id  
            where CR.id = :user_id and roles.name = "Field Staff"'),['user_id'=>Auth::user()->id]);
            $field_staffs = Auth::user()->users()->with('assignedEntitiesForms.formCreator')->role('Field Staff')->get();
            $formAssigner = [];
            $entitiesFormAssigner= [];
            foreach($field_staffs as $fsi=>$field_staff){
                // foreach($field_staff->assignedForms as $afi=>$assigedForm){
                //     $formAssigner[$fsi][$afi]=User::find($assigedForm->pivot->assigner_id);
                // }
                foreach($field_staff->assignedEntitiesForms as $aefi=>$assigedEntitiesForm){
                    $entitiesFormAssigner[$fsi][$aefi]=User::find($assigedEntitiesForm->pivot->assigner_id);
                }
            }
            $casted_other_field_staffs= [];
            foreach ($other_field_staffs as $other_field_staff ) {
                
                $casted_other_field_staffs[]=$this->cast('User',$other_field_staff);
            }
            $staffs['fieldStaffs']= $field_staffs;
            $staffs['otherFieldStaffs']=$casted_other_field_staffs;
            $staffs['assignedStaffsId'] = $form->staffs->pluck('id');
            $staffs['formAssigner'] = $formAssigner;
            $staffs['entitiesFormAssigner'] = $entitiesFormAssigner;
            $staffs['form']=$form;
        }
        
        return json_encode($staffs);
    }

    public function create()
    {
        if(Auth::user()->hasRole('Super Admin')){
            $regions = Region::all();
        }
        else {
            $regions = Auth::user()->regions;
        }
        return view('components.staff.addStaff',[
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
                $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
                $path = $image->storeAs($destinationpath, $imagename);
                $user->profile_image = $imagename;
            }
            if($user->save()){
                $role = Role::where('name','Field Staff')->first();
                $user->assignRole($role);
                Auth::user()->users()->attach($user->id);
                $user->regions()->attach($regionId);
                if(Auth::user()->hasRole(['Admin','Regional Admin'])){
                    DB::table('associate_user')->insert([
                    ['user_id'=> Auth::user()->id,
                    'staff_id'=> $user->id,
                    'staff_status' => 4]
                    ]);
                }
                $company = Auth::user()->companies[0];
                $user->companies()->attach($company);
                DB::commit();
            }
        }catch(Exception $exception){
            DB::rollBack();
            if($exception instanceof \Illuminate\Validation\ValidationException){
                return back()
                ->withErrors($exception->validator->errors())
                ->withInput();
            }
            dd($exception);
        }
        if(Auth::user()->hasRole('Super Admin')){
            return redirect('/staffs')->with('message','Field Staff '.$user->name.' created Successfully');
        }
        else{
            return redirect('/mystaffs')->with('message','Field Staff '.$user->name.' created Successfully');
        }
    }

    public function show($staff_id){
        $staff = User::with('creators')->find($staff_id);
        $staff_company= $staff->companies()->first();
        $staff_region_id = $staff->regions()->first()->id;
        $user = Auth::user();
        $user_company = $user->companies()->first();
        $role = $user->getRoleNames()->first();
        if($role == 'Super Admin' || $role == 'Admin' && ($staff_company->id == $user_company->id)){
            
            return view('components.staff.showStaff',[
                'user'=> $staff
            ]);
        }elseif($role == 'Regional Admin' && ($staff_company->id == $user_company->id)){
            $user_regions = $user->regions->pluck('id')->toArray();
            if(in_array($staff_region_id, $user_regions)){
                
                return view('components.staff.showStaff',[
                    'user' => $staff
                ]);
            }else{
                abort(403);
            }
        }
        else{
            abort(403);
        }
    }

    public function edit(User $staff){
        $user = Auth::user();   //logged in user
        $user_company = $user->companies()->first();
        $role = $user->getRoleNames()->first();
        if($staff->hasRole('Field Staff')){
            $creator  = $staff->creators()->first();
            $staff_company= $staff->companies()->first();
            // if( ($role == 'Super Admin' || $role == 'Admin' && $staff_company->id == $user_company->id ) ||
            //     ($role == 'Regional Admin' && $creator->id == $user->id)    
            // ){
                
            //     return view('components.staff.update',[
            //         'user' => $staff
            //     ]);
            // }
            // else{
            //     abort(403);
            // } 

            if($staff_company->id == $user_company->id && ($role == 'Super Admin' || $role == 'Admin' || $role == 'Regional Admin') && ($creator->id == $user->id) ){
                return view('components.staff.update',[
                    'user' => $staff
                ]);
            }
            else if($role == 'Supervisor'){
                return redirect('/mystaffs')->with('danger', 'You do not have permission to perform this action');
            }   
            else{
                return redirect('/mystaffs')->with('danger', $staff->name.' is not created by you. Only staff created by you can edit or update');
            }
        }
        else{
            return redirect($role=='Super Admin'? '/staffs':'/mystaffs')->with('danger', $staff->name.' is not Field Staff');
        }
    }

    public function update(Request $request, User $staff)
    {
        $user = Auth::user();   //logged in user
        $role = $user->getRoleNames()->first();
        $inputData = request()->validate([
            'name'=>'required',
            'phone_number'=>'required|unique:users,phone_number,'.$staff->id,
            'address'=>'required',
            'email'=>'required|email|unique:users,email,'.$staff->id
        ]);
        $profileImage = public_path("storage/images/profiles/{$staff->profile_image}");
        if($request->has('profile_image')){
            if(File::exists($profileImage))
            {
                File::delete($profileImage);
            }
            $image = $request->file('profile_image');
            $destinationpath ='public/images/profiles';
            $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs($destinationpath, $imagename);
            $inputData['profile_image'] = $imagename;
        }
        $staff->update($inputData);
        
        return redirect($role == 'Super Admin' ? '/staffs' : '/mystaffs')->with('message','Data Updated Successfully.');

    }

    public function destroy(User $staff)
    {
        //allow to delete this user by his creator only
        if($staff->creators[0]->id==Auth::user()->id){
            $profileImage = public_path("storage/images/profiles/{$staff->profile_image}");
            if(File::exists($profileImage))
            {
                File::delete($profileImage);
            }
            if($staff->delete()){
                if(Auth::user()->hasRole('Super Admin')){
                    return redirect('/staffs')->with('danger',$staff->name .' is Deleted');
                }
                else if(Auth::user()->hasRole('Admin')){
                    return redirect('/mystaffs')->with('danger',$staff->name .' is Deleted');
                }
            }
        }
        else{
            if(Auth::user()->hasRole('Super Admin')){
                return redirect('/staffs')->with('danger','You can not delete staff created by other ');
            }
            else if(Auth::user()->hasRole('Admin')){
                return redirect('/mystaffs')->with('danger','You can not delete staff created by other ');
            }
        }
    }

    protected function validInputData(){
        return request()->validate([
            'name'=>'required',
            // 'phone_number'=>['required','regex:/(98|97|96)[0-9]{8}/'],
            'phone_number'=>'required|unique:users,phone_number',
            'address'=>'required',
            // 'profile_image'=>'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'region'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required',
            'confirm_password'=>'required|same:password'
        ]);
    }

    //show client to other Staffs (staff those are assign to client by superadmin)
    public function otherStaff(){
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        if($role == 'Admin'){
            $company = $user->companies()->first();
            //$company_staff_id = DB::table('company_user')->whereCompanyId($company->id)->get()->pluck('user_id')->toArray();
            $assigned_staffs_id = DB::table('associate_user')
                                    ->where([
                                            ['user_id',$user->id],
                                            ['staff_status',3]])
                                    ->get()->pluck('staff_id')->toArray();
            //$all_staffs_id = array_merge($company_staff_id, $assigned_staffs_id);
            $all_staffs = User::whereIn('id', $assigned_staffs_id)->role('Field Staff')->get();
        }
        else if($role == 'Regional Admin'){
            $creator =  Auth::user()->creators[0];//gives the regional admin's admin
            $regions = Auth::user()->regions;//gives the regional admin's region
            $all_staffs=[];
            foreach($regions as $region){
                $field_staffs = $region->users()->role('Field Staff')->get();
                foreach($field_staffs as $staff){
                    $data = DB::table('associate_user')->where([
                        ['user_id','=',$creator->id],
                        ['staff_id','=',$staff->id]
                    ])->first();
                    if($data!=NULL){
                        $all_staffs[]= $staff;
                    }
                }
            }
        }else{
            abort(403);
        }
        
        return json_encode(array('data' => $all_staffs));
    }

    public function staffByRegionalAdmin(){
        $user = Auth::user();
        $regional_admins = $user->users()->role('Regional Admin')->get()->pluck('id')->toArray();
        $field_staffs_ids = array_unique(DB::table('associate_user')->whereIn('user_id', $regional_admins)
                            ->whereStaffStatus(4)
                            ->get()->pluck('staff_id')->toArray()); 
        $field_staffs = User::whereIn('id', $field_staffs_ids)->get();

        return json_encode([
            'data' => $field_staffs
        ]);
    }

    public function enableDisableStaff($id){
        $user= Auth::user();
        $role= $user->getRoleNames()->first();
        $error = false;
        $staff = User::whereId($id)->withTrashed()->first();
        DB::beginTransaction();
        try{
            if($role =='Super Admin' || $role == 'Admin' || $role == 'Regional Admin') {
                $staff_creator = DB::table('creator_user')->whereUserId($user->id)->whereStaffId($id)->first();
                if($staff_creator){
                    if($staff->deleted_at){
                        $staff->restore();
                        DB::commit();
                    }
                    else{
                        $staff->delete();
                        $staff->staffSupervisors()->sync([]);
                        $staff->assignedEntitiesForms()->sync([]);
                       
                        $staff_attendance = StaffAttendance::whereUserId($staff->id);
                        $staff_images = $staff_attendance->get()->pluck('staff_image')->toArray();
                        
                        foreach($staff_images as $image){
                            $image_path = 'public/images/staff_attendances/';
                            Storage::delete($image_path . $image);
                        }
                        $staff_attendance->delete();
                        DB::commit();
                    }
                }else{
                    $error = true;
                }
                $field_staffs = User::with(['bosses','regions'])->role('Field Staff')->withTrashed()->get();
            }
            else{
                abort(410);
            }

            return redirect($role == 'Super Admin' ? '/staffs' : '/mystaffs')->with([
                'users' => $field_staffs,
                'message' => !$error ? $staff->deleted_at ? 'Staff is disabled.' : 'Staff is enabled.' : 'Action Not Authorized!'
            ]);
        }catch (Throwable $th) {
            DB::rollback();
            return redirect($role == 'Super Admin' ? '/staffs' : '/mystaffs')->with([
                'users' => $field_staffs,
                'message' => $th->getMessage()
            ]);   
        }       
    }
    public function getStaffDetail(){
        $user= Auth::user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
