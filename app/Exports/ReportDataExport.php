<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Report;
use App\Models\ReportData;
use Illuminate\Support\Collection;
use App\Http\Resources\ReportDataList;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Resources\ReportData\ReportDataResource;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportDataExport implements FromCollection, ShouldAutoSize, WithEvents
{
    public $all_data, $id;

    public function __construct($id, $all_data){
        $this->all_data = $all_data;
        $this->id = $id;
        // \Log::info($all_data);
    }
    
    public function collection(){
        $all_reports_data = $this->all_data;
        $report_excel[0] = ['Entity Name', 'Latitude', 'Latitude', 'Region Name', 'Assigned Date', 'Filled Date','Filled By', 'Status'];
        $formInputLabel = [];
        $formInputs = [];
        $report_form = Report::whereId($this->id)->first();
        if(!$report_form){

            return response()->json([
            'message' => 'No Report Found.',
            ], 404);
        }
        $formInputsData = json_decode($report_form->data, true);
        foreach ($formInputsData as $key => $formInput) {
            if (!($formInput['element'] == 'Camera' || $formInput['element'] == 'Header')){
                array_push($formInputLabel, trim($formInput['label']) );
                array_push($formInputs, [ 'element' => trim($formInput['element']), 'field_name' => trim($formInput['field_name'])] );
            }
        }
        $report_excel[0] = array_merge($report_excel[0], $formInputLabel);      
        foreach ($all_reports_data as $report) {
            $formDatas = json_decode($report->data,true);
            $answer = [];
            foreach ($formInputs as $formInput) {
                foreach($formDatas as $key => $formData){
                    if($formInput['field_name'] == $formData['name']){
                        if($formInput['element'] == 'Tags' || $formInput['element'] == 'Checkboxes' || 
                            $formInput['element'] == 'RadioButtons' ){
                            $datas = $formData['value'];
                            $value = [];
                            foreach($datas as $index => $data){
                                array_push($value, $data['text']);
                            }
                            array_push($answer, implode(', ',$value));
                        }
                        if($formInput['element'] == 'NumberInput' || $formInput['element'] == 'TextArea' || 
                            $formInput['element'] == 'TextInput' || $formInput['element'] == 'Dropdown'){
                            array_push($answer,  $formData['value'][0]);
                        }if($formInput['element'] == 'DatePicker'){
                            array_push($answer,  Carbon::parse($formData['value'][0])->toDateString());
                        }
                        unset($formDatas[$key]);
                    }
                }
            }
            $data = [
                $report->entities->name , $report->entities->latitude,
                $report->entities->longitude, $report->region->name,
                $report->assigned_date ? Carbon::createFromFormat('Y-m-d', $report->assigned_date, 'UTC')
                ->timezone('Asia/Kathmandu')->format('Y-m-d') : '-',
                $report->filled_date ? Carbon::createFromFormat('Y-m-d', $report->filled_date, 'UTC')
                    ->timezone('Asia/Kathmandu')->format('Y-m-d'): '-' ,
                $report->staffDetail->name, 
                $this->statusConversion($report->status)
            ];
            $report_excel[] = array_merge($data, $answer);
        }
        return new Collection([
            $report_excel
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13);
            },
        ];
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
