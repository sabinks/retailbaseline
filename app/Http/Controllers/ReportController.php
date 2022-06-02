<?php

namespace App\Http\Controllers;

use Throwable;
use App\Company;
use App\EntitiesForm;
use App\Models\Report;
use App\EntitiesFormData;
use App\Models\ReportData;
use Illuminate\Http\Request;
use App\Models\AssignedReportForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        if ($role == 'Admin' || $role == 'Regional Admin' || $role == 'Supervisor') {

            $report_forms = Report::whereCreatorId($user->id)->get();
            $assigned_report_forms_ids = AssignedReportForm::whereAssignedId($user->id)->get()->pluck('report_id')->toArray();
            $assigned_report_forms = Report::find($assigned_report_forms_ids);
            $merged_report_forms = $report_forms->merge($assigned_report_forms);
            
            return response()->json([
                'reports' => $merged_report_forms,
                'role' => $role,
            ]);
        }
    }
    public function create(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = Company::whereId($user->companies()->first()->id)->first();
        $entities_group = EntitiesFormData::whereStatus(2)->get(); //need to filter entities of that company only
        
        return response()->json([
            'company' => $company,
            'role' => $role,
            'entities_list' => $entities_group,
            'creator_id' => $user->id,
            'client_id' => $company->id
        ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'data' => 'required',
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        DB::beginTransaction();
        try{
            $user = Auth::user();
            $company = Company::whereId($user->companies()->first()->id)->first();
            $data['title'] = $request->title;
            $data['data'] = $request->data;
            $data['client_id'] = $company->id;
            $data['creator_id'] = $user->id;
            Report::insert($data);
            
            DB::commit();

            return response()->json([
                'message' => 'Report Form Created!'
            ], 200);
        }catch (Throwable $th) {
            DB::rollback();

            return response()->json(['message'=>$th->getMessage()],400);
        }
    }
    public function edit(Report $report){
        $count = ReportData::whereReportId($report->id)->count();
        $count = 0;
        if($count){

            return response()->json([
                'message' => 'Report form is used, cannot be edit!',
            ], 401);
        }

        return response()->json([
            'report' => $report,
        ], 200);
    }

    public function update(Request $request, Report $report){
        $user = Auth::user();
        $company  = $user->companies()->first();
        $report_form = Report::whereId($report->id)->whereClientId($company->id)->first();
        if(!$report_form){
            
            return response()->json([
                'message' => 'No form found!',
            ], 401);
        }else if($report_form->creator_id != $user->id){    

            return response()->json([
                'message' => 'Cannot edit, unauthorized user!',
            ], 401);
        }
        $orginal_form = json_decode($report_form->data,true);
        $new_form = json_decode($request->data, true);
        $form_changed = $this->formChanged($orginal_form, $new_form); 
        if($form_changed){

            return response()->json([
                'message' => 'Form inputs cannot be deleted, inputs can be arranged or edited!',
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'data' => 'required',
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        $report_form->title = $request->title;
        $report_form->data = $request->data;
        $report_form->save();

        return response()->json([
            'message' => 'Form Updated!'
        ], 200);
    }
    public function destroy(Report $report){
        $user = Auth::user();
        $assigned_report_form_count = AssignedReportForm::whereReportId($report->id)->whereAssignedId($user->id)->count();
        if($assigned_report_form_count){

            return response()->json([
                'message' => 'Cannot delete, unauthorized user!',
            ], 401);
        }
        $reportdata_count = ReportData::whereReportId($report->id)->count();
        if($reportdata_count){

            return response()->json([
                'message' => 'Report form is used, cannot be deleted!',
            ], 401);
        }
        $report->delete();

        return response()->json([
            'message' => 'Report Form Deleted!'
        ], 200);
    }
    public function reportDetail($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        if ($role == 'Admin' || $role == 'Regional Admin' || $role == 'Supervisor') {
            $report = Report::findOrFail($id);

            return response()->json([
                'report' => $report,
                'role' => $role
            ]);
        }
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

    public function getReportFormAssignedForms(){
        $user = Auth::user();
        $company  = $user->companies()->first();
        $report_form_list = $company->reportForm;

        return response()->json([
            'report_forms_list' => $report_form_list,
        ]);
    }

    public function getReportFormAssignedList($id){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $report_datas = ReportData::whereReportId($id)->approved()
                        ->select(['id', 'report_id', 'filled_date', 'entity_id', 'staff_id'])
                        ->with(['report:id,title','entities:id,name', 'staffDetail:id,name'])->orderBy('filled_date','desc');
        if($role == 'Admin'){
            $report_datas = $report_datas->get();
        }else if($role == 'Regional Admin'){
            $region_ids = $user->regionsList()->get()->pluck('id')->toArray();
            $report_datas = $report_datas->whereIn('region_id', $region_ids)->get();
        }else if($role == 'Supervisor'){
            $region = $user->regionsList()->first()->pluck('id')->toArray();
            $report_datas = $report_datas->whereIn('region_id', $region_ids)->get();
        }else{
            abort(410);
        }
      
        return response()->json([
            'report_list' => $report_datas,
        ]);
    }

    public static function getReportForm(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $assigned_report_forms = [];
        if ($role == 'Admin' || $role == 'Regional Admin' || $role == 'Supervisor') {
            $form_list = Report::whereCreatorId($user->id)->get();
            $assigned_report_forms_ids = AssignedReportForm::whereAssignedId($user->id)->get()->pluck('report_id')->toArray();
            $assigned_report_forms = Report::find($assigned_report_forms_ids);
            \Log::info($form_list);
        }else if($role == 'Super Admin'){
            $form_list = \App\Http\Controllers\SuperAdmin\ReportController::getReportForm();
        }else{

            abort(401);
        }
        return response()->json([
            'form_list' => $form_list,
            'assigned_report_forms_list' => $assigned_report_forms ? $assigned_report_forms : []
        ]);
    }
}
