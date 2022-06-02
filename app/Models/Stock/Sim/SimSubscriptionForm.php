<?php

namespace App\Models\Stock\Sim;

use Illuminate\Database\Eloquent\Model;

class SimSubscriptionForm extends Model
{
    protected $fillable = [
        'sim_number', 'esn_number', 'name', 'address', 
        'document_id', 'front_image', 'card_front','amount',
        'card_back', 'photo', 'lat', 'lng', 'form_id', 
        'item_id', 'staff_id', 'company_id'
    ];
}
