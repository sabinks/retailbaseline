<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class StockOutwardReportGenerate extends StringValueBinder  implements FromCollection, WithCustomValueBinder
{
    public $stocks;

    public function __construct($stocks){
        $this->stocks = $stocks;
    }
    public function collection()
    {
        $report[0] =['Unique ID', 'Name', 'Address', 'Amount', 'Document Type', 'ID Number', 'Latitude', 'Longitude', 'Filled Date', 'Sync Date','Item Name', 'Filled By', 'PO Number', 'ESN', 'ICCID', 'Date', 'Zone', 'Details', 'Remark', 'Remarks 2'];

        for ($i = 0; $i < count($this->stocks); $i++) {
            $report[] = [
                            $this->stocks[$i]['unique_id'],     
                            $this->stocks[$i]['name'], 
                            $this->stocks[$i]['address'], 
                            $this->stocks[$i]['amount'] ? $this->stocks[$i]['amount'] : '-', 
                            $this->stocks[$i]['documentType']['name'],
                            $this->stocks[$i]['reg_detail'],
                            $this->stocks[$i]['lat'],
                            $this->stocks[$i]['lng'],
          
                            Carbon::createFromFormat('Y-m-d H:i:s' ,$this->stocks[$i]['filled_date'] )->format('Y-m-d h:mA'),
                            Carbon::createFromFormat('Y-m-d H:i:s' ,$this->stocks[$i]['sync_date'] )->format('Y-m-d h:mA'),
                            
                            $this->stocks[$i]['item']['name'],
                            $this->stocks[$i]['staff']['name'],

                            $this->stocks[$i]['stockInward']['po_number'] ? $this->stocks[$i]['stockInward']['po_number'] : '-',
                            $this->stocks[$i]['stockInward']['esn'] ? $this->stocks[$i]['stockInward']['esn'] : '-',
                            $this->stocks[$i]['stockInward']['iccid'] ? $this->stocks[$i]['stockInward']['iccid'] : '-',
                            $this->stocks[$i]['stockInward']['date'] ? $this->stocks[$i]['stockInward']['date'] : '-',
                            $this->stocks[$i]['stockInward']['zone'] ? $this->stocks[$i]['stockInward']['zone'] : '-',
                            $this->stocks[$i]['stockInward']['details'] ? $this->stocks[$i]['stockInward']['details'] : '-',
                            $this->stocks[$i]['stockInward']['remarks'] ? $this->stocks[$i]['stockInward']['remarks'] : '-',
                            $this->stocks[$i]['stockInward']['remarks_2'] ? $this->stocks[$i]['stockInward']['remarks_2'] : '-',

                        ];
        }

        return new Collection([
            $report
        ]);
    }
}
