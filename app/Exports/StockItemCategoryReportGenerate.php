<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class StockItemCategoryReportGenerate implements FromCollection
{
    public $items, $company;

    public function __construct($items, $category, $company){
        $this->items = $items;
        $this->company = $company;
    }
    public function collection()
    {
        $report[0] =['S.N.', 'Name', 'Description', 'Category', 'In Stock', 'Out Stock', 'Stock Available'];

        for ($i = 0; $i < count($this->items); $i++) {
            $sum_inward_stock = $this->items[$i]->sumInwardStock->first() ?  $this->items[$i]->sumInwardStock->first()->quantity : '0';
            $sum_outward_stock = $this->items[$i]->sumOutwardStock->first() ?  $this->items[$i]->sumOutwardStock->first()->quantity : '0';
            $available_stock = $sum_inward_stock - $sum_outward_stock;
            $report[] = [
                            $i + 1, 
                            $this->items[$i]['name'], 
                            $this->items[$i]['description'], 
                            $this->items[$i]['category']->name, 
                            $sum_inward_stock, 
                            $sum_outward_stock,
                            $available_stock != 0 ? $available_stock : '0'
                        ];
        }

        return new Collection([
            $report
        ]);
    }
}
