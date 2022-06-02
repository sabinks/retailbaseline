<?php

namespace App\Http\Controllers\API\V1;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StaffAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends Controller
{
    public function store(Request $request){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        if($role != 'Field Staff'){
            abort(410);
        }
        
        $data = $request->only(['attendance_type', 'attendance_detail', 'from_date', 'to_date', 'remark', 'login_time', 'lat', 'lng', ]);
        
        DB::beginTransaction();
        try{
            if($request->hasFile('staff_image')){
                $image = $request->file('staff_image');
                $destination_path =  '/public/images/staff_attendances';
                $image_name = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                $result = $image->storeAs($destination_path, $image_name);
                $data['staff_image'] = $image_name;
            }
            $data['user_id'] = $user->id;
            $data['region_id'] = $user->regionsList()->first()->id;
            $result = StaffAttendance::create($data);
            DB::commit();

            return response()->json([
                'message' => 'Staff attendance stored successfully.',
            ], 201);
        }catch (Throwable $th) {
            DB::rollback();

            return response()->json(['message'=>$th->getMessage()], 400);
        }
    }
}
