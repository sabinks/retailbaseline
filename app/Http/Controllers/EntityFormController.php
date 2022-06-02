<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Company;
use App\EntitiesForm;
use App\EntitiesFormData;
use Illuminate\Http\Request;
use App\Http\Requests\API\V1\EntityFormRequest;

class EntityFormController extends Controller
{
    public function __construct()
    {
        return $this->middleware('role:Admin')->only('submitSuperAdmin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = Auth::user();
        $company_id  = $user->companies()->first()->id;
        $role = $user->getRoleNames()->first();
        if ($role == 'Super Admin') {
            $submittedEntitiesForms = $user->submittedEntitiesForms()
                ->with('formCreator.companies','staffs','entitiesFormData')->get();
            foreach ($submittedEntitiesForms as $form) {
                $form->inputs = json_decode($form->inputs );
            }
            $formArr['submittedEntitiesForms'] = $submittedEntitiesForms;
            $forms = EntitiesForm::whereUserId($user->id)
                ->has('clients')
                ->with('clients','formCreator','staffs')
                ->whereHas('clients', function($q) use ($company_id){
                    $q->where('client_id', '<>', $company_id);
                })->get();
            $formArr['formFormDataExist'] = null;
            foreach ($forms as $form) {
                $formArr['formFormDataExist'][$form->id] = $form->entitiesFormData()->exists();
            }
            $formArr['forms'] = $forms;
        }
        else if($role == 'Admin' || $role == 'Regional Admin'){
            $forms = EntitiesForm::whereUserId($user->id)
                ->with('formCreator','staffs')->get();
                
            $formArr['formFormDataExist'] = null;
            foreach ($forms as $form) {
                $formArr['formFormDataExist'][$form->id] = $form->entitiesFormData()->exists();
            }
            $formArr['forms'] = $forms;
        }
     
        $formArr['role'] = $role;

        return json_encode($formArr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentUser = Auth::user();
        $role= $currentUser->getRoleNames()->first();
        $formCreateArr['role']=$role;
        if($role == 'Super Admin'){

            $formCreateArr['companies']=Company::select('id', 'company_name')->where('id', '<>', User::role('Super Admin')->first()->companies[0]->id)->get();
        }

        return json_encode($formCreateArr);
    }

    public function getEntitiesFormStaffs(EntitiesForm $entities_form)
    {
        $role= Auth::user()->getRoleNames()->first();
        $staffs = [];
        if($role == 'Super Admin'){
            $other_field_staffs = DB::select(DB::raw('SELECT  ST.*
            FROM users CR INNER JOIN creator_user ON CR.id <> creator_user.user_id 
            inner JOIN user_has_roles ON creator_user.staff_id = user_has_roles.user_id 
            inner JOIN roles ON user_has_roles.role_id = roles.id 
            inner JOIN users ST ON creator_user.staff_id = ST.id  
            where CR.id = :user_id and roles.name = "Field Staff"'),['user_id'=>Auth::id()]);
            $field_staffs = Auth::user()->users()->with('assignedEntitiesForms.formCreator.companies')->role('Field Staff')->get();
            $formAssigner = [];
            $normalFormAssigner = [];
            $formClientName = [];
            $formEntityVisitCount= [];
            foreach($field_staffs as $fsi=>$field_staff){
                foreach($field_staff->assignedEntitiesForms as $afi=>$assigedForm){
                    // may need later when client also start assigining to its staff
                    $formAssigner[$fsi][$afi]=User::find($assigedForm->pivot->assigner_id);
                    $formClientName[$fsi][$afi]=$assigedForm->clients()->select('company_name')->first();
                    $formEntityVisitCount[$fsi][$afi]=$assigedForm->pivot->entity_visit_count;
                }
                // foreach($field_staff->assignedForms as $afi=>$assigedForm){
                //     // have to work later
                //     $normalFormAssigner[$fsi][$afi]=User::find($assigedForm->pivot->assigner_id);
                // }
            }
            $casted_other_field_staffs= [];
            foreach ($other_field_staffs as $other_field_staff ) {
                
                $casted_other_field_staffs[]=$this->cast('User',$other_field_staff);
            }

            $formAssignedStaffs = $entities_form->staffs;
            foreach ($formAssignedStaffs as $formAssignedStaff ) {
                $staffs['assignedStaffsEntityVisitCount'][$formAssignedStaff->id]=$formAssignedStaff->pivot->entity_visit_count;
            }

            // for shwing info of assigned entity tracking form
            $staffs['formClientName']=$formClientName; 
            $staffs['formEntityVisitCount']=$formEntityVisitCount; 
            $staffs['formAssigner'] = $formAssigner;
            
            // check assigned staffs
            $staffs['assignedStaffsId'] = $formAssignedStaffs->pluck('id');
            // show all field staffs
            $staffs['fieldStaffs']= $field_staffs;
            $staffs['otherFieldStaffs']=$casted_other_field_staffs;
            
            // for shwing info of assigned regular reporting form
            // $staffs['normalFormAssigner'] = $normalFormAssigner;

            // current form 
            $staffs['form']=$entities_form;
        }
        
        return view('components.entities_form.assign_form',['data'=>  $staffs]);
    }

    public function assignForm(Request $request, EntitiesForm $entitiesForm)
    {
        $role= Auth::user()->getRoleNames()->first();
        $user = Auth::user();
        if($role == 'Field Staff'){
            $staffs=[];
        }else{
            $input=$request->all();
            $rules= [
                'staff_ids.*' => 'exists:users,id',
                // 'staff_id.*.company_id'=>'required_with:staff_ids|exists:companies,id',
                // 'staff_id.*.entity_visit_count'=>'required_with:staff_ids|numeric'
            ];
            $messages = [
                // 'staff_ids.*.exists'=>'One of the staffs doesn\'t exist'
            ];
            $validator = \Validator::make($input, $rules, $messages);
            $validator->validate();
            $inputStaffIds = $request->staff_ids?$request->staff_ids:[];
            $staffWithAssigner=[];
            foreach ($inputStaffIds as $singleStaff ) {
                $staffWithAssigner[$singleStaff]['entity_visit_count'] = 1;
                // $staffWithAssigner[$singleStaff]['entity_visit_count'] = $request->staff_id[$singleStaff]['entity_visit_count'];
                $staffWithAssigner[$singleStaff]['assigner_id'] =$user->id;
            }
            $entitiesForm->staffs()->sync($staffWithAssigner);
        }
        return redirect('entities-form');
    }

    public function submitSuperAdmin($entitiesFormId, $submit)
    {
        if ($submit){
            $entitiesForm=EntitiesForm::with('submitSuperAdmins')->findOrFail($entitiesFormId);
            $superAdmin = User::role('Super Admin')->first();
            
            $entitiesForm->submitSuperAdmins()->sync($superAdmin->id);
        }else{
            $entitiesForm=EntitiesForm::with('submitSuperAdmins')->findOrFail($entitiesFormId);
            $entitiesForm->submitSuperAdmins()->sync([]);
        }
            
        return json_encode($entitiesForm);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntityFormRequest $request)
    {
        $currentUser =Auth::user();
        if($currentUser->hasRole('Field Staff')){
            $forms=[];
            return json_encode($forms);
        }else{
            $formInputs=json_decode($request->inputs, true);
            foreach ($formInputs as $index => $formInput ) {
                if($formInput['element'] !== 'Header'){
                
                    if( $formInput['field_name'] =='text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28'){
                        $formInputs[$index]['required']=true;
                    }
                }
            }
            $data['inputs']=json_encode($formInputs);
            
            $data['form_title']= $request->form_title;
            $data['user_id']= $currentUser->id;
            $form = EntitiesForm::create($data);
            if($currentUser->hasRole('Super Admin')){
                $form->clients()->sync($request->client_id);
            }else if($currentUser->hasRole('Admin')){
                $form->clients()->sync($currentUser->companies[0]->id);
            }
            return $form->toJson();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EntitiesForm  $entitiesForm
     * @return \Illuminate\Http\Response
     */
    public function show($entitiesFormId)
    {
        $currentUser = Auth::user();
        $role= $currentUser->getRoleNames()->first();
        if ($role == "Super Admin"||$role == "Admin") {
            // $entitiesForm= $currentUser->submittedEntitiesForms()->findOrFail($entitiesFormId);
            // $entitiesForm->inputs = json_decode($entitiesForm->inputs );
            $entitiesForm= EntitiesForm::with('clients')->where('user_id',Auth::user()->id)->findOrFail($entitiesFormId);
            $entitiesForm->inputs = json_decode($entitiesForm->inputs );
            if ($role == "Super Admin") {
                # code...
                $formEditArr['companies']=Company::select('id', 'company_name')->where('id', '<>', User::role('Super Admin')->first()->companies[0]->id)->get();
            }
            $formEditArr['entitiesForm']=$entitiesForm;
            $formEditArr['role'] = $role;

            return json_encode($formEditArr);
        }elseif ($role == "Field Staff") {
            $form = Auth::user()->assignedEntitiesForms()->findOrFail($entitiesFormId);
            $form->inputs = json_decode($form->inputs );

            return $form->toJson();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EntitiesForm  $entitiesForm
     * @return \Illuminate\Http\Response
     */
    public function edit($entitiesFormId)
    {
        $entitiesForm= EntitiesForm::where('user_id',Auth::user()->id)->findOrFail($entitiesFormId);
        $entitiesForm->inputs = json_decode($entitiesForm->inputs);
        return json_encode($entitiesForm->inputs);
    }

    public function update(EntityFormRequest $request, $entitiesFormId){
        $user = Auth::user();
        $entity_form = EntitiesForm::whereId($entitiesFormId)->first();
        if(!$entity_form){
            
            return response()->json([
                'message' => 'No form found!',
            ], 401);
        }else if($entity_form->user_id != $user->id){    

            return response()->json([
                'message' => 'Cannot edit, unauthorized user!',
            ], 401);
        }
        $orginal_form = json_decode($entity_form->inputs,true);
        $new_form = json_decode($request->inputs, true);
        $form_changed = $this->formChanged($orginal_form, $new_form); 
        if($form_changed){

            return response()->json([
                'message' => 'Form inputs cannot be deleted, inputs can be arranged or edited!',
            ], 401);
        }
        
        foreach($orginal_form as $index => $formInput ) {
            if($formInput['element'] !== 'Header'){
                if( $formInput['field_name'] =='text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28'){
                    $orginal_form[$index]['required'] = true;
                }
            }
        }
        $entity_form->inputs= $request->inputs;
        $entity_form->form_title= $request->form_title;
        $entity_form->save();

        if($user->hasRole('Super Admin')){
            $entity_form->clients()->sync($request->client_id);
        }

        return response()->json([
            'message' => 'Form updated!'
        ], 201);
    }

    public function destroy( $entitiesFormId){
        $user = Auth::user();
        $count = EntitiesFormData::whereEntitiesFormId($entitiesFormId)->count();
        if($count){

            return response()->json(['message' => 'Entity form used, form cannot be deleted!'], 403);
        }
        $entity_form = EntitiesForm::whereUserId($user->id)->findOrFail($entitiesFormId);
        $entity_form->delete();

        return response()->json(['message' => 'Form Deleted!'], 200);
    }

    public function formChanged($orginal_form, $new_form){
        $original_form_ids = [];
        $new_form_ids = [];
        foreach ($orginal_form as $input){
            array_push($original_form_ids, $input['id']);
        }
        foreach ($new_form  as $input){
            array_push($new_form_ids, $input['id']);
        }

        return empty(array_diff($original_form_ids, $new_form_ids)) ? 0 : 1;
    }

    public function getEntityFormAssignedForms(){
        $user = Auth::user();
        $company  = $user->companies()->first();
        $entity_form_list = $company->entityForm;

        return response()->json([
            'entity_form_list' => $entity_form_list,
        ]);
    }

    public function getEntityFormAssignedList($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $entity_data = EntitiesFormData::whereEntitiesFormId($id)->approved()->select(['id', 'entities_form_id', 'name','created_at'])->with(['entitiesForm:id,form_title'])->orderBy('created_at','desc');
        if($role == 'Admin'){
            $entity_data = $entity_data->get();
        }else if($role == 'Regional Admin'){
            $region_ids = $user->regionsList()->get()->pluck('id')->toArray();
            $entity_data = $entity_data->whereIn('region_id', $region_ids)->get();
        }else if($role == 'Supervisor'){
            $region = $user->regionsList()->first()->pluck('id')->toArray();
            $entity_data = $entity_data->whereIn('region_id', $region)->get();
        }else{
            abort(410);
        }
      
        return response()->json([
            'entity_list' => $entity_data,
        ]);
    }
}
