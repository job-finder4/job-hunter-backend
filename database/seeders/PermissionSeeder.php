<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'create jobads']);
        Permission::create(['name' => 'update jobads']);
        Permission::create(['name' => 'approve jobads']);
        Permission::create(['name' => 'view all company jobads']);
        Permission::create(['name' => 'create profile']);
        Permission::create(['name' => 'update profile']);


        $jobSeekerRole = Role::create(['name' => 'jobSeeker']);
        $companyRole = Role::create(['name' => 'company']);
        $adminRole = Role::create(['name' => 'admin']);

        $companyRole->givePermissionTo('create jobads');
        $companyRole->givePermissionTo('update jobads');
        $companyRole->givePermissionTo('view all company jobads');

        $jobSeekerRole->givePermissionTo('create profile');
        $jobSeekerRole->givePermissionTo('update profile');
    }
}
