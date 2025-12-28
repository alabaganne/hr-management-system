<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    private $migrationRequiredMessage = ', Please run "php artisan migrate:refresh" before running the seeder again';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
       try {
            Permission::create(['name' => 'view collaborators']);
            Permission::create(['name' => 'add collaborators']);
            Permission::create(['name' => 'edit collaborators']);
            Permission::create(['name' => 'delete collaborators']);
            Permission::create(['name' => 'manage accounts']); // specific for the manager (super admin)

        } catch(Throwable $e) {
            report($e);
            error_log('ERROR: Permission already exists' . $this->migrationRequiredMessage);
            return;
        }

        try {
            // create roles
            $rh = Role::create(['name' => 'Human Resources Manager']);
            $project_manager = Role::create(['name' => 'Project Manager']);
            $admin = Role::create(['name' => 'Admin']);
        } catch(Throwable $e) {
            report($e);
            error_log('ERROR: Role already exists' . $this->migrationRequiredMessage);
            return;
        }


        // give permissions to roles
        try {
            $rh->givePermissionTo(['view collaborators', 'add collaborators', 'edit collaborators', 'delete collaborators']);
            $project_manager->givePermissionTo([]);
            $admin->givePermissionTo(Permission::all());
        } catch(Throwable $e) {
            report($e);
            error_log('ERROR: Role already have that permission' . $this->migrationRequiredMessage);
            return;
        }

        // assign roles to users
        try {
            User::create([
                'name' => 'Sarah Johnson',
                'username' => 'sarah.johnson',
                'email' => 'rh@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '555-0123',
                'date_of_birth' => '1988-05-15',
                'address' => '123 Main St, City, State',
                'civil_status' => 'married',
                'gender' => 'female',
                'id_card_number' => '12345678',
                'nationality' => 'American',
                'university' => 'Harvard Business School',
                'history' => 'HR Manager with 8 years experience',
                'experience_level' => 8,
                'source' => 'internal',
                'position' => 'HR Manager',
                'grade' => 'Senior',
                'hiring_date' => '2018-01-15',
                'contract_end_date' => '2028-01-15',
                'allowed_leave_days' => 25,
                'department_id' => 1
            ])->syncRoles($rh);

            User::create([
                'name' => 'Michael Chen',
                'username' => 'michael.chen',
                'email' => 'projectmanager@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '555-0456',
                'date_of_birth' => '1985-09-22',
                'address' => '456 Oak Ave, City, State',
                'civil_status' => 'single',
                'gender' => 'male',
                'id_card_number' => '87654321',
                'nationality' => 'Canadian',
                'university' => 'MIT',
                'history' => 'Project Manager with 10 years in tech',
                'experience_level' => 10,
                'source' => 'recruitment',
                'position' => 'Senior Project Manager',
                'grade' => 'Senior',
                'hiring_date' => '2016-03-01',
                'contract_end_date' => '2026-03-01',
                'allowed_leave_days' => 22,
                'department_id' => 1
            ])->syncRoles($project_manager);

            User::create([
                'name' => 'Emma Rodriguez',
                'username' => 'emma.rodriguez',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'phone_number' => '555-0789',
                'date_of_birth' => '1982-12-03',
                'address' => '789 Pine St, City, State',
                'civil_status' => 'married',
                'gender' => 'female',
                'id_card_number' => '11223344',
                'nationality' => 'Spanish',
                'university' => 'Stanford University',
                'history' => 'General Manager with 15 years leadership experience',
                'experience_level' => 15,
                'source' => 'headhunting',
                'position' => 'General Manager',
                'grade' => 'Executive',
                'hiring_date' => '2015-06-01',
                'contract_end_date' => '2025-06-01',
                'allowed_leave_days' => 30,
                'department_id' => 1
            ])->syncRoles($admin);
        } catch(Throwable $e) {
            report($e);
            error_log('ERROR: User already exists' . $this->migrationRequiredMessage);
        }
    }
}
