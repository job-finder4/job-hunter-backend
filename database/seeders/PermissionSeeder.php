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

        $jobSeekerRole = Role::create(['name' => 'jobSeeker']);
        $companyRole = Role::create(['name' => 'company']);
        $adminRole = Role::create(['name' => 'admin']);

    }
}
