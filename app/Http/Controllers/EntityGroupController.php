<?php

namespace App\Http\Controllers;
use DB;
use App\User;
use App\Region;
use App\Entitygroup;
use App\EntitiesFormData;
use Illuminate\Http\Request;
use Illuminate\Support\Collection; 
use Illuminate\Support\Facades\Auth;

class EntityGroupController extends Controller
{

    public function index(){
        $user = Auth::user();
        $entities = Entitygroup::whereCreatorId($user->id)->get();
        $data=[];
        $entity_name = new Collection();
        foreach($entities as $entity){
            $entity_name = new Collection();
            array_push($data, $entity->group_name);
            $entity_ids = json_decode($entity->entity_ids);
            foreach($entity_ids as $id){
                $pointer = EntitiesFormData::whereId($id)->first();
                if($pointer){
                    $entity_name = $entity_name->merge($pointer->name);
                }
            }
            array_push($data, $entity_name);
            array_push($data, $entity->id);
        }
        $entities = array_chunk($data,3);
        return view('components.entityGroup.index',[
            'entities'=>$entities
        ]);
    }


    public function create(Entitygroup $entity)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        $role= $user->getRoleNames()->first();
        if($role == 'Super Admin' || $role == 'Admin' || $role == 'Regional Admin'){
            //  fetch all other existing entities group of login admin
            $entities = new Collection(Entitygroup::where('creator_id', $user->id)->get());
            $total_used_entity = new Collection();
            foreach($entities as $entity){
                $total_used_entity = $total_used_entity->merge(json_decode($entity->entity_ids));
            }

            //fetch all the accepted entities of login super admin or regional admin
            $total_entities = new Collection();
            $region_list=[];
            $my_entities=[];
            if($role == 'Super Admin'){
                $region_list = Region::get()->flatten()->pluck('id')->toArray();
                $my_entities = new Collection(EntitiesFormData::approved()->get());
             }else{
                $region_list = $user->regionsList()->get()->flatten()->pluck('id')->toArray();
                $my_entities = new Collection( $company->entitiesFormData->where('status',2)->whereIn('region_id', $region_list));
             }
            // $my_entities = new Collection( EntitiesFormData::where('status', 2)
            //                         ->whereIn('region_id', $region_list)->whereUserId($company->id)->get());
           
           
            foreach($my_entities as $entity){
                $total_entities = $total_entities->merge($entity->id);
            }

            //find the unused entity 
            $total_unused_entity  = $total_entities->diff($total_used_entity);
            $entitie_collect = new Collection();
            $total_unused_entity = $total_unused_entity->values();
            foreach($total_unused_entity as $location){
                $entitie_collect = $entitie_collect->merge(EntitiesFormData::where('id',$location)->get());
            }
            $region=[];
            foreach($entitie_collect as $entity){
                $region[] = Region::find($entity->region_id);
            }

        }
        else {
            abort(403);
        }
        return view('components.entityGroup.create',[
            'entities'=>$entitie_collect,
            'regions'=>array_unique($region)
        ]);
    }

    public function store(Request $request)
    {
        $inputData = $this->validateInput();
        $data = [];
        foreach($inputData['entities'] as $value){
            array_push($data, (int)$value);
        }
        $data2=[];
        $my_entities = EntitiesFormData::where('status', 2)->whereIn('id', $data)->get();
        foreach($my_entities as $entity){
            array_push($data2,$entity->region_id);
        }
        if(count(array_unique($data2))==1){
            $entity = new Entitygroup;
            $entity->group_name	= $inputData['group_name'];
            $entity->creator_id = Auth::id();
            $entity->entity_ids = json_encode($data);
            if($entity->save()){
                return redirect('group-entites')->with('message','New group created!');
            }
            else{
                return back()->with('error','Group creation failed!');
            }
        }
        else{
            return back()->with('error','Please choose entity of same region to group')->withInput();
        }
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        //fetch the already selected entities names that the user want to update
        $user = Auth::user();
        $company = $user->companies()->first();
        $entity_group = Entitygroup::findOrFail($id);
        $entity_list = json_decode($entity_group->entity_ids); 
        $entity_name =EntitiesFormData::whereIn('id',$entity_list)->get();
        //fetch all entity_groups
        $entities = Entitygroup::whereCreatorId($user->id)->pluck('entity_ids');
        $total_used_entity=new Collection();
        //find all entities from all entity_groups
        foreach($entities as $entity){
            $total_used_entity = $total_used_entity->merge(json_decode($entity));
        }
        //fetch only accepted entity
        $total_entities = new Collection();
        if($user->hasRole('Super Admin')){
            $region_list = Region::pluck('id');
        }
        else{
            $region_list = $user->regionsList()->get()->flatten()->pluck('id');
        }
        $total_entities = $company->entitiesFormData->where('status',2)->whereIn('region_id', $region_list)->pluck('id');
        //find differences between the already used entites and unused entities
        $total_unused_entity  = $total_entities->diff($total_used_entity);
        $entities_collect = EntitiesFormData::whereIn('id',$total_unused_entity->values())->get();
        $new_regions_ids = $entities_collect->pluck('region_id')->unique();
        $old_regions_ids = $entity_name->pluck('region_id')->unique();
        $total_regions_ids = $new_regions_ids->merge($old_regions_ids);
        $region = Region::whereIn('id',$total_regions_ids)->get();
        // Send the selected group info that user want to update and unused entity to blade
        return view('components.entityGroup.update',[
            'entity'=>$entity_group,
            'entity_name'=>$entity_name,
            'entitie_collect'=>$entities_collect,
            'regions'=>$region
        ]); 
    }

    public function update(Request $request, Entitygroup $group_entite)
    {
        $inputData = $this->validateInput();
        $data = [];
        foreach($inputData['entities'] as $value){
            array_push($data, (int)$value);
        }
        $data2=[];
        $my_entities = EntitiesFormData::where('status', 2)->whereIn('id', $data)->get();
        foreach($my_entities as $entity){
            array_push($data2,$entity->region_id);
        }
        if(count(array_unique($data2))==1){
            $inputData['entity_ids']= json_encode($data);
            if($group_entite->update($inputData)){
                return redirect('group-entites')->with('message','Group updated successfully!');
            }
            else{
                return back()->with('error','Please Select entity from list!');
            }
        }
        else{
            return back()->with('error','Please choose entity of same region to group')->withInput();
        }
    }

    public function destroy($id)
    {
        $group = Entitygroup::find($id);
        if($group){
            if($group->delete()){
                return redirect('group-entites')->with('message','The entities group '.$group->group_name.' is deleted successfully');
            }
        }
        else{
            return back()->with('error','Selected group not found!');
        }
    }

    protected function validateInput(){
        return request()->validate([
            'entities'=>'required|array|min:1',
            'group_name'=>'required'
        ]);
    }
}
