<?php

namespace App\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class OutwardStock extends Model
{
    protected $fillable = [
        'item_id', 'entity_id', 'quantity', 'entry_date', 'particular', 'company_id', 'owner', 'contact'
    ];

    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }
}
