<?php

use App\Company;
use Illuminate\Database\Seeder;
use App\User;
use App\Theme;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        //truncate users has roles table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_has_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //truncate users creator table
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('creator_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('associate_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('company_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $role = Role::where('name','Super Admin')->first();
        $user = User::create([
            'name'=>'Lemon',
            'phone_number'=>'9811223344',
            'address'=>'Kathmandu',
            'email'=>'superadmin@admin.com',
            'password'=>bcrypt('password')
        ]);
        
        $user->assignRole($role);//assign super admin role to super admin

        //attach the company to user
        $company = Company::find(1);
        $user->companies()->attach($company);
    }
}
