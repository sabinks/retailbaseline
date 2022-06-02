<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $role = Role::create(['name'=>'Super Admin']);
        //assign permission to the role 'super admin'
        $permissions = Permission::whereIn('name', [
            'manageClient',
            'manageAdmin',
            'viewAdmin',
            // 'manageRegionalAdmin',
            // 'viewRegionalAdmin',
            // 'manageSupervisor',
            'manageFieldStaff',
            // 'viewRegionalAdmin',
            // 'viewSupervisor',
            'viewFieldStaff',
            'manageRegion',
            'manageCustomizeForm',
            'assignAdmin',
            // 'assignRegionalAdmin',
            'assignForm',
            'entity',
            'viewAssociate',
            'manageAllStaffs',
            ])->get();
        $role->syncPermissions($permissions);

        $role = Role::create(['name'=>'Admin']);
        //assign permission to the role 'super admin'
        $permissions = Permission::whereIn('name', [
            'addAllStaffs',
            'manageClient',
            'manageMyRegionalAdmin',
            'viewMyRegionalAdmin',
            'manageMySupervisor',
            'viewMySupervisor',
            'manageFieldStaff',
            'viewFieldStaff',
            'generateReport',
            'approveAttendance',
            'manageRoute',
            'manageRegion',
            'assignRoute',
            'manageCustomizeForm',
            'assignForm',
            'manage_theme',
            'MyStaff',
            'assignRegionalAdmin',
            'mySupervisor',
            'manageOwnStaffs',
            'hireStaff'
            ])->get();
        $role->syncPermissions($permissions);

        $role = Role::create(['name'=>'Regional Admin']);
        //assign permission to the role 'super admin'
        $permissions = Permission::whereIn('name', [
            'manageClient',
            'manageMySupervisor',
            'viewMySupervisor',
            'manageFieldStaff',
            'viewFieldStaff',
            'generateReport',
            'approveAttendance',
            'manageRoute',
            'manageCustomizeForm',
            'assignForm',
            'assignRoute',
            'mySupervisor',
            'manageOwnStaffs',
            'MyStaff'
            ])->get();
        $role->syncPermissions($permissions);

        $role = Role::create(['name'=>'Supervisor']);
        //assign permission to the role 'super admin'
        $permissions = Permission::whereIn('name', [
            'generateReport',
            'approveAttendance',
            'assignRoute',
            'assignForm',
            'MyStaff'
            ])->get();
        $role->syncPermissions($permissions);

        $role = Role::create(['name'=>'Field Staff']);
        //assign permission to the role 'super admin'
        $permissions = Permission::whereIn('name', [
            'entity',
            'viewRoute',
            'manageFormData',
            ])->get();
        $role->syncPermissions($permissions);

        
    }
}
