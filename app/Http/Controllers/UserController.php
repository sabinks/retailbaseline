<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userDetail(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();

        return response()->json([
            'name' => $user->name,
            'role' => $role
         ], 200);
    }

    public static function companyOnlyStaffList(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies->first();
        $staff_list = [];
        if($role == 'Super Admin' || $role == 'Admin'){
            $staff_list = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
        }
        elseif($role == 'Regional Admin' || $role == 'Supervisor' ){
            $region_list = $user->regionsList()->get()->pluck('id')->toArray();
            $staff_list = $company->users()->role('Field Staff')
                ->whereHas('regions', function ($query) use($region_list){
                    $query->whereIn('region_id', $region_list);
                })->get()->pluck('id')->toArray();
        }
        else{
            $staff_list = [];
        }
        return $staff_list;
    }
}
