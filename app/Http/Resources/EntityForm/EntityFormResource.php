<?php

namespace App\Http\Resources\EntityForm;

use App\Http\Resources\User\StaffResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityFormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'form_title' => $this->form_title, 
            'staff_list' => StaffResource::collection($this->staffs)
        ];
    }
}
