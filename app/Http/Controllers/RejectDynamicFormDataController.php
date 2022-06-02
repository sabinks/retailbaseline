<?php

namespace App\Http\Controllers;
use App\User;
use App\EntitiesForm;
use App\EntitiesFormData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class RejectDynamicFormDataController extends Controller
{
    public function index(){
        $user = Auth::user();
        $clientId = $user->companies[0]->id;
        $entitiesForms = EntitiesForm::with('formCreator')
            ->whereHas('clients', function(Builder $query) use ($clientId){
                $query->where('client_id', $clientId);
            })
            ->whereHas('entitiesFormData', function(Builder $query) use ($user){
                $query->where('region_id', $user->regions[0]->id);
            })
            ->get();

        return view('components.entities.RejectDynamicForm',[
            'entitiesForms'=>$entitiesForms
        ]);
    }

    public function getEntitiesFormDataForForm($entitiesFormId){
        $user = Auth::user();
        $clientId = $user->companies[0]->id;
        $entitiesFormData = EntitiesFormData::with('formFiller', 'entitiesForm')
                            ->whereHas('clients', function(Builder $query) use ($clientId){
                                $query->where('client_id', $clientId);
                            })
                            ->whereRegionId($user->regions[0]->id)
                            ->whereEntitiesFormId($entitiesFormId)
                            ->filled()
                            ->get();
        // dd($entitiesFormData);
        return view('components.entities.RejectDynamicFormData',[
            'entitiesFormData'=>$entitiesFormData
        ]);
    }

    public function accept($id){
        $user = Auth::user();
        $user_region = $user->regions[0]->id;
        $entity = EntitiesFormData::findOrFail($id);
        if($entity->region_id == $user_region){
            $entity->update(['status' => 2]);
            return back()->with('Accept',$entity->name.' is accepted successfully');
        }
        else{
           abort(403); 
        }
    }

    public function changeEntityStatus($status, $id){
        $user = Auth::user();
        $company_id = $user->companies->first()->id;       
        $entity_data = EntitiesFormData::whereId($id)->first();
        $form_creator_id = $entity_data->entitiesForm->user_id;
        $form_creator_company_id = User::find($form_creator_id)->companies->first()->id;
        if($company_id == $form_creator_company_id){
            $entity_data->update([
                                    'status' => $status == 'accepted' ? 2 : 3
                                ]);
            
            return response()->json([
                'message' => 'Entity data ' . $status . '!',
                'status' => $entity_data->status
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'User not authorized!'
            ], 403);
        }
    }

    public function reject($id){
        $user = Auth::user();
        $user_region = $user->regions[0]->id;
        $entity = EntitiesFormData::findOrFail($id);
        if($entity->region_id == $user_region){
            $entity->update(['status' => 3]);
            return back()->with('reject',$entity->name.' is rejected successfully');
        }
        else{
           abort(403); 
        }
    }

    public function acceptedEntityList(){
        $user = Auth::user();
        $clientId = $user->companies[0]->id;
        $entities = EntitiesFormData::with('formFiller', 'entitiesForm')
            ->whereHas('clients', function(Builder $query) use ($clientId){
                $query->where('client_id', $clientId);
            })
            ->whereRegionId($user->regions[0]->id)
            ->approved()
            ->get();

        return json_encode(['data' => $entities]);
    }

    public function rejectedEntityList(){
        $user = Auth::user();
        $clientId = $user->companies[0]->id;
        $entities = EntitiesFormData::with('formFiller', 'entitiesForm')
            ->whereHas('clients', function(Builder $query) use ($clientId){
                $query->where('client_id', $clientId);
            })
            ->whereRegionId($user->regions[0]->id)
            ->rejected()
            ->get();

        return json_encode(['data' => $entities]);
    }
}
