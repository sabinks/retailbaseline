<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use DB;
use App\User;
use App\Company;
use App\EntitiesForm;
use App\Models\Report;
use App\EntitiesFormData;
use App\Models\ReportData;
use Illuminate\Http\Request;
use App\Exports\EntityDataExport;
use App\Exports\ReportDataExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientEntityDataExport;
use App\Exports\ClientReportDataExport;
use Illuminate\Support\Facades\Validator;

class GenerateReportController extends Controller
{
    public function reportGenerate(Request $request, $id){
        
        $validator = Validator::make($request->all(),[
            'from_date' =>"required",
            'to_date' =>"required",
            'file_type' =>"required",
            'company_id'=>"required",
            'type_id'=>"required",
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $data = $request->only(['from_date', 'to_date', 'file_type','company_id','type_id']);
        $company = Company::whereId($data['company_id'])->first();

        //for entity tracking form's data 
        if($data['type_id']==1){
            $all_data = EntitiesFormData::whereEntitiesFormId($id)
                ->whereBetween('created_at', [$data['from_date'], $data['to_date']])
                ->with(['formFiller:id,name,email','entitiesForm:id,form_title','entities:id,name,latitude,longitude'])
                ->approved()
                ->get();
            if(!count($all_data)){
                return response()->json([
                    'message' => 'No Report Data Found.',
                ], 404);
            }
            if($data['file_type'] == "csv")
                return Excel::download( new EntityDataExport($id, $all_data), "report_data_export.csv"); 
            else
                return Excel::download( new EntityDataExport($id, $all_data), "report_data_export.xlsx");
        }

        //for report form's data
        else if($data['type_id']==2){
            $all_data = ReportData::whereReportId($id)
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
    }
    public function entityReportGenerate(Request $request){
        $validator = Validator::make($request->all(), [
            'file_type' =>"required",
            'form_id' => "required",
            'from_date' => "required",
            'to_date' => "required",
            'select_status' => "required"
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $data = $request->only(['file_type', 'form_id', 'from_date', 'to_date', 'select_status']);
        $all_data = EntitiesFormData::whereEntitiesFormId($data['form_id'])
            ->whereBetween('created_at', [$data['from_date'], $data['to_date']])
            ->whereIn('status', $data['select_status'])
            ->with(['entitiesForm:id,form_title,inputs'])
            ->get();

        if(!count($all_data)){
            return response()->json([
                'message' => 'No Entity Data Found.',
            ], 404);
        }
        if($data['file_type'] == "csv")
            return Excel::download( new EntityDataExport($data['form_id'], $all_data), "entity_data_export.csv"); 
        else
            return Excel::download( new EntityDataExport($data['form_id'], $all_data), "entity_data_export.xlsx");
    }

    public function reportReportGenerate(Request $request){
        $validator = Validator::make($request->all(), [
            'file_type' =>"required",
            'form_id' => "required",
            'from_date' => "required",
            'to_date' => "required",
            'select_status' => "required"
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $data = $request->only(['file_type', 'form_id', 'from_date', 'to_date', 'select_status']);
        $all_data = ReportData::whereReportId($data['form_id'])
            ->whereBetween('assigned_date', [$data['from_date'], $data['to_date']])
            ->whereIn('status', $data['select_status'])
            ->with(['report:id,title,data'])
            ->get();
        if(!count($all_data)){
            return response()->json([
                'message' => 'No Report Data Found.',
            ], 404);
        }
        if($data['file_type'] == "csv")
            return Excel::download( new ReportDataExport($data['form_id'], $all_data), "report_data_export.csv"); 
        else
            return Excel::download( new ReportDataExport($data['form_id'], $all_data), "report_data_export.xlsx");
    }
}
