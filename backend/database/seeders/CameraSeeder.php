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
            [
                'name' => 'KAMERA IN 1',
                'url' => 'http://192.168.1.108/cgi-bin/snapshot.cgi',
                'user' => 'admin',
                'pass' => 'admin123'
            ],
            [
                'name' => 'KAMERA IN 2',
                'url' =>
                'http://192.168.1.109/cgi-bin/snapshot.cgi',
                'user' => 'admin',
                'pass' => 'admin123'
            ],
            [
                'name' => 'KAMERA OUT 1',
                'url' =>
                'http://192.168.1.110/cgi-bin/snapshot.cgi',
                'user' => 'admin',
                'pass' => 'admin123'
            ],
            [
                'name' => 'KAMERA OUT 2',
                'url' => 'http://192.168.1.111/cgi-bin/snapshot.cgi',
                'user' => 'admin',
                'pass' => 'admin123'
            ],
        ]);
    }
}
