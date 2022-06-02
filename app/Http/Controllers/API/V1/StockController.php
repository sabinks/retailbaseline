<?php

namespace App\Http\Controllers\API\V1;

use App\Region;
use App\EntitiesFormData;
use App\Models\Stock\Item;
use Illuminate\Http\Request;
use App\Models\Stock\Category;
use App\Models\Stock\OutwardStock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function getCategoryList(){
        $user = Auth::user();
        $company = $user->companies->first();
        $categories = Category::whereCompanyId($company->id)->get(['id', 'name']);

        return response()->json([
            'category_list' => $categories
        ], 200);
    }

    public function getItemList($categoryId){
        $user = Auth::user();
        $company = $user->companies->first();
        $items = Item::whereCompanyId($company->id)
                    ->whereCategoryId($categoryId)
                    ->get(['id', 'name', 'image_name']);

        return response()->json([
            'item_list' => $items
        ], 200);
    }

    public function getRegionList(){
        $user = Auth::user();
        $company = $user->companies->first();
        $regions = Region::whereId(35)->get(['id', 'name']);

        return response()->json([
            'region_list' => $regions
        ], 200);
    }

    public function getEntityList($regionId){
        $user = Auth::user();
        $company = $user->companies->first();
        $entities = EntitiesFormData::whereRegionId($regionId)->approved()
                    ->get(['id', 'name']);

        return response()->json([
            'entity_list' => $entities
        ], 200);
    }

    public function getItemCurrentStock($itemId){
        $user = Auth::user();
        $company = $user->companies->first();

        $item = Item::whereId($itemId)->whereCompanyId($company->id)->first();
        $item_opening_stock = $item->sumOpeningStock()->first() != null ? $item->sumOpeningStock()->first()->quantity : 0;
        $item_inward_stock = $item->sumInwardStock()->first() != null ? $item->sumInwardStock()->first()->quantity : 0;
        $item_outward_stock = $item->sumOutwardStock()->first() != null ? $item->sumOutwardStock()->first()->quantity : 0;
        $current_stock = (int)$item_opening_stock + (int)$item_inward_stock - (int)$item_outward_stock;

        return response()->json([
            'current_stock' => $current_stock
        ], 200);
    }

    public function outwardStock(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
  
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'entity_id' => 'required',
            'owner' => 'required:255',
            'contact' => 'max:255',
            'quantity' => 'required',
            'entry_date' => 'required',
            'particular' => 'required',
        ]);
        if($validator->fails()){
            \Log::info($validator->errors());
            return response()->json([
                'message'=>'Input field missing!','errors'=>$validator->errors(), 'status' => 422
            ],422);
        }
        $data = $request->only(['item_id', 'entity_id', 'quantity', 'entry_date', 'particular', 'owner', 'contact']);
        $data['company_id'] = $company->id;
        $item_stock = OutwardStock::create($data);

        return response()->json([
            'message' => 'Outward Stock Registered',
            'status' => 201
        ], 201);
    }

    

}
