<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class StockInwardReportGenerate extends StringValueBinder  implements FromCollection, WithCustomValueBinder
{
    public $items, $company;

    public function __construct($items, $category, $company){
        $this->items = $items;
        $this->company = $company;
    }
    public function collection()
    {
        $report[4] =['Unique ID', 'PO Number', 'ESN', 'ICCID', 'Zone', 'Date', 'Details', 'Remarks', 'Remarks 2'];

        for ($i = 0; $i < count($this->items); $i++) {
            $report[] = [
                            $this->items[$i]['unique_id'], 
                            $this->items[$i]['po_number'], 
                            $this->items[$i]['esn'], 
                            $this->items[$i]['iccid'],
                            $this->items[$i]['zone'],
                            Carbon::parse($this->items[$i]['date'])->toDateString(),
                            $this->items[$i]['details'],
                            $this->items[$i]['remarks_2'],
                        ];
        }

        return new Collection([
            $report
        ]);
    }
}
