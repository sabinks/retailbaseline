<?php

namespace App\Http\Controllers\Stock;

use Throwable;
use App\Models\Stock\Item;
use Illuminate\Http\Request;
use App\Models\Stock\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
        $user = Auth::user();
        $company = $user->companies->first();
        $categories = Category::whereCompanyId($company->id)->get(['id', 'name', 'description']);

        return response()->json([
            'category_list' => $categories
        ], 200);
    }
    public function store(Request $request){
        $user = Auth::user();
        $company = $user->companies->first();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);

        if($validator->fails()){

            return response()->json(['message'=>'','errors'=>$validator->errors()],422);
        }

        $user = Auth::user();
        $company = $user->companies->first();
        $data = $request->only(['name', 'description']);
        $data['company_id'] = $company->id;
        Category::create($data);
        
        return response()->json([
            'message' => 'Category Created!'
        ], 200);
    }

    public function update(Request $request, $id){
        $data = $request->only(['name', 'description']);
        $category = Category::findOrFail($id);
        if($category){
            $category->name = $data['name'] ? $data['name'] : $category->name;
            $category->description = $data['description'] ? $data['description'] : $category->description;
            $category->update();
    
            return response()->json([
                'message' => 'Category Updated!'
            ], 200);
        }
    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $item_count = Item::whereCategoryId($id)->get()->count();
        if($item_count){

            return response()->json([
                'message' => 'Category Used, Cannot Be Deleted!'
            ], 409);
        }
       $category->delete();

        return response()->json([
            'message' => 'Category Deleted!'
        ], 200);
    }
}
