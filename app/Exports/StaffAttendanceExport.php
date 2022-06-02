<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Resources\User\StaffAttendanceReportResource;

class StaffAttendanceExport implements FromCollection, ShouldAutoSize
{
    public $staff_attendance;

    public function __construct($staff_attendance){
        $this->staff_attendance = $staff_attendance;
    }

    public function collection()
    {
        $report_excel[0] = ['Staff Name', 'Attendance Type', 'Attendance Detail', 'From Date', 'To Date', 'Region', 'Login Time', 'Saved Time', 'Remark'];

        foreach ($this->staff_attendance as $staff) {
           $report_excel[] = [
                $staff->staffDetail->name,
                $staff->attendance_type,
                $staff->attendance_detail ? $staff->attendance_detail : '-',
                $staff->from_date ? $staff->from_date : '-',
                $staff->to_date ? $staff->to_date : '-',
                $staff->staffRegion->name,
                Carbon::createFromFormat('Y-m-d H:i:s' , $staff->login_time)->format('Y-m-d h:mA'),
                Carbon::createFromFormat('Y-m-d H:i:s' , $staff->login_time)->format('Y-m-d h:mA'),
                $staff->remark
           ];
        }

        return new Collection([
            $report_excel
        ]);
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class    => function(AfterSheet $event) {
    //             $cellRange = 'A1:W1'; // All headers
    //             $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(13);
    //         },
    //     ];
    // }
}
