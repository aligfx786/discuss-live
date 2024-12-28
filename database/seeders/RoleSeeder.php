<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'moderator', 'description' => 'Content Moderator'],
            ['name' => 'editor', 'description' => 'Content Editor'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
