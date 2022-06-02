<?php

namespace App\Http\Controllers\API\V1;

use Auth;
use File;
use App\FormData;
use Carbon\Carbon;
use App\EntitiesFormData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EntityFormDataRequest;

class EntityFormDataController extends Controller
{
    public function index($entityFormId)
    {
        $userId = Auth::id();
        $entities_form = Auth::user()->assignedEntitiesForms()->with('formCreator')->findOrFail($entityFormId);
        $formData = $entities_form->entitiesFormData()->where('user_id',$userId)->get();
        foreach ($formData as $formDatum) {
            $formDatum->input_datas = json_decode($formDatum->input_datas);  
        }
        $entities_form->inputs=json_decode($entities_form->inputs);
        $formDataf['formData']=$formData;
        $formDataf['form']=$entities_form;

        return response()->json(['data'=>$formDataf], 200);
    }

    public function rejected()
    {
        $userId = Auth::id();
        // 3 for rejected status
        $formData = EntitiesFormData::where([['user_id', '=', $userId],['status', '=', 3]])->get();
        $entities_form = [];
        foreach ($formData as $index => $formDatum) {
            $entities_form[$index]['entitiesData'] = $formDatum->entitiesForm;  
            $entities_form[$index]['entitiesData']->inputs=json_decode($entities_form[$index]['entitiesData']->inputs);
            $formDatum['input_datas'] = json_decode($formDatum['input_datas']);
            $formDatum->unsetRelation('entitiesForm');
            $entities_form[$index]['entitiesAnswer']=$formDatum;
        }

        return response()->json(['data'=>$entities_form], 200);
    }
    public function listFilledFormList(){
        $userId = Auth::id();
        $formData = EntitiesFormData::whereUserId($userId)->whereIn('status', [1,2])->get();
        $entities_form = [];
        foreach ($formData as $index => $formDatum) {
            $entities_form[$index]['entitiesData'] = $formDatum->entitiesForm;  
            $entities_form[$index]['entitiesData']->inputs=json_decode($entities_form[$index]['entitiesData']->inputs);
            $entities_form[$index]['status'] = $this->statusConversion($formDatum->status) ;
            $formDatum['input_datas'] = json_decode($formDatum['input_datas']);
            $formDatum->unsetRelation('entitiesForm');
            $entities_form[$index]['entitiesAnswer']=$formDatum;
        }

        return response()->json(['data'=>$entities_form], 200);
    }
    public function store(EntityFormDataRequest $request, $entityFormId)
    {
        \Log::info('here here');
        $user = Auth::user();
        $entity_form = $user->assignedEntitiesForms()->findOrFail($entityFormId);
        $inputs = $request->all();
      
        $inputs['user_id'] = Auth::id();
        $inputs['region_id'] = $user->regions()->first()->id;
        $inputs['entities_form_id'] = $entity_form->id;
       
        $formInputs = json_decode($entity_form->inputs);
       
        $inputs['input_datas']= json_decode($inputs['input_datas'],true);
        $inputs['assigned_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $user->formAssigned()->first()->pivot->created_at, 'UTC')->timezone('Asia/Kathmandu')->format('Y-m-d');
        $inputs['filled_date'] = Carbon::now('Asia/Kathmandu')->toDateString();
        foreach($formInputs as $formInput){
            if ($formInput->element == 'Camera') {
                if($request->hasFile($formInput->field_name))
                {
                    $image = $request->file($formInput->field_name);
                    $destinationpath ='public/images/entities_form_datas';
                    $imagename = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                    $path = $image->storeAs($destinationpath, $imagename);
                    $formDataImagePath = "storage/images/entities_form_datas/{$imagename}";
                    foreach ($inputs['input_datas'] as $index=>$inputData ) {
                        if ($inputData['name'] == $formInput->field_name) {
                            $inputs['input_datas'][$index]['value'] = $formDataImagePath;
                        }
                    }
                }
            }
        }
        foreach ($inputs['input_datas'] as $inputData ) {
            if ($inputData['name'] == "text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28") {
                $inputs['name'] = $inputData['value'];
                break;
            }
        }
        $inputs['input_datas']= json_encode($inputs['input_datas']);
        $formData = EntitiesFormData::create($inputs);
        $formData->clients()->sync($entity_form->clients()->first()->id);
        $formData->input_datas = json_decode($formData->input_datas);
        return response()->json(['data'=> $formData, 'message' => 'Entity Stored!'], 201);
    }

    public function show($entityFormId, $entitiesFormDataId)
    {
        $entities_form = Auth::user()->assignedEntitiesForms()->findOrFail($entityFormId);
        $userId = Auth::user()->id;
        $entitiesFormData =  EntitiesFormData::with('entitiesForm')->where([
            ['user_id','=',$userId],
            ['entities_form_id','=',$entities_form->id],['id','=',$entitiesFormDataId]])->firstOrFail();
        $entitiesFormData->input_datas = json_decode($entitiesFormData->input_datas, true);
        $entitiesFormData->entitiesForm->inputs = json_decode($entitiesFormData->entitiesForm->inputs);
        
        return response()->json(['data'=>$entitiesFormData], 200);
    }

    public function update(EntityFormDataRequest $request, $entityFormId, $entitiesFormDataId)
    {
        
        $entities_form = Auth::user()->assignedEntitiesForms()->with('formCreator')->findOrFail($entityFormId);
        $userId = Auth::id();
        
        $formData = EntitiesFormData::where([ ['user_id','=',$userId], ['entities_form_id','=',$entities_form->id]])->findOrFail($entitiesFormDataId);
        $inputs = $request->except(['user_id','entities_form_id','region_id']);
        $formInputs = json_decode($entities_form->inputs);
        $formData['input_datas']= json_decode($formData['input_datas'],true);
        $inputs['status']= 1; // to send the form data back to supervisor for accepting or rejecting 
        $inputs['input_datas']= json_decode($inputs['input_datas'],true);
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
                    $formDataImagePath = "storage/images/entities_form_datas/{$imagename}";
                    foreach ($inputs['input_datas'] as $index=>$inputData ) {
                        if ($inputData['name'] == $formInput->field_name) {
                            $inputs['input_datas'][$index]['value'] = $formDataImagePath;
                        }
                    }
 
                }else{
                   
                    foreach ($inputs['input_datas'] as $index=>$inputData ) {
                        if ($inputData['name'] == $formInput->field_name) {
                            $inputs['input_datas'][$index]['value'] = $formData['input_datas'][$index]['value'];
                        }
                    }
                }
            }
        }
        foreach ($inputs['input_datas'] as $inputData ) {
            if ($inputData['name'] == "text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28") {
                $inputs['name'] = $inputData['value'];
                break;
            }
        }
        $inputs['input_datas']= json_encode($inputs['input_datas']);
        $formData->update($inputs);
        
        $formData->input_datas = json_decode($formData->input_datas);

        return response()->json(['data'=>$formData, 'message' => 'Rejected Form Updated!'], 200);
    }

    public function destroy( $entityFormId, $entitiesFormDataId)
    {
        $entities_form = Auth::user()->assignedEntitiesForms()->with('formCreator')->findOrFail($entityFormId);
        $userId = Auth::id();
        $formData = EntitiesFormData::where([ ['user_id','=',$userId], ['entities_form_id','=',$entities_form->id]])->findOrFail($entitiesFormDataId);
        $formData['input_datas']= json_decode($formData['input_datas'],true);
        $formDataImage = $formData['image']?public_path($formData['image']):null;
        if(File::exists($formDataImage))
        {
            File::delete($formDataImage);
        }
        $formInputs = json_decode($entities_form->inputs);
        foreach($formInputs as $formInput){
            if ($formInput->element == 'Camera') {
                foreach($formData['input_datas'] as $inputData ){
                    if($formInput->field_name == $inputData['name']){
                   
                        $formDataImage = $inputData['value']?public_path($inputData['value']):null;
                        if(File::exists($formDataImage))
                        {
                            File::delete($formDataImage);
                        }
                    }
                }
            }
        }
        $formData->delete();

        return response()->json(['message' => 'successfully deleted.'], 204);
    }

    public function updateRejectedData(Request $request, $entitydata_id){
        \Log::info('here here here');
        $user = Auth::user();
        $formData = EntitiesFormData::findOrFail($entitydata_id);
        $inputs = $request->only(['input_datas']);
        $newFormData = json_decode($inputs['input_datas'], true);
        $formInputs = json_decode($formData->entitiesForm->inputs);
        $formData['status'] = 1;
        $formData['user_id'] = $user->id;
        $formData['input_datas']= json_decode($inputs['input_datas'],true);
        $formData['filled_date'] = Carbon::now('Asia/Kathmandu')->toDateString();
        foreach($formInputs as $formInput){
            if ($formInput->element == 'Camera') {
                if($request->hasFile($formInput->field_name)){
                    foreach($formData['input_datas'] as $inputData ){
                        if($formInput->field_name == $inputData['name']){
                            $images = $inputData['value'] ? public_path($inputData['value']) : null;
                            if(File::exists($images)){
                                File::delete($images);
                            }
                        }
                    }
                    $image = $request->file($formInput->field_name);
                    $destinationpath ='public/images/entities_form_datas';
                    $imagename = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                    $path = $image->storeAs($destinationpath, $imagename);
                    $formDataImagePath = "storage/images/entities_form_datas/{$imagename}";
                    
                    foreach($newFormData as $index => $inputData ) {
                        if ($inputData['name'] == $formInput->field_name) {
                            $newFormData[$index]['value'] = $formDataImagePath;
                        }
                    }
                }
            }
        }
        foreach ($newFormData as $inputData ) {
            if ($inputData['name'] == "text_input_F8FE64B1-5A21-4770-B218-2C0158FFAD28") {
                $formData['name'] = $inputData['value'];
                break;
            }
        }
        $formData['input_datas']= json_encode($newFormData);
        
        $formData->update();

        return response()->json([
            'message' => 'Form updated successfully.',
        ], 200);
    }

    public function statusConversion($status){
        switch ($status) {
            case 1:
                return 'Filled';
            case 2:
                return 'Approved';
            case 3:
                return 'Rejected';
        }
    }
}
