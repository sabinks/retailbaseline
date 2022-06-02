<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffHireController extends Controller
{
    public function hire(User $user){
        $detail = DB::table('associate_user')->where([
            ['user_id', '=', Auth::user()->id],
            ['staff_id', '=', $user->id],
        ])->first();
        
        if(empty($detail)){
            Auth::user()->fieldstaffs()->attach($user->id);
            return back()->with('message','Staff Hire request is made successfully!');
        }
        // return $detail->staff_status;
        if($detail->staff_status == 0){
            return back()->with('alert','You have already request this staff !!');
        }
        else if ($detail->staff_status == 1 ){
            return back()->with('primary','You have already hired this staff!');
        }
        else if ($detail->staff_status == 3 ){
            return back()->with('primary','Admin has assigned this staff to you already!');
        }
        else{
            return back()->with('danger','You can not hire this staff!');
        }
    }

    public function grant($user, $staff){
        $update = DB::table('associate_user')->where([
            ['user_id', '=', $user],
            ['staff_id', '=', $staff],
        ])->update(['staff_status' => 1]);

        if($update){
            return back()->with('message','Staff request is granted succesfully');

        }
        else{
            return back()->with('error','Opps there is problem');
        }
    }
    public function reject($user, $staff){
        $update = DB::table('associate_user')->where([
            ['user_id', '=', $user],
            ['staff_id', '=', $staff],
        ])->update(['staff_status' => 2]);

        if($update){
            return back()->with('success','Staff request is rejected succesfully');
        }
        else{
            return back()->with('error','Staff request is granted succesfully');
        }
    }

    public function viewassign(Request $request, User $user){
        $admins = User::role('Admin')->get();
        // return $user->creators;
        $total_admin=[];
        foreach($admins as $admin){
            $record = DB::table('associate_user')->where([
                ['user_id', '=', $admin->id],
                ['staff_id', '=', $user->id],
            ])->first();
            if($admin!=$user->creators){
                if($record==NULL){
                    $total_admin[]=$admin;
                }
            }
        }
        return view('components.assign.assignAdminToStaff',[
            'user'=>$user,
            'admins'=>$total_admin
        ]);
    }

    public function storeassign(Request $request){
        $staff = User::find(request('staff'));
        request()->validate([
            'admins'=>'required|array|min:1'
        ]);
        $staff->bosses()->attach(request('admins'),['staff_status' =>'3']);
        return redirect('/staffs')->with('message','Admin assigned successfully');
    }
}