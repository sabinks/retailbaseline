<?php

namespace App\Models\Stock\Sim;

use App\Models\Stock\Item;
use App\Models\Stock\Category;
use Illuminate\Database\Eloquent\Model;

class SimInwardStock extends Model
{
    protected $fillable =[
        'item_id', 'category_id', 'sim_number',
        'po_number', 'model_name', 'esn', 'iccid',
        'date', 'zone', 'plan'
    ];

    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
