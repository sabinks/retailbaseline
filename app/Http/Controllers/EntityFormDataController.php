<?php

namespace App\Http\Controllers;

use Auth;
use File;
use App\Region;
use Carbon\Carbon;
use App\Entitygroup;
use App\EntitiesForm;
use App\EntitiesFormData;
use App\Models\ReportData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientEntityDataExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\API\V1\EntityFormDataRequest;
use App\Http\Resources\EntityData\EntityLocationResource;
use App\Http\Resources\EntityData\AssignedEntityLocationResource;

class EntityFormDataController extends Controller
{
    public function __construct()
    {
        // return $this->middleware('permission:manageFormData');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($entitiesFormId)
    {
        $userId = Auth::user()->id;
        $role= Auth::user()->getRoleNames()->first();
        $entities_form = EntitiesForm::with('clients')->findOrFail($entitiesFormId);
        if($role == 'Field Staff'){
            $formData = $entities_form->entitiesFormData()->with('formFiller', 'region')->where('user_id',$userId)->get();
        }else{
            $formData = $entities_form->entitiesFormData()->with('formFiller', 'region')->get();
        }
        foreach ($formData as $formDatum) {
            $formDatum->input_datas = json_decode($formDatum->input_datas);  
        }
        $entities_form->inputs=json_decode($entities_form->inputs);
        $formDataf['formData']=$formData;
        $formDataf['form']=$entities_form;
        $formDataf['role']=$role;
        return json_encode($formDataf);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntityFormDataRequest $request, EntitiesForm $entities_form)
    {
        $currentUser = Auth::user();
        $role= $currentUser->getRoleNames()->first();
        if($role == 'Field Staff'){
            $inputs = $request->all();
            $inputs['user_id'] = $currentUser->id;
            $inputs['region_id'] = $currentUser->regions()->first()->id;
            $inputs['entities_form_id'] = $entities_form->id;
            $formInputs = json_decode($entities_form->inputs);
            foreach($formInputs as $formInput){
                if ($formInput->element == 'Camera') {
                    if($request->hasFile($formInput->field_name))
                    {
                        $image = $request->file($formInput->field_name);
                        $destinationpath ='public/images/entities_form_datas';
                        $imagename = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                        $path = $image->storeAs($destinationpath, $imagename);
                        $formDataImagePath = "/storage/images/entities_form_datas/{$imagename}";
                        $inputs['input_datas']= json_decode($inputs['input_datas'],true);
                        foreach ($inputs['input_datas'] as $index=>$inputData ) {
                            if ($inputData['name'] == $formInput->field_name) {
                                $inputs['input_datas'][$index]['value'] = $formDataImagePath;
                                // dd($path,$inputData,$inputs);
                            }
                        }
                        $inputs['input_datas']= json_encode($inputs['input_datas']);
                    }
                }
            }
            $inputs['input_datas']= json_decode($inputs['input_datas'],true);
            foreach ($inputs['input_datas'] as $inputData ) {
                if ($inputData['name'] == "text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28") {
                    $inputs['name'] = $inputData['value'];
                    continue;
                }
            }
            $inputs['input_datas']= json_encode($inputs['input_datas']);
            $formData = EntitiesFormData::create($inputs);
            $formData->clients()->sync($entities_form->clients()->first()->id);
            $formData->input_datas = json_decode($formData->input_datas);
        }else{
            $formData= [];
            return json_encode($formData);
        }
        return $formData->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EntitiesFormData  $entitiesFormData
     * @return \Illuminate\Http\Response
     */
    public function show(EntitiesForm $entities_form, $entitiesFormDataId)
    {
        $userId = Auth::user()->id;
        $entitiesFormData =  EntitiesFormData::with('entitiesForm')->where([
            ['user_id','=',$userId],
            ['entities_form_id','=',$entities_form->id],['id','=',$entitiesFormDataId]])->firstOrFail();
        $entitiesFormData->input_datas = json_decode($entitiesFormData->input_datas, true);
        $entitiesFormData->entitiesForm->inputs = json_decode($entitiesFormData->entitiesForm->inputs);
        
        return $entitiesFormData->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EntitiesFormData  $entitiesFormData
     * @return \Illuminate\Http\Response
     */
    public function edit(EntitiesFormData $entitiesFormData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EntitiesFormData  $entitiesFormData
     * @return \Illuminate\Http\Response
     */
    public function update(EntityFormDataRequest $request, EntitiesForm $entities_form, $entitiesFormDataId)
    {
        $inputs = $request->except(['user_id','entities_form_id','region_id']);
        $userId = Auth::user()->id;
        $formData = EntitiesFormData::where([ ['user_id','=',$userId], ['entities_form_id','=',$entities_form->id]])->findOrFail($entitiesFormDataId);
        $formInputs = json_decode($entities_form->inputs);
        $formData['input_datas']= json_decode($formData['input_datas'],true);
        foreach($formInputs as $formInput){
            if ($formInput->element == 'Camera') {
                if($request->hasFile($formInput->field_name))
                {
                    foreach($formData['input_datas'] as $inputData ){
                        if($formInput->field_name == $inputData['name']){
                            $formDataImage = $inputData['value']?public_path($inputData['value']):null;
                            if(File::exists($formDataImage))
                            {
                                File::delete($formDataImage);
                            }
                        }
                    }
                    $image = $request->file($formInput->field_name);
                    $destinationpath ='public/images/entities_form_datas';
                    $imagename = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                    $path = $image->storeAs($destinationpath, $imagename);
                    $formDataImagePath = "/storage/images/entities_form_datas/{$imagename}";
                    $inputs['input_datas']= json_decode($inputs['input_datas'],true);
                    foreach ($inputs['input_datas'] as $index=>$inputData ) {
                        if ($inputData['name'] == $formInput->field_name) {
                            $inputs['input_datas'][$index]['value'] = $formDataImagePath;
                            // dd($path,$inputData,$inputs);
                        }
                    }
                    $inputs['input_datas']= json_encode($inputs['input_datas']);
                }else{
                    $inputs['input_datas']= json_decode($inputs['input_datas'],true);
                    foreach ($inputs['input_datas'] as $index=>$inputData ) {
                        if ($inputData['name'] == $formInput->field_name) {
                            $inputs['input_datas'][$index]['value'] = $formData['input_datas'][$index]['value'];
                            // dd($path,$inputData,$inputs);
                        }
                    }
                    $inputs['input_datas']= json_encode($inputs['input_datas']);
                }
            }
        }
        $inputs['input_datas']= json_decode($inputs['input_datas'],true);
        foreach ($inputs['input_datas'] as $inputData ) {
            if ($inputData['name'] == "text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28") {
                $inputs['name'] = $inputData['value'];
                continue;
            }
        }
        $inputs['input_datas']= json_encode($inputs['input_datas']);
        $formData->update($inputs);
        $formData->input_datas = json_decode($formData->input_datas);
        return $formData->toJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EntitiesFormData  $entitiesFormData
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntitiesForm $entities_form, $entitiesFormDataId)
    {
        $userId = Auth::user()->id;
        $formData = EntitiesFormData::where([ ['user_id','=',$userId], ['entities_form_id','=',$entities_form->id]])->findOrFail($entitiesFormDataId);
        $formInputs = json_decode($entities_form->inputs);
        $formData['input_datas']= json_decode($formData['input_datas'],true);
        $formDataImage = $formData['image']?public_path($formData['image']):null;
        if(File::exists($formDataImage))
        {
            File::delete($formDataImage);
        }
        foreach($formInputs as $formInput){
            if ($formInput->element == 'Camera') {
                foreach($formData['input_datas'] as $inputData ){
                    if($formInput->field_name == $inputData['name']){
                        $formDataImage = $inputData['value'] ? public_path($inputData['value']) : null;
                        if(File::exists($formDataImage)){
                            File::delete($formDataImage);
                        }
                    }
                }
            }
        }
        $formData->delete();
        return response()->json(['message' => 'successfully deleted.'], 204);
    }

    public function deleteEntityData($entity_id){
        $entity = EntitiesFormData::findOrFail($entity_id);
        $reports = ReportData::whereEntityId($entity_id)->get();
        $entity_data = json_decode($entity->input_datas, true);
        foreach($entity_data as $input){
            if(Str::contains($input['name'], 'camera')){
                Storage::delete(Str::replaceFirst('storage', 'public',$input['value']));
            }
        }
        foreach($reports as $report){
            ReportData::destroy($report->id);
        }
        $entity->delete();
        $entities_group = Entitygroup::get();
        foreach($entities_group as $group){
            $ids = json_decode($group->entity_ids, true);
            if(in_array( $entity_id, $ids)){
                $filter_ids = array_filter($ids, function($id) use ($entity_id){
                    if($id != $entity_id) return true; return false;
                });
                $group->entity_ids = json_encode($filter_ids);
                $group->save();
            }
        }
        
        return response()->json([
            'message' => 'Entity data and its related report data deleted',
         ], 200);
    }

    public function getEntityLocation($id){
        $user = Auth::user();
        $company = $user->companies()->first();
        $role = $user->getRoleNames()->first();
        $entities_location = $company->entitiesFormData();
        $region_ids = [];
        $entity_forms = [];
        $company_staff = [];
        $staff_current_location = [];
        if($role == 'Super Admin'){
            $entities_location = $id ? (new EntitiesFormData)->whereEntitiesFormId($id) : new EntitiesFormData();
            $region_ids = Region::all()->pluck('id')->toArray();
            $entity_forms = $company->entitiesForms()->get()->toArray();
            $company_staff = Region::with('superAdminStaffs')->get();
        }else if($role == 'Admin'){
            $region_ids = Region::all()->pluck('id')->toArray();
            $company_staff = Region::with('adminStaffs')->get();
            $entity_forms = $company->entitiesForms()->get()->toArray();
        }else if($role == 'Regional Admin'){
            $region_ids = $user->regions()->get()->pluck('id')->toArray();
        }else if($role == 'Supervisor'){
            $region_ids = $user->regions()->get()->pluck('id')->toArray();
        }else{
            abort(401);
        }
      
        $entities_location =   $entities_location->approved()
                                ->whereIn('region_id', $region_ids)
                                ->get();
        $assigned_entities_location = EntitiesFormData::whereIn('entities_form_id', $company->entityForm()->get()->pluck('id')->toArray())
                                        ->approved()
                                        ->whereIn('region_id', $region_ids)
                                        ->get();
        
        return response()->json([
            'role' => $role,
            'entities_location' => EntityLocationResource::collection($entities_location),
            'entities_forms' => $entity_forms,
            'company_staff' => $company_staff,
            'assigned_entities_location' =>AssignedEntityLocationResource::collection( $assigned_entities_location),
            // 'staff_current_location' => $staff_current_location
        ], 200);
    }
    public function getEntityData($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $entity_data = EntitiesFormData::whereId($id)->with('entitiesForm:id,inputs,form_title')->first();
        $entityInputs = json_decode($entity_data->entitiesForm->inputs, true);
        $formDatas = json_decode($entity_data->input_datas, true);
        $question = [];
        $answer = [];
        $formInputs = [];
        foreach ($entityInputs as $key => $formInput) {
            if ($formInput['element'] != 'Header'){
                array_push($formInputs, [ 
                    'element' => trim($formInput['element']), 
                    'field_name' => trim($formInput['field_name']),
                    'label' => trim($formInput['label'])
                ] );
            }else{
                array_push($formInputs, [ 
                    'element' => trim($formInput['element']), 
                    'label' => trim($formInput['content'])
                ] );
            }
        }
        foreach ($formInputs as $formInput) {
            if($formInput['element'] == 'Header'){
                array_push($question, $formInput);
                array_push($answer, "Header");
            }else{
                foreach($formDatas as $key => $formData){
                    if($formInput['field_name'] == $formData['name']){
                        $check = false;
                        array_push($question, $formInput);
                        if($formInput['element'] == 'Tags' || $formInput['element'] == 'Checkboxes' || 
                            $formInput['element'] == 'RadioButtons' ){
                            $datas = $formData['value'];
                            $value = [];
                            foreach($datas as $index => $data){
                                array_push($value, $data['text']);
                            }
                            array_push($answer, implode(', ',$value));
                        }
                        else if($formInput['element'] == 'NumberInput' || $formInput['element'] == 'TextArea' || 
                            $formInput['element'] == 'TextInput' || $formInput['element'] == 'Dropdown'){
                            if($formData['value'])
                                array_push($answer,  $formData['value']);
                            else $check = true;
                        }else if($formInput['element'] == 'DatePicker'){
                            if($formData['value'])
                                array_push($answer,  Carbon::parse($formData['value'])->toDateString());
                            else $check = true;
                        }
                        else if($formInput['element'] == 'Camera'){
                            if($formData['value'])
                                array_push($answer,  $formData['value']);
                            else $check = true;
                        }
                        if($check)
                            array_push($answer, 'No data filled!');
                        unset($formDatas[$key]);
                    }
                }
            }
        }  
        return response()->json([
            'question' => $question,
            'answer' => $answer,
            'image_path' => 'storage/images/report_data_images',
            'role' => $role,
            'status' => $entity_data->status,
            'title' => $entity_data->entitiesForm->form_title,
            'name' => $entity_data->name,
        ], 200);
    }

    public function reportGenerate(Request $request, $form_id){
        $validator = Validator::make($request->all(), [
            'file_type' =>"required",
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $data = $request->only(['file_type']);
        $user = Auth::user();
        $company = $user->companies()->first();
        $role= $user->getRoleNames()->first();
        $all_data = EntitiesFormData::whereEntitiesFormId($form_id)
            ->approved()
            ->with(['entitiesForm:id,form_title,inputs'])
            ->get();

        if(!count($all_data)){
            return response()->json([
                'message' => 'No Entity Data Found.',
            ], 404);
        }
        if($data['file_type'] == "csv")
            return Excel::download( new ClientEntityDataExport($form_id, $all_data), "entity_data_export.csv"); 
        else
            return Excel::download( new ClientEntityDataExport($form_id, $all_data), "entity_data_export.xlsx");
    }
}
