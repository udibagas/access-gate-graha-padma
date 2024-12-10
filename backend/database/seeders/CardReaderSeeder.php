<?php

namespace Database\Seeders;

use App\Models\CardReader;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardReaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CardReader::insert([
            ['name' => 'MAIN GATE', 'device' => '/dev/ttyS0', 'access_gate_id' => 1],
        ]);
    }
}
