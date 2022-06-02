<?php

namespace App\Http\Resources\EntityData;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityDataList extends JsonResource
{
    public function toArray($request)
    {
        return [
            'entity_name' => $this->name,
            'form_title' => $this->entitiesForm->form_title,
            'region' => $this->region->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'options' => '<a class="btn btn-secondary btn-sm mr-1" href="/entities-history/' . $this->id . '"><i class="fa fa-eye"></i></a>' . 
                        '<a class="btn btn-success btn-sm mr-1" href="/map-location/' . $this->id . '"><i class="fa fa-map-marker"></i></a>' .
                        '<a class="btn btn-danger btn-sm mr-1" onClick="deleteEntityData(' . $this->id . ')"><i class="fa fa-trash"></i></a>'
        ];
    }
}
