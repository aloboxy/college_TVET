<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Get all user types from the table
        $userTypes = DB::table('user_types')->pluck('title')->toArray();
        
        // 2. Add 'student' if not present, because we saw a user with 'student' type
        if (!in_array('student', $userTypes)) {
            $userTypes[] = 'student';
        }

        // 3. Create Key Roles
        foreach ($userTypes as $typeName) {
            // ensure nice naming (slug)
            $roleName = strtolower(str_replace(' ', '_', trim($typeName)));
            Role::firstOrCreate(['name' => $roleName]);
        }
        
        // Ensure standard roles exist just in case
        $standardRoles = ['super_admin', 'admin', 'teacher', 'student', 'parent', 'accountant', 'librarian'];
        foreach ($standardRoles as $role) {
             Role::firstOrCreate(['name' => $role]);
        }

        // 4. Assign Roles to Users
        // Chunk to avoid memory issues
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                if ($user->user_type) {
                    $roleName = strtolower(str_replace(' ', '_', trim($user->user_type)));
                    // Check if role exists in DB (it should from step 3)
                    $role = Role::where('name', $roleName)->first();
                    if ($role) {
                        if (!$user->hasRole($roleName)) {
                            $user->assignRole($role);
                        }
                    } else {
                        // Create it if missing?
                         $role = Role::create(['name' => $roleName]);
                         $user->assignRole($role);
                    }
                }
            }
        });
        
        $this->command->info('Roles and Permissions seeded successfully!');
    }
}
