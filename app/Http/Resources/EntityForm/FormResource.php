<?php

namespace App\Http\Resources\EntityForm;

use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->form_title, 
            'value' => $this->form_title,
        ];
    }
}
