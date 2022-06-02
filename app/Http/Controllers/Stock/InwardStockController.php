<?php

namespace App\Http\Controllers\Stock;

use App\Imports\StockImport;
use Illuminate\Http\Request;
use App\Models\Stock\Category;
use App\Models\Stock\InwardStock;
use App\Models\Stock\StockInward;
use App\Models\Stock\StockOutward;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock\SubscriptionForm;
use Illuminate\Support\Facades\Validator;
use App\Exports\StockInwardReportGenerate;

class InwardStockController extends Controller
{
    public function index($categoryId = 0){
        $user = Auth::user();
        $company = $user->companies->first();
        $items = $categoryId ? StockInward::query()
                            ->whereCompanyId($company->id)
                            ->whereCategoryId($categoryId)
                            ->with(['item:id,name,category_id', 'item.category:id,name'])->get() : 
                            StockInward::query()
                            ->whereCompanyId($company->id)
                            ->with(['item:id,name,category_id', 'item.category:id,name'])->get();
                                            
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
        InwardStock::create($data);
        
        return response()->json([
            'message' => 'Inward Stock Created!'
        ], 200);
    }
    public function update(Request $request, $inwardStockId){
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
        $inwardStock = InwardStock::findOrFail($inwardStockId);
        $inwardStock['quantity'] = $data['quantity'] ? $data['quantity'] : $inwardStock->quantity;
        $inwardStock['entry_date'] = $data['entry_date'] ? $data['entry_date'] : $inwardStock->entry_date;
        $inwardStock['particular'] = $data['particular'] ? $data['particular'] : $inwardStock->particular;
        $inwardStock->update();
        
        return response()->json([
            'message' => 'Inward Stock Updated!'
        ], 200);
    }

    public function destroy($inwardStockId){
        $inwardStock = StockInward::findOrFail($inwardStockId);
        $outwardStock = SubscriptionForm::whereUniqueId($inwardStock->unique_id)->first();
        
        if($outwardStock){
            return response()->json([
                'message' => 'Stock item used, cannot delete.'
            ], 403);
        }
        $inwardStock->delete();

        return response()->json([
            'message' => 'Inward Stock Deleted!'
        ], 200);
    }

    public function storeStock(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'category_id' => 'required',
            'excel_file' => 'required'
        ], [
            'item_id.required' => 'Select item.',
            'category_id.required' => 'Select category.',
            'excel_file.required' => 'Select stock file to upload.',
        ]);
        if($validator->fails()){

            return response()->json([
                'errors' => $validator->errors()
            ],422);
        }
        $data = $request->only(['item_id', 'category_id']);
        if($request->hasFile('excel_file')){
            $file = $request->file('excel_file');
            
            try {
                Excel::import(new StockImport($data['item_id'], $data['category_id'], $company), $file);

                return response()->json([
                    'message' => 'Stock Store Successfully!'
                ], 201);
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                foreach ($failures as $failure) {
                    $failure->row(); // row that went wrong
                    $failure->attribute(); // either heading key (if using heading row concern) or column index
                    $failure->errors(); // Actual error messages from Laravel validator
                    $failure->values(); // The values of the row that has failed.
                }
            }
        }
    }
    public function  generateInwardStockReport($categoryId){
        $user = Auth::user();
        $company = $user->companies->first();
        $category = Category::whereId($categoryId)->first();
        $items = $categoryId ? StockInward::query()
                ->whereCompanyId($company->id)
                ->whereCategoryId($categoryId)
                ->get() : 
                StockInward::query()
                ->whereCompanyId($company->id)
                ->get();
        
        return Excel::download( new StockInwardReportGenerate($items, $category, $company), "stock_item_data.xlsx");
    }

    public function downloadTemplateFile(){
        return response()->download(storage_path('app/template_file.xlsx'));
    }
}
