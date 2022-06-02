<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Stock\StockInward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StockImport implements ToCollection, WithChunkReading, WithStartRow, WithHeadingRow
{
    use Importable;

    public $item_id, $category_id, $company;

    public function __construct($item_id, $category_id, $company){
       $this->item_id = $item_id;
       $this->category_id = $category_id;
       $this->company = $company; 
    }
    
    public function collection(Collection $rows)
    {
        // Validator::make($rows->toArray(), [
        //     '*.' => 'required',
        //     '*.1' => 'required',
        //     '*.5' => 'required',
        // ])->validate();

        foreach ($rows as $row) 
        {
            \Log::info($row);
            StockInward::create([
                'item_id' => $this->item_id,
                'category_id' => $this->category_id,
                'company_id' => $this->company->id,
                'unique_id' => $row['unique_id'],
                'po_number' => $row['po_number'],
                'esn' => $row['esn'] ?? 0,
                'iccid' => $row['iccid'] ?? 0,
                'date' => Date::excelToDateTimeObject($row['date'])->format('Y-m-d'),
                'zone' => $row['zone'] ?? 0 ,
                'details' => $row['details'] ?? 0,
                'remarks' => $row['remarks'] ?? 0,
                'remarks_2' => $row['remarks_2'] ?? 0,
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 100;
    }
    public function startRow(): int
    {
        return 2;
    }
    public function rules(): array
    {
        return [

             // Can also use callback validation rules
             '5' => function($attribute, $value, $onFailure) {
                  if ($value == null) {
                       $onFailure('Date is missing on record!');
                  }
              }
        ];
    }
  
}
