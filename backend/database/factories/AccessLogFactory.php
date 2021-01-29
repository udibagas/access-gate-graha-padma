<?php

namespace Database\Factories;

use App\Models\AccessLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccessLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccessLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'access_gate_id' => rand(1, 2),
            'member_id' => rand(1, 100)
        ];
    }
}
