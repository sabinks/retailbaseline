<?php

namespace App\Http\Controllers;

use App\StaffLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLocationController extends Controller
{
    public function getLocation($staff_id){
        $user = Auth::user();
        $staff_location = StaffLocation::whereStaffId($staff_id)->with('staffDetail')->first();
        return response()->json([
            'staff_location' => $staff_location
        ], 200);
    }    
}
