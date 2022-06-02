<?php

namespace App\Http\Controllers\SuperAdmin;

use App\User;
use App\Entitygroup;
use App\Models\Report;
use App\EntitiesFormData;
use App\Models\ReportData;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use App\Models\AssignedReportForm;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportDataController extends Controller{

    public function index(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $reports_list = [];
        $entity_groups = [];
        $assigned_reports;
        $entity_groups = Entitygroup::whereCreatorId($user->id)->get();

        $report_forms = Report::whereCreatorId($user->id)->get();
        $assigned_report_forms_ids = AssignedReportForm::whereAssignedId($user->id)->get()->pluck('report_id')->toArray();
        $assigned_report_forms = [];
        if($assigned_report_forms_ids){
            $assigned_report_forms = Report::whereId([$assigned_report_forms_ids])->get();
        }
        $reports_list = Report::whereClientId($company->id)->whereCreatorId($user->id)->get();
        $merged_report_forms = $report_forms->merge($assigned_report_forms);
        foreach ($merged_report_forms as $key => $report_form) {
            $report_form['title'] = $report_form->title;
            $report_form['id'] = $report_form->id;
            $report_data = ReportData::whereReportId($report_form->id)
                ->whereCreatorId($user->id)
                ->select('entitygroup_id')->distinct('entitygroup_id');
            $report_form['entity_groups'] = $report_data->with('reportEntityGroup:id,group_name')->get();
            foreach ($report_form['entity_groups'] as $key => $report) {
                $report_form['entity_groups'][$key]['staff_count'] = $report->whereEntitygroupId( $report_form['entity_groups'][$key]['entitygroup_id'])
                                                                        ->whereReportId($report_form->id)
                                                                        ->select('staff_id')->distinct('staff_id')
                                                                        ->count();
            }
        }

        return response()->json([
            'reports' => $reports_list,
            'role' => $role,
            'assigned_reports' => $merged_report_forms,
            'entity_groups' => $entity_groups
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $report = ReportData::findOrFail($id);
        $report_images = ReportImage::whereReportdataId($report->id)->get();
       if($report_images){
            foreach($report_images as $report_image){
                \Log::info($report_image['image_name']);
                Storage::delete('public/images/report_data_images/'. $report_image['image_name']);
                $report_image->delete();
            }
       }
        $report->delete();

        return response()->json([
            'message' => 'Report data deleted',
         ], 200);
    }

    public function reportBulkApprove(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        DB::beginTransaction();
        try{
            $all_data = ReportData::whereCreatorId($user->id)
                ->whereNotNull('data')
                ->whereNotNull('filled_date')
                ->pending()
                ->update(['status' => 3]);
            DB::commit();
            return response()->json([
               'message' => $all_data ? 'Filled Regular Report Approved!' : 'No Regular Report Found!',
               'role' => $role
            ], $all_data ? 200 : 404);
        }catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message'=>$th->getMessage()], 400);
        }
    }

    public function allStaffList(Request $request){
        $user = Auth::user();
        $company = $user->companies()->first();
        $all_user = DB::table('company_user')->whereCompanyId($company->id)->get()->pluck('user_id')->toArray();
        $all_staff_list = User::whereIn('id', $all_user)
                        ->role('Field Staff')
                        ->get();
        
        return response()->json([
            'staff_list' => $all_staff_list
        ], 200);
    }

    public function getEntityList($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $ids = json_decode(EntityGroup::findOrFail($id)->entity_ids);
        $entities_list = EntitiesFormData::whereIn('id', $ids)->get((['id', 'name as value','name as label', 'region_id']));

        return response()->json([
            'entities_list' => $entities_list,
        ], 200);
    }

    public function assignStaff(Request $request){
        $validator = Validator::make($request->all(),[
            'report_id' =>"required",
            'staff_id' =>"required",
            'assign_date' =>"required",
            'entitygroup_id' =>"required",
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $input['report_id'] = $request->report_id; 
        $input['staff_id'] = (int)$request->staff_id;
        $input['assigned_date'] = $request->assign_date;
        $input['creator_id'] = $user->id;
        $input['entitygroup_id'] = (int)$request->entitygroup_id;
        $count = ReportData::whereReportId($input['report_id'])
                    ->whereStaffId($input['staff_id'])->whereAssignedDate($input['assigned_date'])
                    ->whereEntitygroupId($input['entitygroup_id'])->count();
        if($count){
            return response()->json([
                'message' => 'Duplicate entry exists, please recheck!',
            ], 400);
        }
        $entity_ids = json_decode($request->entity_ids, true);
        $region_id = (int)User::find($input['staff_id'])->regionsList()->first()->id;
        $input['region_id'] = $region_id;
        $input['status'] = 1; //assigned
        DB::beginTransaction();
        try{
            $data = [];
            foreach($entity_ids as $id){
                $input['entity_id'] = $id;
                array_push($data, $input);
            }
            ReportData::insert($data);
            DB::commit();
            return response()->json([
                'message' => 'Task Assigned to Field Staff!',
                'data' => $data
            ], 200);
        }catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message'=>$th->getMessage()],400);
        }
    } 
   
}
