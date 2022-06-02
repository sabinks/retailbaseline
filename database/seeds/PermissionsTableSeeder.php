<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $permissions = [
            'addAllStaffs',
            'manageAdmin',
            'viewAdmin',
            'manageRegionalAdmin',
            'viewRegionalAdmin',
            'manageMyRegionalAdmin',
            'viewMyRegionalAdmin',
            'manageSupervisor',
            'viewSupervisor',
            'manageMySupervisor',
            'viewMySupervisor',
            'manageFieldStaff',
            'viewFieldStaff',
            'entity',
            'viewRoute',
            'generateReport',
            'manageFormData',
            'approveAttendance',
            'manageRoute',
            'manageRegion',
            'assignRoute',
            'assignRegion',
            'manageCustomizeForm',
            'assignForm',
            'manage_theme',
            'manageClient',
            'assignAdmin',
            'assignRegionalAdmin',
            'MyStaff',
            'mySupervisor',
            'viewAssociate',
            'manageAllStaffs',
            'manageOwnStaffs',
            'hireStaff'
         ];
    
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
