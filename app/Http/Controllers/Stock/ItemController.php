<?php

namespace App\Http\Controllers\Stock;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Stock\Item;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index(){
        $user = Auth::user();
        $company = $user->companies->first();
        $items = Item::whereCompanyId($company->id)->with('categories')->get();

        return response()->json([
            'item_list' => $items
        ], 200);
    }
    public function getItem($categoryId){
        $user = Auth::user();
        $company = $user->companies->first();
        $items = Item::whereCompanyId($company->id)->whereCategoryId($categoryId)->get();

        return response()->json([
            'item_list' => $items
        ], 200);
    }
    public function store(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'max:255',
            'category_id' => 'required',
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        $user = Auth::user();
        $company = $user->companies->first();
        $data = $request->only(['name', 'description', 'category_id']);
        $data['company_id'] = $company->id;
        Item::create($data);
        
        return response()->json([
            'message' => 'Item Created!'
        ], 200);
    }

    public function update(Request $request, $id){
        $data = $request->only(['name', 'description', 'category_id']);
        $item = Item::findOrFail($id);
        if($item){
            $item->name = $data['name'] ? $data['name'] : $item->name;
            $item->description = $data['description'] ? $data['description'] : $item->description;
            $item->category_id = $data['category_id'] ? $data['category_id'] : $item->category_id;
            $item->update();
    
            return response()->json([
                'message' => 'Item Updated!'
            ], 200);
        }

    }

    public function destroy($id){
        $item = Item::findOrFail($id);
        // if($item_count){

        //     return response()->json([
        //         'message' => 'Category Used, Cannot Be Deleted!'
        //     ], 409);
        // }
       $item->delete();

        return response()->json([
            'message' => 'Item Deleted!'
        ], 200);
    }
}
