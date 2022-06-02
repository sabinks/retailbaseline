<?php

use App\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Region::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('region_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $regions = [
            'Achham',	
            'Arghakhanchi',	
            'Baglung',	
            'Baitadi',	
            'Bajhang',	
            'Bajura',	
            'Banke',	
            'Bara',
            'Bardiya',
            'Bhaktapur'	,
            'Bhojpur',	
            'Chitwan',	
            'Dadeldhura',	
            'Dailekh'	,
            'Dang Deokhuri',	
            'Darchula',	
            'Dhading',	
            'Dhankuta',	
            'Dhanusa',	
            'Dolakha',
            'Dolpa',	
            'Doti',
            'Gorkha',
            'Gulmi',
            'Humla',	
            'Ilam',	
            'Jajarkot',
            'Jhapa',
            'Jumla',
            'Kailali',
            'Kalikot',
            'Kanchaur',
           ' Kapilvastu',
           ' Kaski',
            'Kathmandu',
            'Kavrepalanchok',
            'Khotang',
            'Lalitpur',
            'Lamjung',
            'Mahottari',
            'Makwaur',
            'Manang',
            'Morang',
            'Mugu',
            'Mustang',
            'Myagdi',
            'Nawalpur',
            'Parasi',
            'Nuwakot',
            'Okhaldhunga',
            'Palpa',
            'Panchthar',
            'Parbat',
            'Parsa',
            'Pyuthan',
            'Ramechhap',
            'Rasuwa',
            'Rautahat',
            'Rolpa',
            'Eastern Rukum',
            'Western Rukum',
            'Rupandehi',
            'Salyan',
            'Sankhuwasabha',
            'Saptari',
            'Sarlahi',
            'Sindhuli',
            'Sindhupalchok',
            'Siraha',
            'Solukhumbu',
            'Sunsari',
            'Surkhet',
            'Syangja',
            'Tanahu',
            'Taplejung',
            'Terhathum',
            'Udayapur'
                ];
            
        foreach ($regions as $region) {
            Region::create(['name' => $region]);
        }
    }
}
