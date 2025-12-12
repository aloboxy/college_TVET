<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

// 1. Reset Cache
app()[PermissionRegistrar::class]->forgetCachedPermissions();

// 2. Define the problematic permissions
$perms = [
    'permissions.manage',
    'college.manage',
    'major.manage',
    'minor.manage',
    'department.manage',
];

foreach($perms as $pName) {
    // 3. Delete if exists to force clean slate
    $p = Permission::where('name', $pName)->where('guard_name', 'web')->first();
    if($p) {
        $p->delete();
    }
    
    // 4. Create fresh
    try {
        Permission::create(['name' => $pName, 'guard_name' => 'web']);
        echo "Created: $pName\n";
    } catch (\Exception $e) {
        echo "Error Creating $pName: " . $e->getMessage() . "\n";
    }
}

// 5. Assign to Super Admin
$role = Role::where('name', 'super_admin')->first();
if ($role) {
    $role->givePermissionTo($perms);
    echo "Assigned permissions to super_admin\n";
} else {
    echo "Role super_admin not found\n";
}

// 6. Final Cache Clear
app()[PermissionRegistrar::class]->forgetCachedPermissions();
echo "Cache cleared.\n";
