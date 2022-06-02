<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\ReportForm\ReportForm;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyReport extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->company_name, 
            'report_form' => ReportForm::collection($this->reportForm)
        ];
    }
}
