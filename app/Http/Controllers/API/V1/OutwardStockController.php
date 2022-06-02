<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock\SubscriptionForm;
use Illuminate\Support\Facades\Validator;
use App\Exports\StockOutwardReportGenerate;

class OutwardStockController extends Controller
{
    public function store(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|unique:subscription_forms',
            'name' => 'required|string',
            'address' => 'required',
            'document_id' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'form_id' => 'required',
            'item_id' => 'required',
            'form_image' => 'required',
            'card_front' => 'required',
            'card_back' => 'required',
            'photo' => 'required',
            'filled_date' => 'required',
            'reg_detail'=> 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message'=>'Input field missing!',
                'errors'=>$validator->errors(), 
                'status' => 422
            ],422);
        }

        $data = $request->only(['unique_id', 'name', 'address', 'document_id', 'lat', 'lng', 'form_id', 'item_id', 'filled_date', 'amount', 'reg_detail']);
        $data['company_id'] = $company->id;
        $data['staff_id'] = $user->id;
        $data['sync_date'] = Carbon::now('Asia/Kathmandu')->toDateTimeString();
        $image_array = ['form_image', 'card_front', 'card_back', 'photo'];
        foreach ($image_array as $key => $value) {
            if($request->hasFile($value)){
                $image = $request->file($value);
                $image_name = $data['unique_id'] . "_" . $value . ".". $image->getClientOriginalExtension();
                $data[$value] = $image_name;
            }
        }
        try {
            DB::beginTransaction();
            SubscriptionForm::create($data);
            DB::commit();
            //now store images
            foreach ($image_array as $key => $value) {
                if($request->hasFile($value)){
                    $image = $request->file($value);
                    $destination_path =  '/public/images/outward_stock';
                    $result = $image->storeAs($destination_path, $data[$value]);
                }
            }

            return response()->json([
                'message'=>'Subscription Form Created Successfully!',
        
            ],201);

        } catch (\Throwable $th) {
            \Log::error($th);
        }
    }
    
    public function outwardStockDetail(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
          'from_date' => 'required',
          'to_date' => 'required',
          'item_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors(), 
            ],422);
        }
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');
        $item_id = $request->query('item_id');
        $download = $request->query('download');
        $outwardStock = SubscriptionForm::query()
                        ->whereStaffId($user->id)
                        ->whereItemId($item_id)
                        ->whereBetween('filled_date', [$from_date, $to_date])
                        ->with(['staff', 'item'])
                        ->get();

        return response()->json([
            'report_count' => $outwardStock->count()
        ], 200);
    }

    public function outwardStockDetailReport(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
          'from_date' => 'required',
          'to_date' => 'required',
          'item_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->errors(), 
            ],422);
        }
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');
        $item_id = $request->query('item_id');
        $outwardStock = SubscriptionForm::query()
                        ->whereStaffId($user->id)
                        ->whereItemId($item_id)
                        ->whereBetween('filled_date', [$from_date, $to_date])
                        ->with(['staff', 'item'])
                        ->get();
        if($outwardStock->count() <= 0) {

            return response()->json([
                'message' => 'No data for report generation.'
            ], 404);
        }

        Excel::store( new StockOutwardReportGenerate($outwardStock), "report_stock_report.xlsx", 'public');

        return response()->json([
            'url' => 'storage/report_stock_report.xlsx'
        ], 200);

        // return response()->download(storage_path('app/public/report_stock_report.xlsx'));
    }
}
