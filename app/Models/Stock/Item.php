<?php

namespace App\Models\Stock;

use App\Region;
use App\Models\Stock\Category;
use App\Models\Stock\InwardStock;
use App\Models\Stock\StockInward;
use App\Models\Stock\OpeningStock;
use App\Models\Stock\OutwardStock;
use Illuminate\Support\Facades\DB;
use App\Models\Stock\SubscriptionForm;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table="items";
    protected $fillable = [
        'name', 'description', 'category_id', 'company_id', 'image_name'
    ];

    public function categories(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function openingStock(){
        return $this->hasMany(OpeningStock::class, 'item_id');
    }
    public function inwardStock(){
        return $this->hasMany(InwardStock::class, 'item_id');
    }
    public function outwardStock(){
        return $this->hasMany(OutwardStock::class, 'item_id');
    }
    public function sumInwardStock(){
        return $this->hasMany(StockInward::class, 'item_id')->selectRaw('stock_inwards.item_id,COUNT(stock_inwards.item_id) as quantity')->groupBy('item_id');
    }
    public function sumOutwardStock(){
        return $this->hasMany(SubscriptionForm::class, 'item_id')->selectRaw('subscription_forms.item_id,COUNT(subscription_forms.item_id) as quantity')->groupBy('item_id');
    }
    public function sumOpeningStock(){
        return $this->hasMany(OpeningStock::class, 'item_id')->selectRaw('opening_stocks.item_id,COALESCE(SUM(opening_stocks.quantity),0) as quantity')->groupBy('item_id');
    }
    public function region(){
        return $this->hasOne(Region::class, 'region_id');
    }
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
