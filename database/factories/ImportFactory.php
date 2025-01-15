<?php

namespace Database\Factories;

use App\Models\Import;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ImportFactory extends Factory
{
    protected $model = Import::class;

    public function definition(): array
    {
        return [
            'file_name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
