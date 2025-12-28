<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create departments first
        $departments = ['Web', 'Mobile', 'AI', 'Data Science', 'UI Design'];
        $departmentIds = [];
        foreach($departments as $department) {
            $dept = App\Models\Department::create(['name' => $department]);
            $departmentIds[] = $dept->id;
        }
        
        // Call RolesAndPermissionsSeeder to create default users and roles
        $this->call(RolesAndPermissionsSeeder::class);
        
        // Create 15 additional random users
        \App\Models\User::factory(15)->create()->each(function ($user) use ($departmentIds) {
            $user->department_id = $departmentIds[array_rand($departmentIds)];
            $user->save();
        });

        // Add 2 specific employees to Mobile department
        $mobileDeptId = App\Models\Department::where('name', 'Mobile')->first()->id;
        \App\Models\User::factory(2)->create()->each(function ($user) use ($mobileDeptId) {
            $user->department_id = $mobileDeptId;
            $user->save();
        });
        
        // Add skills, leaves, trainings, and evaluations to make it look like a real company
        $this->call(CompanyDataSeeder::class);
    }
}
