<?php

use Illuminate\Database\Seeder;
use App\Models\Stock\Sim\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $document_type = [
            ['name' => 'Citizenship'],
            ['name' => 'Passport'],
            ['name' => 'National ID'],
            ['name' => 'Voting Card'],
            ['name' => 'Driving License'],
            ['name' => 'Adhar Card'],
            ['name' => 'Rasan Card'],
            ['name' => 'NRN Card'],
            ['name' => 'Student ID Card'],
            ['name' => 'Indian Registration Card'],
            ['name' => 'Refugee Card'],
            ['name' => 'Other'],
        ];
        DocumentType::insert($document_type);
    }
}
