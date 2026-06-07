<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'guard_name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function givePermission($permission)
    {
        $permission = Permission::where('name', $permission)->firstOrFail();
        $this->permissions()->attach($permission);
    }

    public function revokePermission($permission)
    {
        $permission = Permission::where('name', $permission)->firstOrFail();
        $this->permissions()->detach($permission);
    }
}
