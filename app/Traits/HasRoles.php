<?php

namespace App\Traits;

trait HasRoles
{
    /**
     * Check if user has a specific role
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }

        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return false;
    }

    /**
     * Check if user is super admin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is admin (including super admin)
     *
     * @return bool
     */
    public function isAdmin()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Check if user can access admin panel
     *
     * @return bool
     */
    public function canAccessAdmin()
    {
        return $this->hasRole(['super_admin', 'admin']);
    }

    /**
     * Get role display name
     *
     * @return string
     */
    public function getRoleDisplayNameAttribute()
    {
        $roleNames = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'manager' => 'Manager',
            'staff' => 'Staff',
            'accountant' => 'Accountant',
            'customer' => 'Customer',
        ];

        return $roleNames[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get role display name (method version)
     *
     * @return string
     */
    public function getRoleDisplayName()
    {
        return $this->getRoleDisplayNameAttribute();
    }

    /**
     * Get role color for UI
     *
     * @return string
     */
    public function getRoleColorAttribute()
    {
        $roleColors = [
            'super_admin' => 'bg-purple-100 text-purple-800',
            'admin' => 'bg-blue-100 text-blue-800',
            'manager' => 'bg-green-100 text-green-800',
            'staff' => 'bg-yellow-100 text-yellow-800',
            'accountant' => 'bg-indigo-100 text-indigo-800',
            'customer' => 'bg-gray-100 text-gray-800',
        ];

        return $roleColors[$this->role] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if user has specific permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Admin has most permissions except some sensitive ones
        if ($this->isAdmin()) {
            $adminPermissions = [
                'dashboard', 'products', 'categories', 'brands', 'orders', 
                'customers', 'inventory', 'coupons', 'reviews', 'cms', 
                'reports', 'settings.general', 'settings.payment', 'settings.email'
            ];
            return in_array($permission, $adminPermissions);
        }

        // Check user's specific permissions if stored
        if (isset($this->permissions) && is_array($this->permissions)) {
            return in_array($permission, $this->permissions);
        }

        return false;
    }
}
