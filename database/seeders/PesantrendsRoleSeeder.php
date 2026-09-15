<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PesantrendsRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $parentRole = Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);

        // Assign admin role
        $admins = User::whereIn('email', ['admin@pesantrends.id', 'admin@gmail.com'])->get();
        foreach ($admins as $admin) {
            if (! $admin->hasRole('admin')) {
                $admin->assignRole($adminRole);
            }
            if ($admin->role !== 'admin') {
                $admin->update(['role' => 'admin']);
            }
        }

        // Assign parent role to demo user and others
        $parents = User::whereNotIn('email', ['admin@pesantrends.id', 'admin@gmail.com'])->get();
        foreach ($parents as $parent) {
            if (! $parent->hasRole('admin') && ! $parent->hasRole('parent')) {
                $parent->assignRole($parentRole);
            }
            if ($parent->role !== 'admin' && $parent->role !== 'parent') {
                $parent->update(['role' => 'parent']);
            }
        }
    }
}
