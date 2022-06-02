<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class StockInward extends Model
{
    
    protected $fillable =[
        'item_id', 'category_id', 'company_id', 'unique_id',
        'po_number', 'esn', 'iccid',
        'date', 'zone', 'plan', 'details', 'remarks', 'remarks_2'
    ];

    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
