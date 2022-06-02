<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\Region;
use DataTables;
use Carbon\Carbon;
use App\Entitygroup;
use App\Models\Report;
use App\EntitiesFormData;
use App\Models\ReportData;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use App\Exports\ReportDataExport;
use App\Models\AssignedReportForm;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReportData\ReportDataList;
use App\Http\Resources\ReportData\ReportDataDetail;
use App\Http\Resources\ReportData\ReportDataResource;

class ReportDataController extends Controller
{
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
        if($role == 'Admin'){
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
        }else if($role == 'Regional Admin'){
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
        }else if($role == 'Supervisor'){
            $assigned_report_forms = AssignedReportForm::whereAssignedId($user->id)->get();
            $merged_report_forms = $assigned_report_forms;
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
        }else{
            abort(400);
        }

        return response()->json([
            'reports' => $reports_list,
            'role' => $role,
            'assigned_reports' => $merged_report_forms,
            'entity_groups' => $entity_groups
        ]);
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
        // $count = ReportData::whereReportId($input['report_id'])
        //             ->whereStaffId($input['staff_id'])
        //             ->whereAssignedDate($input['assigned_date'])
        //             ->whereEntitygroupId($input['entitygroup_id'])->count();
        // if($count){
        //     return response()->json([
        //         'message' => 'Duplicate entry exists, please recheck!',
        //     ], 400);
        // }
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

    public function allStaffList(Request $request){
        $entity_ids = json_decode($request->entity_list, true); //array data
        $region_ids = EntitiesFormData::whereIn('id', $entity_ids)->approved()->distinct('region_id')
                        ->pluck('region_id')->toArray();

        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $all_staff_list = [];

        if($role == "Admin"){
            $all_supervisor_list = $company->users()->role('Supervisor')->get()->pluck('id')->toArray();
            $filter_supervisor = DB::table('region_user')->whereRegionId($region_ids)->whereIn('user_id', $all_supervisor_list)->get()->pluck('user_id')->toArray();
            $all_staff_list = User::findOrFail(DB::table('fieldstaffs_supervisors')
            ->whereIn('supervisor_id', $filter_supervisor)
            ->get()->pluck('fieldstaff_id')->toArray());
        }else if($role == "Regional Admin"){
            $all_staff_list = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
            $filter_staff = DB::table('region_user')->whereRegionId($region_ids)->whereIn('user_id', $all_staff_list)->get()->pluck('user_id')->toArray();
            $all_staff_list = User::findOrFail(DB::table('fieldstaffs_supervisors')
                ->whereIn('fieldstaff_id', $filter_staff)
                ->get()
                ->pluck('fieldstaff_id')->toArray());
        }else if($role == "Supervisor"){
            $all_staff_list = User::findOrFail([DB::table('fieldstaffs_supervisors')->whereIn('supervisor_id', $user->id)->get()->pluck('fieldstaff_id')->toArray()]);
        }
        else{
            abort(400);
        }

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
            'entities_list' => $entities_list
        ], 200);
    }

    public function reportList(Request $request, $status, $id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $report_list = [];
        $form_id = intval($id);
        if($role == 'Super Admin'){
            $report_list = ReportData::whereCreatorId($user->id);
        }
        else if($role == 'Admin'){
            // $company_staff_list = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
            $assigned_staff_list = DB::table('fieldstaffs_supervisors')->whereCompanyId($company->id)->get()->pluck('fieldstaff_id')->toArray();
            $report_list = ReportData::whereIn('staff_id', $assigned_staff_list);
        }
        else if($role == 'Regional Admin'){
            $region_ids = $user->regionsList()->get()->pluck('id')->toArray();
            // $staff_list = $company->users()->role('Field Staff')->get()->flatten()->pluck('id')->toArray();
            $assigned_staff_list = DB::table('fieldstaffs_supervisors')->whereCompanyId($company->id)->get()->pluck('fieldstaff_id')->toArray();
            $report_list = ReportData::whereIn('staff_id', $assigned_staff_list)
                                ->whereIn('region_id', $region_ids);
        }
        else if($role == 'Supervisor'){
            $region = $user->regionsList()->first();
            // $staff_list = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
            $assigned_staff_list = DB::table('fieldstaffs_supervisors')->whereSupervisorId($user->id)->get()->pluck('fieldstaff_id')->toArray();
            $report_list = ReportData::whereIn('staff_id', $assigned_staff_list)
                                ->whereRegionId($region->id);
        }
        else{
            abort(400);
        }
        switch ($status) {
            case 'assigned':
                $report_list = $report_list->assigned();
                break;
            case 'pending':
                $report_list = $report_list->pending();
                break;
            case 'approved':
                $report_list = $report_list->approved();
                break;
            case 'rejected':
                $report_list = $report_list->rejected();
                break;
            case 'all':
                $report_list = $report_list;
                break;
        } 
        $report_list = $form_id ? $report_list->whereReportId($form_id) : $report_list;
        $report_list1 = $report_list
            ->with(['report:id,title', 'staffDetail:id,name,email,address,profile_image','entities:id,name,latitude,longitude'])
            ->latest()->get();
        $data = ReportDataList::collection($report_list1);
        //
        return Datatables::of($data)
            ->rawColumns(['options'])
            ->make(true);
    }

    public function getReportDetail($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company_id = $user->companies->first()->id;
        
        $reportdata = ReportData::whereId($id)->with(['report:id,title,data,creator_id'])->first(['id', 'report_id','data','status']);
        $report_creator_company_id = User::whereId($reportdata->report->creator_id)->first()->companies->first()->id;
        $report;
        if($role == 'Super Admin' || $role == 'Admin' || $role == 'Regional Admin' || $role = 'Supervisor'){
            $report = ReportData::whereId($id)
                        ->with(['report:id,title,data', 'staffDetail:id,name,email,address,profile_image','entities:id,name,latitude,longitude'])
                        ->first();
        }else{
            abort(410);
        }
        return response()->json([
            'report_detail' => new ReportDataDetail($report),
            'role' => $role,
            'can_update' =>  $report_creator_company_id == $company_id ? true : false   
        ], 200);
    }
    public function show($id){
        $user = Auth::user();
        $company_id = $user->companies->first()->id;
        $role= $user->getRoleNames()->first();
        $reportdata = ReportData::whereId($id)->with(['report:id,title,data,creator_id', 'entities'])->first(['id', 'report_id','data','status', 'entity_id']);
        $report_creator_company_id = User::whereId($reportdata->report->creator_id)->first()->companies->first()->id;

        
        if(!$reportdata->data){
            return response()->json([
            'message' => 'Data fill pending from field staff!'
            ], 404);
        }
         $report_images = ReportImage::whereReportdataId($id)
                            ->get(['id','image_name', 'form_field_name'])->toArray();
        $reportInputs = json_decode($reportdata->report->data, true);
        $formDatas = json_decode($reportdata->data, true);
        $question = [];
        $answer = [];
        $formInputs = [];
        foreach ($reportInputs as $key => $formInput) {
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
                            if($formData['value'][0])
                                array_push($answer,  $formData['value'][0]);
                            else $check = true;
                        }else if($formInput['element'] == 'DatePicker'){
                            if($formData['value'][0])
                                array_push($answer,  Carbon::parse($formData['value'][0])->toDateString());
                            else $check = true;
                        }
                        else if($formInput['element'] == 'Camera'){
                            foreach($report_images as $image){
                                if($image['form_field_name'] == $formInput['field_name']){
                                    array_push($answer, $image['image_name']);
                                    $check = false;
                                    break;
                                }else { $check = true; }
                            }
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
            'status' => $reportdata->status,
            'title' => $reportdata->report->title,
            'entity_name' =>  $reportdata->entities->name,
            'can_update' =>  $report_creator_company_id == $company_id ? true : false
        ], 200);
    }

    public function update($id, Request $request){
        $user = Auth::user();
        $company_id = $user->companies->first()->id;
        $role = $user->getRoleNames()->first();
        try {
            $report = ReportData::whereId($id)->first();
            $report_creator_company_id = User::whereId($report->creator_id)->first()->companies->first()->id;
            if(!json_decode($report->data, true)){

                return response()->json([
                    'message' => 'Assigned report form fill pending!',    
                ], 409);
            }else if($company_id != $report_creator_company_id){

                return response()->json([
                    'message' => 'User not authorized!'
                ], 403);
            }

            $statusChange = $request->status;
            $status = ['assigned' => 1, 'pending' => 2, 'approved' => 3, 'rejected' => 4 ];
            $report->status = $status[$statusChange];
            
            $report = $report->save();
            return response()->json([
                'report_detail' => new ReportDataDetail(ReportData::whereId($id)
                                ->with(['report:id,title,data', 'staffDetail:id,name,email,address,profile_image','entities:id,name,latitude,longitude'])
                                ->first()),
                'message' => 'Report status changed!',
                'role' => $role       
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error in changing status!'     
            ], 400);
        }
    }

    public function destroy($id)
    {
        $report = ReportData::findOrFail($id);
        $report_images = ReportImage::whereReportdataId($report->id)->get();
       if($report_images){
            foreach($report_images as $report_image){
                Storage::delete('public/images/report_data_images/'. $report_image['image_name']);
                $report_image->delete();
            }
       }
        $report->delete();

        return response()->json([
            'message' => 'Report data deleted',
         ], 200);
    }

    public function reportDetail($reportdata_id, $entitygroup_id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
       

        $staff_list = ReportData::whereReportId($reportdata_id)
                            ->whereCreatorId($user->id)
                            ->whereEntitygroupId($entitygroup_id)
                            ->select('staff_id')->distinct('staff_id')->get();

        $report_detail = [];
        foreach ($staff_list as $staff) {
           $data = ReportData::whereReportId($reportdata_id)
                            ->whereCreatorId($user->id)
                            ->whereEntitygroupId($entitygroup_id)
                            ->whereStaffId($staff->staff_id)
                            ->orderBy('assigned_date')
                            ->select(['staff_id', 'report_id', 'assigned_date'])->distinct(['staff_id', 'assigned_date', 'report_id'])
                            ->with(['report:id,title', 'staffDetail:id,name,email,address,profile_image'])
                            ->get()->toArray();
            array_push($report_detail, $data);
        }                 
                      
        $collection = collect($report_detail);
        $collapsed = $collection->collapse();
        $collapsed->all();
        return response()->json([
            'report_detail' => $collapsed,
            'role' => $role
        ], 200);
    }

    public function reportGenerate(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'from_date' =>"required",
            'to_date' =>"required",
            'file_type' =>"required",
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $data = $request->only(['from_date', 'to_date', 'file_type']);
        $user = Auth::user();
        $company = $user->companies()->first();
        $role= $user->getRoleNames()->first();
        $all_data = ReportData::whereReportId($id)
            ->whereCreatorId($user->id)
            ->whereBetween('assigned_date', [$data['from_date'], $data['to_date']])
            ->approved()
            ->with(['report:id,title', 'staffDetail:id,name,email','entities:id,name,latitude,longitude'])
            ->get();

        if(!count($all_data)){
            return response()->json([
                'message' => 'No Report Data Found.',
            ], 404);
        }
        if($data['file_type'] == "csv")
            return Excel::download( new ReportDataExport($id, $all_data), "report_data_export.csv"); 
        else
            return Excel::download( new ReportDataExport($id, $all_data), "report_data_export.xlsx");
    }

    public function allReportsList(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $reports_list = [];
        $report_forms = Report::whereCreatorId($user->id)->get(['id', 'title']);
        $assigned_report_forms_ids = AssignedReportForm::whereAssignedId($user->id)->get(['report_id'])->toArray();
        $assigned_report_forms = [];
        if($assigned_report_forms_ids){
            $assigned_report_forms = Report::whereId($assigned_report_forms_ids)->get(['id', 'title']);
        }
        if($role == 'Admin' || $role == 'Regional Admin'){
            $reports_list = Report::whereClientId($company->id)->whereCreatorId($user->id)->get(['id', 'title']);
            $merged_report_forms = $report_forms->merge($assigned_report_forms);
        }else if($role == 'Supervisor'){
            $assigned_report_forms = AssignedReportForm::whereAssignedId($user->id)->get(['id', 'title']);
            $merged_report_forms = $assigned_report_forms; 
        }else{
            abort(400);
        }

        return response()->json([
            'role' => $role,
            'all_report_lists' => $merged_report_forms,
        ]);
    } 

    public function reportBulkApprove($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        DB::beginTransaction();
        $form_id = intval($id); 
        try{
            $all_data = $form_id ? ReportData::whereCreatorId($user->id)->whereReportId($form_id) : ReportData::whereCreatorId($user->id);
            $all_data = $all_data
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
    public function statusConversionOther($tatus){
        switch ($status) {
            case 1:
                return 'Assigned';
              case 2:
                return 'Pending';
              case 3:
                return 'Approved';
              case 4:
                return 'Rejected';
        }
    }
    public function getReportImages($entity_id){
        $report_data = ReportData::whereEntityId($entity_id)->approved()
                    ->with(['report:id,title', 'reportImages'])
                    ->paginate(1);
        if(!$report_data){
            
            return response()->json([
                'message' => 'No Report Found!' 
            ], 200);
        }
        else{
            return response()->json([
                'report_data' => $report_data 
            ], 200);
        }
    }

    public function getDailyReportCount($form_id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $report_list = $this->getReportDataQuery($user, $company, $role);
        $report_list = $report_list->approved()
                                ->whereDate('filled_date', '>=', Carbon::now()->startOfMonth())->whereDate('filled_date', '<=', Carbon::now()->endOfMonth())
                                ->groupBy('filled_date')->orderBy('filled_date', 'DESC');

        $report_count = $form_id ? $report_list->whereReportId($form_id)->get([DB::raw('Date(filled_date) as date'), DB::raw('COUNT(*) as "count"')]) 
                            : $report_list->get([DB::raw('Date(filled_date) as date'), DB::raw('COUNT(*) as "count"')]);

        $first_day = Carbon::now()->firstOfMonth()->format('d');
        $last_day = Carbon::now()->lastOfMonth()->format('d');
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        $current_date = Carbon::now()->format('M, Y');
        $data = [];
        for ($day = $first_day; $day <= $last_day; $day++) { 
            $date = Carbon::create($year, $month, $day, 0, 0, 0, 'Asia/Kathmandu')->format('Y-m-d');
            $day_wise_data['day'] = (int)$day;
            $day_wise_data['weekday'] = Carbon::now()->firstOfMonth()->addDay($day)->format('l');
            $day_wise_data['date'] = $date;
            $day_wise_data['count'] = 0;
            foreach ($report_count as $key => $report) {
                if($report->date === $date){
                    $day_wise_data['count'] = $report->count;
                }
            }
            $data[] = $day_wise_data;
        }

        return response()->json([
            'data' => $data,
            'date' => $current_date
        ]);
    }

    public function getReportDataQuery($user, $company, $role){
        $report_list;
        if($role == 'Super Admin'){
            $report_list = ReportData::whereCreatorId($user->id);
        }
        else if($role == 'Admin'){
            $assigned_staff_list = DB::table('fieldstaffs_supervisors')->whereCompanyId($company->id)->get()->pluck('fieldstaff_id')->toArray();
            $report_list = ReportData::whereIn('staff_id', $assigned_staff_list);
        }
        else if($role == 'Regional Admin'){
            $region_ids = $user->regionsList()->get()->pluck('id')->toArray();
            $assigned_staff_list = DB::table('fieldstaffs_supervisors')->whereCompanyId($company->id)->get()->pluck('fieldstaff_id')->toArray();
            $report_list = ReportData::whereIn('staff_id', $assigned_staff_list)
                                ->whereIn('region_id', $region_ids);
        }
        else if($role == 'Supervisor'){
            $region = $user->regionsList()->first();
            $assigned_staff_list = DB::table('fieldstaffs_supervisors')->whereSupervisorId($user->id)->get()->pluck('fieldstaff_id')->toArray();
            $report_list = ReportData::whereIn('staff_id', $assigned_staff_list)
                                ->whereRegionId($region->id);
        }

        return $report_list;
    }
}
