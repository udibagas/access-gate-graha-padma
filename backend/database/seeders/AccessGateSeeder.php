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
            ['name' => 'GATE IN', 'type' => 'IN', 'host' => '192.168.1.100', 'cameras' => json_encode([1, 2])],
            ['name' => 'GATE OUT', 'type' => 'OUT', 'host' => '192.168.1.101', 'cameras' => json_encode([1, 2])],
        ]);
    }
}
