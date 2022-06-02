<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Stock\StockInward;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Stock\SubscriptionForm;

class InwardStockController extends Controller
{
    public function getSNDetail($unique_id){
        $user = Auth::user();
        $stock = StockInward::whereUniqueId($unique_id)->first();
        if($stock == null){

            return response()->json([
                'message' => 'Item not registered!'
            ], 404); 
        }
        $outwardStock = SubscriptionForm::whereUniqueId($unique_id)->first();
      
        return response()->json([
            'category' => $stock ? $stock->category()->first(['id', 'name']) : 'No Data',
            'item' => $stock ? $stock->item()->first(['id', 'name']) : 'No Data',
            'message' => $outwardStock ? 'Item with Unique ID already registered' : 'Item available'
        ], 200);
    }
}
