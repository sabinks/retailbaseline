<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StaffAttendance;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffAttendanceExport;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User\StaffAttendanceResource;

class StaffAttendanceController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->query(),[
            'from_date' => "required",
            'to_date' =>"required",
            'all' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        
        $staff_list = $this->staffList();
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');
        $all = $request->query('all');
        \Log::info([$from_date, $to_date, $all]);
        $staff_attendance = boolval($all) ? StaffAttendance::query()
                                ->whereIn('user_id', $staff_list) : 
                                StaffAttendance::query()
                                ->whereIn('user_id', $staff_list)
                                ->whereBetween('login_time', [$from_date, $to_date]);

        $staff_attendance = $staff_attendance->with(['staffDetail', 'staffRegion'])->latest()->get();
        $data = StaffAttendanceResource::collection($staff_attendance);

        return Datatables::of($data)
            ->rawColumns(['options'])
            ->make(true);
    }

    public function generateStaffAttendanceReport(Request $request){
        $validator = Validator::make($request->query(),[
            'from_date' => "required",
            'to_date' => "required",
            'all' =>"required",
        ]);
        $file_type = 'csv';
        if($validator->fails()){
            return response()->json(['message' => 'Validation Failed!','errors' => $validator->errors()],422);
        }
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');
        $all = $request->query('all');
        $staff_list = $this->staffList();

        $staff_attendance = boolval($all) ? StaffAttendance::query()
                                    ->whereIn('user_id', $staff_list) : 
                                    StaffAttendance::query()
                                    ->whereBetween('login_time', [$from_date, $to_date])
                                    ->whereIn('user_id', $staff_list);

        $staff_attendance = $staff_attendance->with(['staffDetail', 'staffRegion'])->latest()->get();
        if(!count($staff_attendance)){
            return response()->json([
                'message' => 'No Staff Attendance Found.',
            ], 404);
        }

        if($file_type == "csv")
            return Excel::download( new StaffAttendanceExport($staff_attendance), "staff_attendance_report.csv"); 
        else
            return Excel::download( new StaffAttendanceExport($staff_attendance), "staff_attendance_report.xlsx");
    }

    public function staffList(){
        $user = Auth::user();
        $company = $user->companies->first();
        $role= $user->getRoleNames()->first();
        if($role == 'Super Admin' || $role == 'Admin'){
            $staff_list = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();

            return $staff_list;
        }else if($role == 'Regional Admin' || $role == 'Supervisor'){
            $region_list = $user->regionsList()->get()->pluck('id')->toArray();
            $staff_list = $company->users()->role('Field Staff')
                ->whereHas('regions', function ($query) use($region_list){
                    $query->whereIn('region_id', $region_list);
                })->get()->pluck('id')->toArray();

            return $staff_list;
        }
        else{
            abort(410);
        }     
    }
    public function pieChartAttendance(){
        $user = Auth::user();
        $company = $user->companies->first();
        $role= $user->getRoleNames()->first();
        $staff_list = UserController::companyOnlyStaffList();
        if(!count($staff_list)){
            abort(410);
        }     
        $current_date = Carbon::now();
        $staff_attendance_count = count($staff_list);
        $present_staff = StaffAttendance::query()
                        ->whereIn('user_id',$staff_list )
                        ->whereAttendanceType('present')
                        ->whereDate('login_time', '>=', Carbon::parse($current_date)->startOfDay())
                        ->whereDate('login_time', '<=', Carbon::parse($current_date)->endOfDay())
                        ->count();
        $absent_staff = StaffAttendance::query()
                        ->whereIn('user_id',$staff_list )
                        ->whereAttendanceType('absent')
                        ->whereDate('login_time', '>=', Carbon::parse($current_date)->startOfDay())
                        ->whereDate('login_time', '<=', Carbon::parse($current_date)->endOfDay())
                        ->count();
        return response()->json([
            'id' => $staff_list,
            'no_attendance' => $staff_attendance_count - $present_staff - $absent_staff,
            'present_staff' => $present_staff,
            'absent_staff' => $absent_staff
         ], 200);
    }
}
