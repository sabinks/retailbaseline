<?php

namespace App\Http\Controllers\Stock;

use File;
use App\User;
use ZipArchive;
use Carbon\Carbon;

use App\Models\Stock\Item;
use Illuminate\Http\Request;
use App\Models\Stock\Category;
use App\Models\Stock\OutwardStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock\SubscriptionForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Exports\StockOutwardReportGenerate;

class OutwardStockController extends Controller
{
    public function index($categoryId = 0){
        $user = Auth::user();
        $company = $user->companies->first();
        if($categoryId){
            $itemIds = Item::whereCategoryId($categoryId)->get('id')->toArray();
        }
        $all_staff_list = $this->staffList();
       
        $items = $categoryId ? 
        SubscriptionForm::query()
                // ->whereCompanyId($company->id)
                ->whereIn('staff_id', $all_staff_list)
                ->whereIn('item_id', $itemIds)
                ->with(['item', 'item.category', 'staff', 'documentType'])->get() : 
        SubscriptionForm::query()
                // ->whereCompanyId($company->id)
                ->whereIn('staff_id', $all_staff_list)
                ->with(['item', 'item.category', 'staff', 'documentType'])->get();
      
                                            
        return response()->json([
            'item_list' => $items
        ], 200);
    }
  
    public function update(Request $request, $outwardStockId){
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
        $outwardStock = OutwardStock::findOrFail($outwardStockId);
        $outwardStock['quantity'] = $data['quantity'] ? $data['quantity'] : $outwardStock->quantity;
        $outwardStock['entry_date'] = $data['entry_date'] ? $data['entry_date'] : $outwardStock->entry_date;
        $outwardStock['particular'] = $data['particular'] ? $data['particular'] : $outwardStock->particular;
        $outwardStock->update();
        
        return response()->json([
            'message' => 'Outward Stock Updated!'
        ], 200);
    }

    public function destroy($outwardStockId){
        $outwardStock = SubscriptionForm::findOrFail($outwardStockId);
        $images_field = ['card_back', 'card_front', 'form_image', 'photo'];
        if($outwardStock){
            foreach($images_field as $image){
                Storage::delete('public/images/outward_stock/'. $outwardStock[$image]);
            }
       }
        $result = $outwardStock->delete();

        return response()->json([
            'message' => 'Outward Stock Deleted!'
        ], 200);
    }

    public function outwardItemDetail($id){
        $user = Auth::user();
        $company = $user->companies->first();
        $all_staff_list = $this->staffList();
        $itemDetail = SubscriptionForm::whereId($id)->whereIn('staff_id', $all_staff_list)
                                ->with(['item:id,name,category_id', 'item.category:id,name', 'staff:id,name', 'documentType:id,name'])->first();
        if(!$itemDetail){
            
            return response()->json([
                'message' => 'No record found.'
            ], 404);
        }

        return response()->json([
            'item_detail' => $itemDetail,
            'image_list' => [$itemDetail->form_image, $itemDetail->card_front, $itemDetail->card_back, $itemDetail->photo]
        ], 200);
    }

    public function generateOutwardStockReport(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'item_id' => 'required',    
            'from_date' => 'required',
            'to_date' => 'required',
        ], [
            'category_id.required' => 'Please select category.',
            'item_id.required' => 'Please select item.',
            'from_date.required' => 'Please select from date.',
            'to_date.required' => 'Please select to date.',
        ]);
        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }
        $category_id = $request->query('category_id');
        $from_date = $request->query('from_date');
        $to_date = $request->query('to_date');
        $item_id = $request->query('item_id');
        $bulk_image = $request->query('bulk_image');
       
        $category = Category::whereId($category_id)->first();
        $item_array = Item::query()->whereId($item_id)->whereCategoryId($category_id)->get(['id'])->toArray();
        $all_staff_list = $this->staffList();
        $date_from = Carbon::parse($from_date)->startOfDay();
        $date_to = Carbon::parse($to_date)->endOfDay();

        $outwardStock = SubscriptionForm::query()
                        // ->whereCompanyId($company->id)
                        ->whereIn('staff_id', $all_staff_list)
                        ->whereIn('item_id', $item_array)
                        ->whereDate('sync_date', '>=', $date_from)
                        ->whereDate('sync_date', '<=', $date_to)
                        // ->whereBetween('sync_date', [$from_date, $to_date])
                        ->with(['staff', 'item', 'stockInward'])
                        ->get();
        if($outwardStock->count() == 0){
        
            return response()->json([
                'message' => 'No record found!',
            ], 404);
        }

        $zip = new ZipArchive;
        $zip_file = 'test.zip';
        if ($zip->open(storage_path('app/' . $zip_file), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)
        {
            //add excel file newly created
            Excel::store( new StockOutwardReportGenerate($outwardStock), "report_stock.xlsx", 'local');
            $zip->addFile(storage_path('app/report_stock.xlsx'), 'report_stock.xlsx'); 
            if($bulk_image){
                $destination_path = 'public/images/outward_stock';
                $image_array = ['form_image', 'card_front', 'card_back', 'photo'];  //image type on current system
                foreach ($outwardStock as $key => $stock) {
                    for ($index=0; $index < 4; $index++) { 
                        $file_path = storage_path() ."/app/" . $destination_path . "/" . $stock[$image_array[$index]];
                        $zip->addFile($file_path,  $stock->unique_id . '/' . $image_array[$index]. '.' . explode(".",  $stock[$image_array[$index]])[1]);
                    }
                }
            }            
            $zip->close();
        }
    
        return response()->download(storage_path('app/' . $zip_file));

    }

    public function staffList(){
        $user = Auth::user();
        $role= $user->getRoleNames()->first();
        $company = $user->companies->first();
        if($role == "Admin"){
            $company_staff = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
            $lemon_staff = DB::table('associate_user')->whereStaffStatus(3)->get()->pluck('staff_id')->toArray();
            $all_staff_list = array_merge($company_staff, $lemon_staff);
        }else if($role == "Regional Admin"){
            $company_staff = $company->users()->role('Field Staff')->get()->pluck('id')->toArray();
            $lemon_staff = DB::table('associate_user')->whereStaffStatus(3)->get()->pluck('staff_id')->toArray();
            $all_staff_list = array_merge($company_staff, $lemon_staff);
            $region_ids =  DB::table('region_user')->whereUserId($user->id)->get()->pluck('region_id')->toArray();
            $all_staff_list = DB::table('region_user')->whereIn('user_id', $all_staff_list)
                                    ->whereIn('region_id', $region_ids)
                                    ->get()->pluck('user_id')->toArray();
        }else if($role == "Supervisor"){ //fine
            $all_staff_list = User::findOrFail([DB::table('fieldstaffs_supervisors')
                                                    ->whereIn('supervisor_id', $user->id)
                                                    ->get()
                                                    ->pluck('fieldstaff_id')->toArray()
                                            ]);
        }
        else{
            abort(403);
        }
        return $all_staff_list;
    }
}
