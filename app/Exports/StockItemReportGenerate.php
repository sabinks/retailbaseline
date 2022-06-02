<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class StockItemReportGenerate implements FromCollection
{
    public $item, $company, $all_data;

    public function __construct($item, $company, $all_data){
        $this->item = $item;
        $this->company = $company;
        $this->all_data = $all_data;
    }

    public function collection()
    {   
        $report[] =['S.N.', 'Entry Date', 'Stock Type', 'Particular', 'Quantity'];

        for ($i = 0; $i < count($this->all_data); $i++) {
            $report[] = [$i + 1, $this->all_data[$i]['entry_date'], $this->all_data[$i]['stock_type'], $this->all_data[$i]['particular'], $this->all_data[$i]['quantity']];
        }
        $report[] = [];
        $report[]=  ['Stock Summary:'];
        $opening_stock = $this->item->sumOpeningStock()->first() ? $this->item->sumOpeningStock()->first()->quantity : 0;
        $inward_stock = $this->item->sumInwardStock()->first() ? $this->item->sumInwardStock()->first()->quantity : 0;
        $outward_stock = $this->item->sumOutwardStock()->first() ? $this->item->sumOutwardStock()->first()->quantity : 0;
        
        $report[] = ['Opening Stock: ' . $opening_stock];
        $report[] = ['Inward Stock: ' . $inward_stock];
        $report[] =[ 'Outward Stock: '  .$outward_stock];
        $report[] = ['Available Stock: ' . ($opening_stock + $inward_stock - $outward_stock)];

        return new Collection([
            $report
        ]);
    }
}
