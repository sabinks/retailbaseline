<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Carbon\Carbon;
use App\StaffLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StaffLocationController extends Controller
{
    public function store(Request $request){
        $user = Auth::user();
        $company = $user->companies()->first();
        $role= $user->getRoleNames()->first();
        if($role != 'Field Staff'){
            abort(410);
        }

        $data = $request->only(['lat', 'lng']);

        DB::beginTransaction();
        try{
            if($request->hasFile('staff_image')){
                $image = $request->file('staff_image');
                $destination_path =  '/public/images/staff_location';
                $image_name = sha1(microtime() . rand(1,1000)) .".". $image->getClientOriginalExtension();
                $result = $image->storeAs($destination_path, $image_name);
                $data['staff_image'] = $image_name;
            }
            $data['staff_id'] = $user->id;
            $data['company_id'] = $company->id;
            $result = StaffLocation::create($data);
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
