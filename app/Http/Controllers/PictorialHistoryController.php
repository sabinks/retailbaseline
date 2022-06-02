<?php

namespace App\Http\Controllers;

use App\User;
use App\Region;
use App\EntitiesFormData ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EntityData\EntityDataList;
use DataTables;

class PictorialHistoryController extends Controller
{
    public function index($status){
        $user = Auth::user();
        $role = Auth::user()->getRoleNames()->first();
        $company = $user->companies()->first();
        $company_id = $company->id;
        $entity = [];
        if($role == 'Super Admin'){
            $entity  = new EntitiesFormData;
        }else if($role == 'Admin'){
            $entity_ids = DB::table('client_entities_form_data')->whereClientId($company_id)->get()->pluck('id')->toArray();
            $entity = EntitiesFormData::whereIn('id', $entity_ids);
        }else if($role == "Regional Admin"){
            $entity_ids = DB::table('client_entities_form_data')->whereClientId($company_id)->get()->pluck('id')->toArray();
            $region_ids = $user->regions->pluck('id')->toArray();
            $entity = EntitiesFormData::whereIn('id', $entity_ids)
                        ->whereIn('region_id', $region_ids );
        }
        else{
            abort(401);
        }
        $entity_data =  $status == 'approved' ? $entity->approved()->with(['entitiesForm:id,form_title','region'])->get() : $entity->with(['entitiesForm:id,form_title','region'])->get();
        $data = EntityDataList::collection($entity_data);
        
        return Datatables::of($data)
            ->rawColumns(['options'])
            ->make(true);
    }
    
    public function show($id){
        $entity = EntitiesFormData::whereId($id)->with(['region:id,name', 'formFiller:id,name'])->first();
        return response()->json([
            'entity' => $entity
        ]);
    }
}
