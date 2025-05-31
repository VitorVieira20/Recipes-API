<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'image' => $this->faker->imageUrl(640, 480, 'food', true),
            'description' => $this->faker->paragraph(),
            'ingredients' => [
                $this->faker->word(),
                $this->faker->word(),
                $this->faker->word(),
            ],
            'instructions' => [
                $this->faker->sentence(),
                $this->faker->sentence(),
                $this->faker->sentence(),
            ],
            'category_id' => Category::factory(),
        ];
    }
}
