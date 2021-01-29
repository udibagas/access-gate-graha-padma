<?php

namespace Database\Seeders;

use App\Models\Camera;
use Illuminate\Database\Seeder;

class CameraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Camera::insert([
            ['name' => 'KAMERA 1', 'url' => 'http://www.aaa.com', 'user' => 'admin', 'pass' => 'admin123'],
            ['name' => 'KAMERA 2', 'url' => 'http://www.aaa.com', 'user' => 'admin', 'pass' => 'admin123'],
        ]);
    }
}
