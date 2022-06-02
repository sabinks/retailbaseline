<?php

namespace App\Http\Controllers\SuperAdmin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AssignEntityController extends Controller
{
    public function allStaffList(Request $request){
        $user = Auth::user();
        $company = $user->companies()->first();
        $all_user = DB::table('company_user')->whereCompanyId($company->id)->get()->pluck('user_id')->toArray();
        $all_staff_list = User::whereIn('id', $all_user)
                        ->role('Field Staff')
                        ->get();
        
        return response()->json([
            'staff_list' => $all_staff_list
        ], 200);
    }
}
