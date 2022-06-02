<?php

namespace App\Models\Stock;

use App\Models\Stock\Item;
use Illuminate\Database\Eloquent\Model;

class InwardStock extends Model
{
    protected $fillable = [
        'item_id', 'quantity', 'entry_date', 'particular', 'company_id'
    ];

    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }
  
}
