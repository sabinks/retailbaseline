<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class OpeningStock extends Model
{
    protected $fillable = [
        'item_id', 'quantity', 'entry_date', 'particular', 'company_id'
    ];
    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }
}
