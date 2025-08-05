<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ModelMaterial;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed material data
        $materials = [
            ['nama_material' => 'Soluble Wax', 'type' => 'Wax', 'processing_time' => 30],
            ['nama_material' => 'D2-49 Soluble Wax', 'type' => 'Wax', 'processing_time' => 45],
            ['nama_material' => 'D2-49 Body Wax', 'type' => 'Wax', 'processing_time' => 60],
            ['nama_material' => 'D2-49 Ring Wax', 'type' => 'Wax', 'processing_time' => 40],
            ['nama_material' => 'D2-49 WAX', 'type' => 'Wax', 'processing_time' => 50],
            ['nama_material' => 'D2-49', 'type' => 'Wax', 'processing_time' => 35],
        ];

        foreach ($materials as $material) {
            ModelMaterial::create($material);
        }
    }
}
