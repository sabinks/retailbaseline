<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Models\Stock\InwardStock;
use App\Models\Stock\OpeningStock;
use App\Models\Stock\OutwardStock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OpenStockController extends Controller
{
    public function index($categoryId = 0){
        $user = Auth::user();
        $company = $user->companies->first();
        $items = $categoryId ? OpeningStock::whereCompanyId($company->id)->with(['item', 'item.category'])->get() : 
                            OpeningStock::whereCompanyId($company->id)->with(['item', 'item.category'])->get();
                                            
        return response()->json([
            'item_list' => $items
        ], 200);
    }
    public function store(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'quantity' => 'required',
            'entry_date' => 'required',
            'particular' => 'required'
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        $data = $request->only(['item_id', 'quantity', 'entry_date', 'particular']);
        $data['company_id'] = $company->id;
        OpeningStock::create($data);
        
        return response()->json([
            'message' => 'Open Stock Created!'
        ], 200);
    }
    public function update(Request $request, $openingStockId){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'entry_date' => 'required',
            'quantity' => 'required|numeric',
            'particular' => 'required'
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        $data = $request->only(['quantity', 'particular', 'entry_date']);
        $openingStock = OpeningStock::findOrFail($openingStockId);
        $openingStock['quantity'] = $data['quantity'] ? $data['quantity'] : $openingStock->quantity;
        $openingStock['entry_date'] = $data['entry_date'] ? $data['entry_date'] : $openingStock->entry_date;
        $openingStock['particular'] = $data['particular'] ? $data['particular'] : $openingStock->particular;
        $openingStock->update();
        
        return response()->json([
            'message' => 'Opening Stock Updated!'
        ], 200);
    }

    public function destroy($openingStockId){
        $openingStock = InwardStock::findOrFail($openingStockId);
        $openingStock->delete();
        
        return response()->json([
            'message' => 'Opening Stock Deleted!'
        ], 200);
    }
}
