<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            ['name' => 'admin', 'email' => 'admin@admin.com', 'password' => bcrypt('Z5gNKDTz'), 'role' => 1],
            ['name' => 'part', 'email' => 'part@part.com', 'password' => bcrypt('K8wXRYvM'), 'role' => 11],
            // 他のプロジェクトデータをここに追加
        ]);
    }
}
