<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use App\Models\AssignedReportForm;
use App\Models\Report;
use App\Models\ReportData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
class AssignedReportFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        if($role == 'Admin'){
            $submit_to = DB::table('roles')->whereIn('name',['Regional Admin','Supervisor'])->get();
        }
        else if($role == 'Regional Admin'){
            $submit_to = DB::table('roles')->whereIn('name',['Admin','Supervisor'])->get();
        }
        $data2=[];
        $reports = Report::whereCreatorId($user->id)->get();
        $assignedReports = AssignedReportForm::whereAssignedId($user->id)->get();
        foreach($assignedReports as $report){
            $data = Report::whereId($report->report_id)->first();
            if($data){
                $data2[]=$data;
            }
        }
        $totalReports = $reports->merge($data2);
        return response()->json([
            'reports' => $totalReports,
            'role' =>$role,
            'submit_to'=>$submit_to
        ]);
    }


    public function assignForm(Request $request)
    {
        $assigner = Auth::user();
        $company = $assigner->companies()->first();
        $input['assigner_id'] = $assigner->id;
        $input['assigned_id'] = request('assigned_id');
        $input['company_id'] = $company->id;
        $input['report_id'] = request('report_id');
        DB::beginTransaction();
        try{
            // check if same report is assigned to same person 
            $count1 = AssignedReportForm::whereAssignerId($input['assigner_id'])
                    ->whereAssignedId($input['assigned_id'])
                    ->whereReportId($input['report_id'])->first();

            //check is the report is assigned to him by target person (like: admin assign form to regional admin and regional admin
            // try to assign form to admin)
            $count2 = AssignedReportForm::whereAssignerId($input['assigned_id'])
            ->whereAssignedId($input['assigner_id'])
            ->whereReportId($input['report_id'])->first();

            if(!$count2){
                if(!$count1){
                    $result = AssignedReportForm::insert($input);
                    DB::commit();
                    if($result){
                        return response()->json([
                            'message' => 'Report Assign',
                        ], 200);
                    }
                }
                else{
                    return response()->json([
                        'message' => 'Can not re-assign to the same person',
                    ], 400);
                }
            }
            else{
                return response()->json([
                    'message' => 'Can not re-assign form to creator',
                ], 400);
            }
        }catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message'=>$th->getMessage()],400);
        }
        // return response()->json([
        //     'message' => 'Duplicate entry exists, please recheck!',
        // ], 400);
    }

    public function assignToNames($roleid){
        $login_user = Auth::user();
        $company = $login_user->companies()->first();
        $login_user_role = $login_user->getRoleNames()->first();
        if($login_user_role == 'Admin'){
            $role = DB::table('roles')->whereId($roleid)->first();
            $users = $company->users()->role($role->name)->get();
            return response()->json([
                'names'=>$users
            ]);
        }
        else if($login_user_role == 'Regional Admin'){
            $role = DB::table('roles')->whereId($roleid)->first();
            if($role->name == 'Admin'){
                $users = $company->users()->role($role->name)->get();
                return response()->json([
                    'names'=>$users
                ]);
            }
            else if ($role->name == 'Supervisor'){
                $login_user_regions = $login_user->regions;
                $users = $company->users()->role($role->name)->get();
                $submit_to =[];
                foreach($login_user_regions as $region){
                    foreach($users as $user){
                        if($region->id == $user->regions()->first()->id){
                            $submit_to[]=$user;
                        }
                    }
                }
                return response()->json([
                    'names'=>$submit_to
                ]);
            }
        }
    }

    //Following method return regular form assigned to the login user
    public function listAssignedReportForm(){
        $login_user = Auth::user();
        $assigned_report_forms = AssignedReportForm::whereAssignedId($login_user->id)->get();
        $data = [];
        foreach($assigned_report_forms as $reportForm){
            $report = Report::whereId($reportForm->report_id)->first();
            array_push($data,$reportForm->report_id);
            array_push($data,$report->title);
            $assigner = User::whereId($reportForm->assigner_id)->first();
            array_push($data,$assigner->name);
        }
        $data = array_chunk($data,3);
        $collection = collect(['id','title','assigner']);
        $combined = [];
        foreach($data as $datum){
            $combined[] = $collection->combine($datum);
        }
        return response()->json([
            'forms'=>$combined
        ]);
    }

    //Following method return regular report assigned by the login user
    public function AssignedReportForm(){
        $login_user = Auth::user();
        $assigned_report_forms = AssignedReportForm::whereAssignerId($login_user->id)->get();//get reports based on login user who act as assigner
        $data = [];
        foreach($assigned_report_forms as $reportForm){
            $report = Report::whereId($reportForm->report_id)->first();
            array_push($data,$reportForm->report_id);
            array_push($data,$report->title);
            $assigned = User::whereId($reportForm->assigned_id)->first();
            array_push($data,$assigned->name);
            array_push($data,$assigned->id);
        }
        $data = array_chunk($data,4);
        $collection = collect(['title_id','title','assigned','assigned_id']);
        $combined = [];
        foreach($data as $datum){
            $combined[] = $collection->combine($datum);
        }
        return response()->json([
            'forms'=>$combined
        ]);
    }

    //following method will remove regular report form from assigned user
    public function RemoveRegularReportForm($reportId,$assignedId){
        $report = AssignedReportForm::whereAssignedId($assignedId)->whereReportId($reportId)->first();
        if($report){
            $count = ReportData::whereReportId($report->id)->count();
            if($count){
                return response()->json([
                    'message' => 'Report form is used, cannot be removed!',
                ], 401);
            }
            else{
                $report->delete();
                return response()->json([
                    'message' => 'Report Form Removed!'
                ], 200);
            }
        }
        return response()->json([
            'message' =>"Please Select Appropriate data"
        ], 400);
    }
}
