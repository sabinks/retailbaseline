<?php

namespace App\Http\Resources\EntityForm;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityForm extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->form_title, 
        ];
    }
}
