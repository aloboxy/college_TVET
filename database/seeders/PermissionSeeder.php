<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // Students
            'students.create',
            'students.view',
            'students.edit',
            'students.delete',
            'students.promote',
            'students.graduate',

            // Users
            'users.create',
            'users.view',
            'users.edit',
            'users.delete',
            'users.reset_password',

            // Academics (Classes, Subjects, Sections)
            'academics.manage', // General super-access or split if needed
            'classes.create',
            'classes.edit',
            'classes.delete',
            'subjects.create',
            'subjects.edit',
            'subjects.delete',
            'sections.create',
            'sections.edit',
            'sections.delete',

            // Library
            'library.create',
            'library.edit',
            'library.delete',
            'library.view',

            // Marks
            'marks.create',
            'marks.view',
            'marks.edit',
            'marks.delete',
            'marksheet.view',

            // Payments
            'payments.create',
            'payments.view',
            'payments.edit',
            'payments.delete',
            'payments.manage',

            // Management
            'permissions.manage',
            'college.manage',
            'major.manage',
            'minor.manage',
            'department.manage',

            // Grade Approval
            'mark.request_change',
            'mark.approve_dept',
            'mark.approve_college',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
