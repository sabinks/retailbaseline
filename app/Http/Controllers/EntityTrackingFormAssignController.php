<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Region;
use Carbon\Carbon;
use App\EntitiesForm;
use App\EntitiesFormData;
use Illuminate\Http\Request;
use Illuminate\Support\Collection; 
use Illuminate\Support\Facades\Auth;

class EntityTrackingFormAssignController extends Controller
{
    public function index(){
        $user = Auth::user();
        $company = $user->companies()->first();
        $regions = $user->regions;
        $total_entities_forms = new Collection();
        $forms = DB::table('client_entities_form')->whereClientId($company->id)->get('entities_form_id');
        foreach($forms as $form){
            $entity_form = DB::table('entities_forms')->whereId($form->entities_form_id)->get(['id','form_title','user_id']);
            $total_entities_forms = $total_entities_forms->merge($entity_form);
        }
        return response()->json([
            'forms'=>$total_entities_forms,
            'regions'=>$regions
        ]);
    }

    public function filedStaffList($id){
        $user = Auth::user();
        $region = Region::find($id);
        $company = $user->companies()->first();
        $admin = $company->users()->role('Admin')->first();
        $assigned_staff =$admin->fieldstaffs()->whereStaffStatus(3)->get();
        $fieldStaffs = $company->users()->role('Field Staff')->get();
        $staffs = new Collection();
        $staffs = $fieldStaffs->merge($assigned_staff);
        $staff_list = [];
        foreach($staffs as $staff){
            if($staff->regions()->first()->id == $region->id){
                $staff_list[] = [
                    'id' => $staff->id,
                    'value' => $staff->name,
                    'label' => $staff->name
                ];
            }
        }
        return response()->json([
            'staffs'=>$staff_list
        ]); 
    }

    public function assign(Request $request){
        $assigner = Auth::user();
        $company = $assigner->companies()->first();
        $input['assigner_id'] = $assigner->id;
        $Staff_list = json_decode($request->user_ids, true);
        $input['entities_form_id'] = request('form_id');
        $input['entity_visit_count'] = 1;
        $result;
        $incr = 0;
        $bad_message = '';
        $good_message = '';
        DB::beginTransaction();
        try{
            if($Staff_list==NULL){
                return response()->json([
                    'message' =>'Please select at one field staff!',
                ], 400);
            }
            else{
                foreach($Staff_list as $staff){
                    $count = DB::table('entities_form_user')
                                ->whereUserId($staff)
                                ->whereEntitiesFormId($input['entities_form_id'])
                                ->first();
                    if(!$count){
                        $input['user_id'] = $staff;
                        $input['created_at'] = $input['updated_at'] = Carbon::now();
                        $result = DB::table('entities_form_user')->insert($input);
                        $user = User::whereId($staff)->first();
                        $good_message = $good_message.$user->name.',';
                    }
                    else{
                        $incr = $incr+1;
                        $user = User::whereId($staff)->first();
                        $bad_message = $bad_message.$user->name.',';
                    }
                }
                DB::commit();
                if($incr == 0){
                    return response()->json([
                        'message' => 'Entity tracking form assigned to :-'.$good_message,
                    ], 200);
                }
                else{
                    if($good_message==''){
                        return response()->json([
                            'message' => 'Can not Re-assign to :-'.$bad_message,
                        ], 400);
                    }
                    else{
                        return response()->json([
                            'message' => 'Can not Re-assign to :-'.$bad_message.' But assigned to:-'.$good_message,
                        ], 400);
                    }
                }
            }
        }catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function assignedList(){
        $login_user = Auth::user();
        $assigned_forms = DB::table('entities_form_user')->where('assigner_id',$login_user->id)->get();
        $data = [];
        foreach($assigned_forms as $entityForm){
            $form = EntitiesForm::whereId($entityForm->entities_form_id)->first();
            array_push($data,$entityForm->entities_form_id);
            array_push($data,$form->form_title);
            $assigned = User::whereId($entityForm->user_id)->first();
            array_push($data,$assigned->name);
            array_push($data,$assigned->id);
        }
        $data = array_chunk($data,4);
        $collection = collect(['form_id','form_title','assigned','assigned_id']);
        $combined = [];
        foreach($data as $datum){
            $combined[] = $collection->combine($datum);
        }
        return response()->json([
            'forms'=>$combined
        ]);
    }

    public function removeEntityForm($form_id, $assigned_id){
        $form = DB::table('entities_form_user')->whereUserId($assigned_id)
                    ->whereEntitiesFormId($form_id)->first();
        if($form){
            DB::table('entities_form_user')->whereUserId($assigned_id)
                    ->whereEntitiesFormId($form_id)->delete();
            return response()->json([
                'message' => 'Entity form removed!',
            ], 200);
        }
        return response()->json([
            'message' =>"Please select appropriate data!"
        ], 400);
    }
}
