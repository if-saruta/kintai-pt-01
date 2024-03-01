<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::insert([
            ['client_id' => '1', 'name' => '休み'],
            ['client_id' => '1', 'name' => '打ち合わせ'],
            ['client_id' => '1', 'name' => '車検'],
            ['client_id' => '1', 'name' => '面接'],
            // 他のプロジェクトデータをここに追加
        ]);
    }
}
