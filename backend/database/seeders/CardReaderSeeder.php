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
            [
                'name' => 'READER IN',
                'prefix' => 'W',
                'access_gate_id' => 1,
                'type' => 'IN',
                'camera_ids' => "[1, 2]"
            ],
            [
                'name' => 'READER OUT',
                'prefix' => 'X',
                'access_gate_id' => 1,
                'type' => 'OUT',
                'camera_ids' => "[3, 4]"
            ],
        ]);
    }
}
