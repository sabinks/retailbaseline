<?php

namespace App\Http\Resources\EntityData;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityDataResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'entity_name' => $this->name,
            'title' => $this->entitiesForm->form_title,
            'assigned_staff' => $this->formFiller->name,
            'staff_email' => $this->formFiller->email,
            'assigned_date' => Carbon::parse($this->assigned_date, 'UTC')->format('M d, Y'),
            'filled_date' => Carbon::parse($this->filled_date, 'UTC')->format('M d, Y'),
            'status' => $this->statusConversion($this->status),
            'options' => '<a class="btn btn-secondary btn-sm mr-1" href="/entities-history/' . $this->id . '"><i class="fa fa-eye"></i></a>' .
                        '<a class="btn btn-success btn-sm mr-1" href="/map-location/' . $this->id . '"><i class="fa fa-map-marker"></i></a>' .
                        '<a class="btn btn-danger btn-sm mr-1" onClick="deleteEntityData(' . $this->id . ')"><i class="fa fa-trash"></i></a>'
        ];
    }
    public function statusConversion($status){
        switch ($status) {
            case 1:
                return 'Filled';
            case 2:
                return 'Approved';
            case 3:
                return 'Rejected';
        }
    }
}
