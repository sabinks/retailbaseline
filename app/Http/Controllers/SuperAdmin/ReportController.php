<?php

namespace App\Http\Controllers\SuperAdmin;

use Throwable;
use DataTables;
use App\Company;
use App\Models\Report;
use App\Models\ReportData;
use Illuminate\Http\Request;
use App\Models\AssignedReportForm;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\Company\CompanyReport;
use App\Http\Resources\ReportForm\ReportForm;

class ReportController extends Controller{
    
    public function index(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $report_list = Report::whereCreatorId($user->id)->whereClientId($company->id)->get();
        $data = ReportForm::collection($report_list);

        return Datatables::of($data)
            ->rawColumns(['options'])
            ->make(true);
    }

    public function create()
    {
        //
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

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Report $report_form){
        $user = Auth::user();
        $company  = $user->companies()->first();
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

    public function destroy(Report $report_form){
        $user = Auth::user();
        $company  = $user->companies()->first();
        $form_assigned_client_count = $report_form->companies->count();
        if($form_assigned_client_count){

            return response()->json([
                'message' => 'Cannot delete, form assigned to client!',
            ], 401);
        }
        $assigned_report_form_count = AssignedReportForm::whereReportId($report_form->id)->count();
        if($assigned_report_form_count){

            return response()->json([
                'message' => 'Cannot delete, unauthorized user!',
            ], 401);
        }
        $reportdata_count = ReportData::whereReportId($report_form->id)->count();
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

    public function clientReportViewAccess(){
        $user = Auth::user();
        $company = $user->companies()->first();
        $all_report = Report::whereClientId($company->id)->get(['id', 'title as label', 'title as value']);
        $company_list = Company::where('company_name', '!=', 'Lemon')->with('reportForm')->get();
        $clients = CompanyReport::collection($company_list);
        
        return response()->json([
            'reports' =>  $all_report,
            'assigned_client' => $clients
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

    public function clientReportAssign($client_id, Request $request){
        $company = Company::findOrFail($client_id);
        $result = $company->reportForm()->sync(json_decode($request->report_ids));
        return $result;
    }
    public static function getReportForm(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies()->first();
        $report_form = Report::whereCreatorId($user->id)->whereClientId($company->id)->get(['id', 'title'])->toArray();

        return $report_form;
    }
}
