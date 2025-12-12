<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Super Admin - Gets everything
        $superAdmin = Role::where('name', 'super_admin')->first();
        if($superAdmin){
            $superAdmin->givePermissionTo(Permission::all());

            // Redundant but explicit assignment requested by user
            $superAdmin->givePermissionTo([
                'permissions.manage',
                'college.manage',
                'major.manage',
                'minor.manage',
                'department.manage',
            ]);
        }

        // 2. Admin - Gets most Administrative & Academic things
        $admin = Role::where('name', 'admin')->first();
        if($admin){
            $admin->givePermissionTo([
                'students.create', 'students.view', 'students.edit', 'students.delete', 'students.promote', 'students.graduate',
                'users.create', 'users.view', 'users.edit', 'users.reset_password', // No users.delete usually
                'academics.manage', 'classes.create', 'classes.edit', 'subjects.create', 'subjects.edit', 'sections.create', 'sections.edit',
                'library.create', 'library.edit', 'library.view',
                'marks.view', 'marksheet.view',
                'payments.create', 'payments.view', 'payments.edit', 'payments.manage' // No delete
            ]);
        }

        // 3. Teacher - Academics & Marks
        $teacher = Role::where('name', 'teacher')->first();
        if($teacher){
            $teacher->givePermissionTo([
                'students.view',
                'library.view',
                'marks.create', 'marks.view', 'marks.edit',
            ]);
        }

        // 4. Student - Basic View
        $student = Role::where('name', 'student')->first();
        if($student){
            $student->givePermissionTo([
                'library.view',
                // Marks view is usually handled by ownership logic, but can add if needed
            ]);
        }
    }
}
