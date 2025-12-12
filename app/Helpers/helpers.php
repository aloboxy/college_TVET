<?php

use Illuminate\Support\Facades\Auth;
use App\Models\UserPermission;


if (!function_exists('hasPermission')) {
    /**
     * Check if the authenticated user has a specific permission.
     *
     * @param string $permissionName
     * @return bool
     */
    function pcan($permissionName)
    {
        $user = Auth::user();
        if (!$user) {
            return false; // If user is not logged in, deny access.
        }
        // Assuming that the user model has a permissions relationship
        return $user->permissions()->where('name', $permissionName)->exists();
    }
}
