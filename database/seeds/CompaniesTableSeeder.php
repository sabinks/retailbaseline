<?php

use App\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Company::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Company::create([
            'company_name'=>'Lemon',
            'company_phone_number'=>'9841010101',
            'company_address'=>'kathmandu',
            'webaddress'=>'Kathmandu',
            'theme'=>'themePurple',
            'webaddress'=>'www.lemon.com',
        ]);
    }
}
