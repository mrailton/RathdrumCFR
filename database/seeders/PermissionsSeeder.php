<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public array $permissions = [
        ['name' => 'defib.list'],
        ['name' => 'defib.view'],
        ['name' => 'defib.create'],
        ['name' => 'defib.update'],
        ['name' => 'defib.delete'],
        ['name' => 'defib.inspect'],
        ['name' => 'defib.note'],
        ['name' => 'member.list'],
        ['name' => 'member.view'],
        ['name' => 'member.create'],
        ['name' => 'member.update'],
        ['name' => 'member.delete'],
        ['name' => 'member.note'],
        ['name' => 'user.list'],
        ['name' => 'user.view'],
        ['name' => 'user.update'],
        ['name' => 'user.invite'],
        ['name' => 'user.delete'],
    ];


    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
