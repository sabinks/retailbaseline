<?php

namespace App\Http\Controllers\Stock;

use App\Models\Stock\Item;
use Illuminate\Http\Request;
use App\Models\Stock\Category;
use App\Models\Stock\InwardStock;
use App\Models\Stock\StockInward;
use App\Models\Stock\OpeningStock;
use App\Models\Stock\OutwardStock;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock\SubscriptionForm;
use App\Exports\StockItemReportGenerate;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Validator;
use App\Exports\StockItemCategoryReportGenerate;
use App\Http\Resources\Stock\InwardStockResource;
use App\Http\Resources\Stock\OpeningStockResource;
use App\Http\Resources\Stock\OutwardStockResource;
use App\Http\Resources\Stock\StockRegisterResource;

class StockRegisterController extends Controller
{
    public function stock($categoryId = 0){
        $user = Auth::user();
        $company = $user->companies->first();
        $role = $user->getRoleNames()->first();
        $staff_list = UserController::companyOnlyStaffList();
        $items = $categoryId ? Item::query()
                                    ->whereCompanyId($company->id)
                                    ->whereCategoryId($categoryId)
                                    ->with(['sumInwardStock', 'sumOutwardStock' => function ($query) use ($staff_list) {
                                        $query->whereIn('subscription_forms.staff_id', $staff_list);
                                    },'category'])->get() : 
                                Item::query()
                                    ->whereCompanyId($company->id)
                                    ->with(['sumInwardStock', 'sumOutwardStock' => function ($query) use ($staff_list) {
                                        $query->whereIn('subscription_forms.staff_id', $staff_list);
                                    },'category'])->get();
        return response()->json([
            'item_list' => StockRegisterResource::collection($items),
            'staff_list' => $staff_list
        ], 200);
    }

    public function stockItemBalanceSheet($itemId){
        $user = Auth::user();
        $company = $user->companies->first();
        $item = Item::whereId($itemId)->whereCompanyId($company->id)->get();
      
        $inwardStock = StockInward::whereItemId($itemId)->whereCompanyId($company->id)->get();
        $outwardStock = SubscriptionForm::whereItemId($itemId)->whereCompanyId($company->id)->get();

        $inwardStock = collect(InwardStockResource::collection($inwardStock));
        $outwardStock = collect(OutwardStockResource::collection($outwardStock));

        $balance_sheet = $inwardStock->merge($outwardStock);
        $balance_sheet = $balance_sheet->sortBy('entry_date', SORT_REGULAR, true)->values()->all();

        return response()->json([
            'balance_sheet' => $balance_sheet,
            'item_detail' => StockRegisterResource::collection($item)
        ], 200);
    }

    public function generateItemReport(Request $request, $itemId){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'report' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Input field missing!','errors'=>$validator->errors(), 'status' => 422
            ],422);
        }
        $data = $request->only(['id', 'report', 'from_date', 'to_date']);
        //generate report
        $item = Item::whereId($itemId)->whereCompanyId($company->id)->with(['category'])->first();
        $all_data = collect([]);
        switch ($data['report']) {
            case '1':
                $openingStock = OpeningStock::whereItemId($itemId)
                    ->whereBetween('entry_date', [$data['from_date'], $data['to_date']])
                    ->whereCompanyId($company->id)->orderBy('entry_date', 'desc')->get();
                $inwardStock = InwardStock::whereItemId($itemId)
                    ->whereBetween('entry_date', [$data['from_date'], $data['to_date']])
                    ->whereCompanyId($company->id)->orderBy('entry_date', 'desc')->get();
                $outwardStock = OutwardStock::whereItemId($itemId)
                    ->whereBetween('entry_date', [$data['from_date'], $data['to_date']])
                    ->whereCompanyId($company->id)->orderBy('entry_date', 'desc')->get(); 

                $openingStock = collect(OpeningStockResource::collection($openingStock));
                $inwardStock = collect(InwardStockResource::collection($inwardStock));
                $outwardStock = collect(OutwardStockResource::collection($outwardStock));

                $all_data = $openingStock->merge($all_data);
                $all_data = $inwardStock->merge($all_data);
                $all_data = $outwardStock->merge($all_data);
                break;

            case '2':
                $openingStock = OpeningStock::whereItemId($itemId)
                    ->whereBetween('entry_date', [$data['from_date'], $data['to_date']])
                    ->whereCompanyId($company->id)->orderBy('entry_date', 'desc')->get();
                $openingStock = collect(OpeningStockResource::collection($openingStock));
                $all_data = $openingStock->merge($all_data);
                break;

            case '3':
                $inwardStock = InwardStock::whereItemId($itemId)
                    ->whereBetween('entry_date', [$data['from_date'], $data['to_date']])
                    ->whereCompanyId($company->id)->orderBy('entry_date', 'desc')->get();
                
                $inwardStock = collect(InwardStockResource::collection($inwardStock));
                $all_data = $inwardStock->merge($all_data);
                break;

            case '4';
                $outwardStock = OutwardStock::whereItemId($itemId)
                    ->whereBetween('entry_date', [$data['from_date'], $data['to_date']])
                    ->whereCompanyId($company->id)->orderBy('entry_date', 'desc')->get();
                $outwardStock = collect(OutwardStockResource::collection($outwardStock));
                $all_data = $outwardStock->merge($all_data);
                break;

            default:
                $all_data = [];
                break;
        }

        
        return Excel::download( new StockItemReportGenerate($item, $company, $all_data), "stock_item_data.xlsx"); 
    }

    public function generateItemCategoryReport($categoryId){
        $user = Auth::user();
        $company = $user->companies->first();
        $category = Category::whereId($categoryId)->first();
        $items = $categoryId ? Item::whereCompanyId($company->id)
                ->whereCategoryId($categoryId)
                ->with('sumInwardStock', 'sumOutwardStock','category')->get() : 
            Item::whereCompanyId($company->id)
                ->with('sumInwardStock', 'sumOutwardStock','category')->get();
        if($items->count() == 0){
            return response()->json([
                'message'=>'No record found.'
            ],404);
        }
        return Excel::download( new StockItemCategoryReportGenerate($items, $category, $company), "stock_item_data.csv");
    }
}
