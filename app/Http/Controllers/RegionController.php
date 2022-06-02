<?php

namespace App\Http\Controllers;

use App\Region;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class RegionController extends Controller
{
    function __construct()
    {
        $this->middleware('role:Super Admin|Admin',['only' => ['index','showAssign']]);
        $this->middleware('role:Super Admin', ['only' => ['create','store','show','edit','update','destroy']]);
        $this->middleware('role:Admin', ['only' => ['storeAssign','myRegion','selectRegion','removeRegion','createMyRegion','removeGroupAndRegion']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('Super Admin')){
            $total_regions = Region::all();
        }
        else{
            $regions = Region::all();
            $total_regions=[];
            foreach($regions as $region){
                $data = DB::table('region_user')->where([
                    ['user_id',Auth::user()->id],
                    ['region_id',$region->id]
                ])->first();
                if($data==NULL){
                    $total_regions[]=$region;
                }
            }
        }
        return view('components.region.listRegion',[
            'regions'=>$total_regions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.region.addRegion');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Region $region, Request $request)
    {
        Region::create(request()->validate([
            'name'=>'required'
        ]));

        return redirect('/regions')->with('message','Region '.request('name').' is created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        return "Data will be added soon";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        return view('components.region.updateRegion',[
            'region'=>$region
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $region->update(request()->validate([
            'name'=>'required'
        ]));

        return redirect('/regions')->with('message','Region'.request('name').' is updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        if($region->delete()){
            return redirect('/regions')->with('notice','Region '.$region->name.' is Deleted');
        }
    }

    public function removeGroupAndRegion($group){
       //code to delete group and regions in that group
    }
    // public function showAssign(Region $region){
    //     if(Auth::user()->hasRole('Super Admin')){
    //         $clients = User::role('Admin')->get();
    //     }
    //     else{
    //         $clients = Auth::user()->users()->role('Regional Admin')->get();
    //     }
    //     $total_clients=[];
    //     foreach($clients as $client){
    //         $admin = DB::table('region_user')->where([
    //             ['user_id',$client->id],
    //             ['region_id',$region->id]
    //         ])->first();
    //         if($admin==NULL){
    //             $total_clients[]=$client;
    //         }
    //     }
    //     return view('components.assign.assignAdminToRegion',[
    //         'admins'=>$total_clients,
    //         'region'=>$region
    //     ]);
    // }
    // public function storeAssign(Region $region,Request $request){
    //     request()->validate([
    //         'admins'=>'required|array|min:1'
    //     ]);
    //     $region->users()->attach(request('admins'));
    //     return redirect()->route('regions.index')->with('message','Admin assigned successfully !!');
    // }

    //show all the regions of login admin
    public function myRegion(){
        $regions = Auth::user()->regions;
        $data=[];
        $data1=[];
        foreach($regions as $region){
            if($region->pivot->region_name!=NULL){
                array_push($data,$region->pivot->region_name);
                }
            else if ($region->pivot->region_name==NULL){
                array_push($data1,$region->name);
                array_push($data1,$region->id);
            }
        }
        $single_regions = array_chunk($data1,2);
        $region_group = array_unique($data);

        return view('components.region.myRegion',[
            'regions'=>$regions,
            'region_group'=>$region_group,
            'single_regions'=>$single_regions
        ]);
        
    }

    public function regionslist(){
        $regions = Region::get();
    
        return view('components.region.regions_list',[
            'regions'=>$regions,
        ]);
        
    }
    //
    public function selectRegion(Request $request,Region $region){
        Auth::user()->regions()->attach($region);
        return back()->with('message',$region->name.' is Selected Successfully !!');
    }

    public function removeRegion(Request $request, Region $region){
        if(Auth::user()->regions()->detach($region)){
            return back()->with('notice',$region->name.' is remove Successfully !!');
        }
        else{
            return back()->with('error',$region->name.' does not belongs to you !!');
        }
    }

    public function createMyRegion(){
        $regions = Region::all();
        $total_regions=[];
        foreach($regions as $region){
            $data = DB::table('region_user')->where([
                ['user_id',Auth::user()->id],
                ['region_id',$region->id]
            ])->first();
            if($data==NULL){
                $total_regions[]=$region;
            }
        }
        return view('components.region.formMyregionCreate',[
            'regions'=>$total_regions
        ]);
        // $total_unset_regioal_admin=[];
        // $my_regional_admins = Auth::user()->users()->role('Regional Admin')->get();
        // foreach($my_regional_admins as $regional_admin){
        //     if($regional_admin->regions==NULL){
        //         $total_unset_regioal_admin[] = $regional_admin;
        //     }
        // }
        // return $total_unset_regioal_admin;
    }

    public function storeMyRegion(Request $request){
        $inputData = request()->validate([
            'regions'=>'required|array|min:2',
            'region_name'=>'required'
        ]);
        Auth::user()->regions()->attach($inputData['regions'],['region_name'=>$inputData['region_name']]);
        return redirect('/myRegion')->with('message','Region '.$inputData['region_name'].' is created successfully' );
    }

    public function editGroupRegion($name)
    {
        $user = Auth::user();
        $id = Auth::user()->id;
        $count = count(DB::table('region_user')->whereRegionName($name)->whereUserId($id)->get());
        if($count>=1){
            //get all the regions in group
            $group_regions = DB::table('region_user')->whereRegionName($name)->whereUserId($id)->pluck('region_id');
            $old_regions = Region::whereIn('id',$group_regions)->get();
            //get all admin regions including those regions in group
            $my_region = $user->regions()->pluck('region_id');
            //all region except admin's
            $all_regions = Region::whereNotIn('id',$my_region)->get(); 
            return view('components.region.updateGroupRegion',[
                'group'=>$name,
                'old_regions'=>$old_regions,
                'all_regions'=>$all_regions,
                'user'=>$user
            ]);
        }
        else{
            return redirect('/myRegion')->with('info','The Region Group '.$name. ' does not belongs to you');
        }
    }

    public function updateGroupRegion(Request $request, $name){
        $inputRegion = request()->validate([
            'regions'=>'required|array|min:2'
        ]);
        $Admin = Auth::user();
        $id = $Admin->id;
        $company = $Admin->companies()->first();
        //find the old regions in the group
        $group_regions = DB::table('region_user')->whereRegionName($name)->whereUserId($id)->pluck('region_id');
        //find out the different regions between old and new group
        $removed_regions = $group_regions->diff($inputRegion['regions']);
        //check either the removed region has supervisor and field staff , if yes then not allow to remove
        // if no users then allow to remove
        $error ='';
        //fetch all the supervisors and field staffs of the company
        $employees = $company->users()->role(['Supervisor','Field Staff'])->pluck('user_id');
        //fetch all the staff assigned to admin by super admin
        $assigned_staffs = $Admin->fieldstaffs()->whereStaffStatus(3)->pluck('staff_id');
        //merged both type of employees
        $employees = $employees->merge($assigned_staffs);
        foreach($removed_regions as $region){
            $delete_region = Region::findOrFail($region);
            $data = count(DB::table('region_user')->whereRegionId($region)->whereIn('user_id',$employees)->get());
            if($data>0){
                $error = $error.$delete_region->name.", ";
            }
        }
        if($error==''){
            $old_regions = Region::whereIn('id',$group_regions)->get();
            //Assign to Admin
            Auth::user()->regions()->detach($old_regions);
            Auth::user()->regions()->attach($inputRegion['regions'],['region_name'=>$name]);

            //check either the group has regional admin or not 
            $my_regional_admins = $Admin->users()->role('Regional Admin')->pluck('staff_id');
            //find all the users that belongs to selected regions group
            $all_users = DB::table('region_user')
                            ->whereRegionName($name)
                            ->where('user_id','!=',$id)
                            ->pluck('user_id')
                            ->unique()
                            ->values();
            //find the regional admin of the selected group 
            $regional_admin = $my_regional_admins->intersect($all_users);
            if(count($regional_admin)>0){
                $regional_admin = User::whereId($regional_admin)->first();
                //Assign to Regional Admin
                $regional_admin->regions()->detach($old_regions);
                $regional_admin->regions()->attach($inputRegion['regions'],['region_name'=>$name]);
                return redirect('/myRegion')->with('success','The Region Group '.$name. ' edited successfully');;
            }
            else{
                return redirect('/myRegion')->with('success','The Region Group '.$name. ' edited successfully');;
            }
        }
        else{
            return redirect()->back()->with('info','The region(s), '.$error.' are in used' );
        }
    }
}