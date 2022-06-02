<?php

namespace App\Http\Controllers\SuperAdmin;

use App\User;
use DataTables;
use App\Company;
use App\EntitiesForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Company\CompanyEntity;
use App\Http\Resources\EntityForm\EntityForm;
use App\Http\Resources\EntityForm\FormResource;
use App\Http\Resources\EntityForm\EntityFormResource;

class EntityController extends Controller
{
    public function index(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $all_entity_form = $company->entitiesForms()->get();
        // $all_entity_form = EntitiesForm::whereUserId($user->id)
        //                     ->doesntHave('clients')
        //                     ->with('clients')
        //                     ->get();
        $form_list = EntityForm::collection($all_entity_form);
        
        return response()->json([
            'form_list' => $form_list
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(),[
            'form_title' =>"required",
            'data' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $formInputs = json_decode($request->data, true);
        foreach ($formInputs as $index => $formInput ) {
            if($formInput['element'] !== 'Header'){
                if( $formInput['field_name'] == 'text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28'){
                    $formInputs[$index]['required']=true;
                }
            }
        }
        $data['inputs'] = json_encode($formInputs);
        $data['form_title']= $request->form_title;
        $data['user_id']= $user->id; //superadmin user id
        $form = EntitiesForm::create($data);
        $company = $user->companies()->first();
        $form->clients()->sync($company->id);

        return response()->json([
            'message' => 'Form Created!'
        ], 200);       
    }

    public function show($id)
    {
        //
    }

    public function edit(EntitiesForm $entity_form){
        return response()->json([
            'report' => $entity_form,
        ], 200);
    }

    public function update(Request $request, EntitiesForm $entity_form){
        $user = Auth::user();
        $company  = $user->companies()->first();
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
        $validator = Validator::make($request->all(), [
            'form_title' => 'required|max:100',
            'inputs' => 'required',
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        $entity_form->form_title = $request->form_title;
        $entity_form->inputs = $request->inputs;
        $entity_form->save();

        return response()->json([
            'message' => 'Form Updated!'
        ], 200);
    }
    public function destroy($entity_form){
        $user = Auth::user();
        $entity_form = EntitiesForm::whereId($entity_form)->whereUserId($user->id)->first();
        $form_assigned_client_count = $entity_form->companies->count();
        if($form_assigned_client_count){

            return response()->json([
                'message' => 'Cannot delete, form assigned to client(s)!',
            ], 401);
        }
        $entity =  $entity_form->staffs()->get();
        if($entity->count()){

            return response()->json(['message' => 'Cannot delete, form assigned to staff(s)!'], 403);
        }
        $entity_form->delete();
   
        return response()->json([
            'message' => 'Form Deleted!'
        ], 200);
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

    public function getFormAssignedStaff(){
        $user = Auth::user();
        $company = $user->companies()->first();
        $all_user = DB::table('company_user')->whereCompanyId($company->id)->get()->pluck('user_id')->toArray();
        $all_staff_list = User::whereIn('id', $all_user)
                        ->role('Field Staff')
                        ->get(['id', 'name as label', 'name as value']);
        $user_id = $user->id;
        // $forms = EntitiesForm::whereUserId($user->id)->select(['id', 'form_title'])
        //                     // ->doesntHave('clients')
        //                     ->with(['staffs'])
        //                     ->get();
        $forms = $company->entitiesForms()->get();
        $forms = EntityFormResource::collection($forms);
        return response()->json([
            'forms' => $forms,
            'staff_list' => $all_staff_list
        ], 200);
    }

    public function entityAssignStaff(EntitiesForm $entity_form, Request $request){
        $user = Auth::user();
        $inputStaffIds = $request->staff_ids ? json_decode($request->staff_ids,true) : [];
        $staffWithAssigner=[];
        
        foreach ($inputStaffIds as $singleStaff ) {
            $staffWithAssigner[$singleStaff]['entity_visit_count'] = 1;
            $staffWithAssigner[$singleStaff]['assigner_id'] = $user->id;
        }
        $entity_form->staffs()->sync($staffWithAssigner);
        return $inputStaffIds;
        return response()->json([
            'message' => 'Field Staff Updated!'
        ], 200);
    }

    public function clientEntityViewAccess(){
        $user = Auth::user();
        $company = $user->companies()->first();
        $all_entity_form = $company->entitiesForms()->get();
        $company_list = Company::where('company_name', '!=', 'Lemon')->with('entityForm')->get();
        $clients = CompanyEntity::collection($company_list);
        
        return response()->json([
            'all_entity_form' =>  FormResource::collection($all_entity_form),
            'assigned_clients' => $clients
        ], 200);
    }

    public function clientEntityAssign($client_id, Request $request){
        $company = Company::findOrFail($client_id);
        $result = $company->entityForm()->sync(json_decode($request->entity_ids));
        return $result;
    }
}
