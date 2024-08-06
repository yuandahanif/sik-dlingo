<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penduduk>
 */
class PendudukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $generator = \Faker\Factory::create();
        // $populator = new \Faker\ORM\Propel\Populator($generator);
        // $populator->addEntity('Author', 5);
        // $populator->addEntity('Book', 10);
        // $insertedPKs = $populator->execute();
        return [
            'name' => fake()->name(),
            'nik' => fake()->unique()->randomNumber(5, false),
        ];
    }
}
