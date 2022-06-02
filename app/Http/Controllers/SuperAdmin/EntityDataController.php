<?php

namespace App\Http\Controllers\SuperAdmin;

use DataTables;
use App\EntitiesForm;
use App\EntitiesFormData;
use App\Models\ReportData;
use App\Models\ReportImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ReportDataList;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\EntityData\EntityDataResource;

class EntityDataController extends Controller
{
    public function entityList(Request $request, $status, $id){
        $user = Auth::user();
        $company = $user->companies()->first();
        $entity_form_ids = $company->entitiesForms()->get()->pluck('id')->toArray();
        $form_id = intval($id);
        $entity_list = EntitiesFormData::whereIn('entities_form_id', $id ? [$form_id] : $entity_form_ids);
        switch ($status) {
            case 'filled':
                $entity_list = $entity_list->filled();
                break;
            case 'approved':
                $entity_list = $entity_list->approved();
                break;
            case 'rejected':
                $entity_list = $entity_list->rejected();
                break;
            case 'all':
                $entity_list = $entity_list;
                break;
        } 
        // return $entity_list->get();
        $entity_list1 = $entity_list
            ->with(['formFiller', 'entitiesForm'])
            ->get();
        // return $entity_list1;
        $data = EntityDataResource::collection($entity_list1);
        
        return Datatables::of($data)
            ->rawColumns(['options'])
            ->make(true);
    }

    public function entityBulkApprove($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $form_id = intval($id);
        DB::beginTransaction();
        $all_data = $form_id ? $company->entitiesFormData()->whereEntitiesFormId($form_id) : $company->entitiesFormData();
        try{
            $all_data = $all_data
                ->whereNotNull('input_datas')
                // ->whereNotNull('created_at')
                ->whereIn('status', [1,3])
                ->update(['status' => 2]);
            DB::commit();
            return response()->json([
               'message' => $all_data ? 'Filled Entities Data Approved!' : 'No Entities Data Found!',
               'role' => $role
            ], $all_data ? 200 : 404);
        }catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message'=>$th->getMessage()], 400);
        }
    }
    public function destroy($entity_id){
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
        $entities_group = App\Entitygroup::get();
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
    public function entityDataCount($form_id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $ids = (int)$form_id == 0 ? EntitiesForm::whereUserId($user->id)->get()->pluck('id')->toArray() : [(int)$form_id];
        $total_approved = EntitiesFormData::query()
                                ->whereIn('entities_form_id', $ids)
                                ->approved()->count();
        $total_pending = EntitiesFormData::query()
                                ->whereIn('entities_form_id', $ids)
                                ->filled()->count();
        $total_rejected = EntitiesFormData::query()
                                ->whereIn('entities_form_id', $ids)
                                ->rejected()->count();
    
        return response()->json([
            'total_approved' => $total_approved,
            'total_pending' =>  $total_pending,
            'total_rejected' => $total_rejected
        ], 200);
    }
}
