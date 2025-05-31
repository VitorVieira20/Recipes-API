<?php

namespace Tests\Feature;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_list_recipes()
    {
        Recipe::factory()->count(3)->create();

        $response = $this->getJson('/api/recipes');

        $response->assertOk()
                 ->assertJsonCount(3);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_show_recipe()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/api/recipes/$recipe->id");

        $response->assertOk()
                 ->assertJsonFragment(['id' => $recipe->id]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_create_recipe()
    {
        $token = env('API_SECRET_TOKEN');

        $payload = [
            'name' => 'Massa à Bolonhesa',
            'image' => 'https://example.com/image.jpg',
            'description' => 'Deliciosa receita italiana.',
            'ingredients' => ['massa', 'carne', 'molho de tomate'],
            'instructions' => ['cozer a massa', 'preparar o molho'],
            'category_id' => \App\Models\Category::factory()->create()->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('recipes.store'), $payload);

        $response->assertStatus(201);
        $response->assertCreated()
                 ->assertJsonFragment(['message' => 'Recipe created successfully.']);
        $response->assertJsonPath('data.name', 'Massa à Bolonhesa');
    }

    /* public function test_can_update_recipe()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $updatedData = ['name' => 'Updated Recipe Name'];

        $response = $this->actingAs($user, 'api')->putJson(route('recipes.update', $recipe->id), $updatedData);

        $response->assertOk()
                 ->assertJsonFragment(['message' => 'Recipe updated successfully.']);
    }

    public function test_can_delete_recipe()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson(route('recipes.destroy', $recipe->id));

        $response->assertOk()
                 ->assertJsonFragment(['message' => 'Recipe deleted successfully.']);
    } */
}
