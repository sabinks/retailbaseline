<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\EntityForm\FormResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyEntity extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->company_name, 
            'entity_form' => FormResource::collection($this->entityForm)
        ];
    }
}
