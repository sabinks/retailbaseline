<?php

namespace App\Http\Resources\ReportForm;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportForm extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->title, 
            'value' => $this->title,
            'options' => '<a class="btn btn-secondary btn-sm mr-1" href="/super/report-form/update/' . $this->id . '"><i class="fa fa-pencil"></i></a>' . 
                        '<a class="btn btn-success btn-sm mr-1" onClick="downloadReport(' . $this->id . ')"><i class="fa fa-save"></i></a>' .
                        '<a class="btn btn-primary btn-sm mr-1" href="/super/report-data/list/' . $this->id . '"><i class="fa fa-eye"></i></a>' .
                        '<a class="btn btn-danger btn-sm mr-1" onClick="deleteReport(' . $this->id . ')"><i class="fa fa-trash"></i></a>' 
        ];
    }
}
