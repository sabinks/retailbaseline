<?php

namespace App\Models\Stock;

use App\User;
use App\Models\Stock\StockInward;
use App\Models\Stock\DocumentType;
use Illuminate\Database\Eloquent\Model;

class SubscriptionForm extends Model
{
    protected $fillable = [
        'unique_id', 'name', 'address', 
        'document_id', 'form_image', 'card_front', 'reg_detail', 'amount',
        'card_back', 'photo', 'lat', 'lng', 'form_id', 
        'item_id', 'staff_id', 'company_id', 'filled_date', 'sync_date'
    ];
    protected $casts = [
        'filled_date' => 'datetime:Y-m-d h:i A',
        'sync_date' => 'datetime:Y-m-d h:i A',
    ];
    public function item(){
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function stockInward(){
        return $this->belongsTo(StockInward::class, 'unique_id', 'unique_id');
    }
    
    public function staff(){
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function documentType(){
        return $this->belongsTo(DocumentType::class, 'document_id');
    }
}
