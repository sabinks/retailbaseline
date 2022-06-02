<?php

namespace App\Http\Controllers\API\V1;

use App\User;
use App\Models\Report;
use App\EntitiesFormData;
use App\Models\ReportData;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportDataController extends Controller
{
    public function getAssignedReport(){
        $user = Auth::user();
        $assignedReport = ReportData::whereStaffId($user->id)
                                ->assigned()
                                ->with('reportForm:id,data,title')
                                ->get();
        
        foreach ($assignedReport as $key => $report) {
            $assignedReport[$key]['status_name'] = $report->status;
            $assignedReport[$key]['entity_name'] = $report->reportEntityData()->first()->name;
            $assignedReport[$key]['entity_address'] = $report->reportEntityData()->first()->address;
            $assignedReport[$key]['entity_lat'] = $report->reportEntityData()->first()->latitude;
            $assignedReport[$key]['entity_long'] = $report->reportEntityData()->first()->longitude;
            $assignedReport[$key]['report_title'] = $report->reportForm->title;
            $assignedReport[$key]['report_data'] = json_decode( $report->reportForm->data);
            $assignedReport[$key]['report_answer'] = json_decode($report->data);
            $assignedReport[$key]['report_assigned_date'] = json_decode($report->assigned_date);
        }

        return response()->json([
            'data' => $assignedReport
        ], 200);
    }

    public function getRejectedReport(){
        $user = Auth::user();
        $assignedReport = ReportData::whereStaffId($user->id)
                                ->rejected()
                                ->with('reportForm:id,data,title')
                                ->get();
        
        foreach ($assignedReport as $key => $report) {
            $assignedReport[$key]['status_name'] = $report->status;
            $assignedReport[$key]['entity_name'] = $report->reportEntityData()->first()->name;
            $assignedReport[$key]['entity_address'] = $report->reportEntityData()->first()->address;
            $assignedReport[$key]['entity_lat'] = $report->reportEntityData()->first()->latitude;
            $assignedReport[$key]['entity_long'] = $report->reportEntityData()->first()->longitude;
            $assignedReport[$key]['report_title'] = $report->reportForm->title;
            $assignedReport[$key]['report_data'] = json_decode( $report->reportForm->data);
            $assignedReport[$key]['report_answer'] = json_decode($report->data);
            $assignedReport[$key]['report_images'] = $report->reportImages;
            $assignedReport[$key]['report_assigned_date'] = json_decode($report->assigned_date);
            $assignedReport[$key]['report_filled_date'] = json_decode($report->filled_date);
        }

        return response()->json([
            'data' => $assignedReport,
            'image_path' => 'storage/images/report_data_images'
        ], 200);
    }

    public function getReportDataList(){
        $user = Auth::user();
        $assignedReport = ReportData::whereStaffId($user->id)
                                ->whereIn('status', [2,3])
                                ->with('reportForm:id,data,title')
                                ->get();
        
        foreach ($assignedReport as $key => $report) {
            $assignedReport[$key]['status_name'] = $report->status;
            $assignedReport[$key]['status'] = $this->statusConversion($report->status);
            $assignedReport[$key]['entity_name'] = $report->reportEntityData()->first()->name;
            $assignedReport[$key]['entity_address'] = $report->reportEntityData()->first()->address;
            $assignedReport[$key]['entity_lat'] = $report->reportEntityData()->first()->latitude;
            $assignedReport[$key]['entity_long'] = $report->reportEntityData()->first()->longitude;
            $assignedReport[$key]['report_title'] = $report->reportForm->title;
            $assignedReport[$key]['report_data'] = json_decode( $report->reportForm->data);
            $assignedReport[$key]['report_answer'] = json_decode($report->data);
            $assignedReport[$key]['report_images'] = $report->reportImages;
            $assignedReport[$key]['report_assigned_date'] = json_decode($report->assigned_date);
            $assignedReport[$key]['report_filled_date'] = json_decode($report->filled_date);
        }

        return response()->json([
            'data' => $assignedReport,
            'image_path' => 'storage/images/report_data_images'
        ], 200);
    }

    public function store(Request $request){
        $user = Auth::user();
        $data = $request->only(['id', 'report_id', 'report_data_answer', 'filled_date', 'staff_id']);
        $report_data = ReportData::whereId($data['id'])->whereStaffId($data['staff_id'])
                    ->whereReportId($data['report_id'])
                    ->first();
        if(!$report_data){
            abort(404);
        }
        DB::beginTransaction();
        try{
            $report_data->data = $data['report_data_answer'];
            $report_data->status = 2;    //pending = must check report data to accept/reject by supervisor
            $report_data->filled_date = $data['filled_date'] ? $data['filled_date'] : Carbon::now('Asia/Kathmandu')->toDateString();
            
            $report_form = Report::whereId($report_data->report_id)->first();
            if(!$report_form){
                abort(404);
            }
            $formInputs = json_decode($report_form->data);
            foreach($formInputs as $key => $formInput){
                if ($formInput->element == 'Camera') {
                    if($request->hasFile($formInput->field_name)){
                        $image[$key] = $request->file($formInput->field_name);
                        $destination_path =  '/public/images/report_data_images';
                        $image_name[$key] = sha1(microtime() . rand(1,1000)) .".". $image[$key]->getClientOriginalExtension();
                        $result = $image[$key]->storeAs($destination_path, $image_name[$key]);
                        $reportdata_image[$key]['reportdata_id'] = $data['id'];
                        $reportdata_image[$key]['form_field_name'] = $formInput->field_name;
                        $reportdata_image[$key]['form_label'] = $formInput->label;
                        $reportdata_image[$key]['image_name'] = $image_name[$key];
                        ReportImage::create($reportdata_image[$key]);
                    }
                }
            }
            $result = $report_data->save();
            DB::commit();

            return response()->json([
                'message' => 'Report stored successfully.',
                'report' => $result
            ], 201);
        }catch (Throwable $th) {
            DB::rollback();

            return response()->json(['message'=>$th->getMessage()],400);
        }
    }

    public function update(Request $request){
        $user = Auth::user();
        $data = $request->only(['id', 'report_id', 'report_data_answer', 'filled_date']);
        $formData['report_data_answer'] = json_decode($data['report_data_answer'], true);
        $report_data = ReportData::whereId($data['id'])->whereReportId($data['report_id'])->first();
        if(!$report_data){
            abort(404);
        }
        DB::beginTransaction();
        try{
            $report_form = Report::whereId($report_data->report_id)->first();
            if(!$report_form){
                abort(404);
            }
            $form['data'] = json_decode($report_form['data'], true);
            foreach($form['data'] as $index =>  $formInput){
                if ($formInput['element'] == 'Camera') {
                    if($request->hasFile($formInput['field_name'])){
                        $image = $request->file($formInput['field_name']);
                        $destination_path = 'public/images/report_data_images';
                        $image_name = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                        $formData['report_data_answer'][$index]['value'] = [$image_name];
                        $reportdata_image = ReportImage::whereReportdataId($data['id'])
                                                ->whereFormFieldName($formInput['field_name'])->first();
                      
                        if($reportdata_image){
                            $image_path =  storage_path() ."/app/" . $destination_path . "/" . $reportdata_image->image_name;
                            unlink($image_path);
                        }
                        $result = $image->storeAs($destination_path, $image_name);
                        $reportdata_image->form_field_name = $formInput['field_name'];
                        $reportdata_image->form_label = $formInput['label'];
                        $reportdata_image->image_name = $image_name;
                        $reportdata_image->save();
                    }
                }
            }
            $report_data->data = json_encode($formData['report_data_answer']);
            $report_data->status = 2;    //pending = must check report data to accept/reject
            $report_data->filled_date = $data['filled_date'] ? $data['filled_date'] : Carbon::now('Asia/Kathmandu')->toDateString();
            $result = $report_data->save();
            DB::commit();

            return response()->json([
                'message' => 'Report updated successfully.',
                'report' => $result
            ], 200);
        }catch (Throwable $th) {
            DB::rollback();
            
            return response()->json(['message'=>$th->getMessage()],400);
        }       
    }
    public function statusConversion($status){
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
}
