<?php

namespace Database\Seeders;

use App\Models\AccessGate;
use Illuminate\Database\Seeder;

class AccessGateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccessGate::insert([
            ['name' => 'MAIN GATE', 'device' => '/dev/ttyS0'],
        ]);
    }
}
