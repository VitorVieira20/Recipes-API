<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function test_cannot_list_recipes_not_found()
    {
        $response = $this->getJson('/api/recipes');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Recipes not found']);
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
    public function test_cannot_show_recipe_not_found()
    {
        $response = $this->getJson('/api/recipes/1000');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Recipe not found']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_show_recipes_by_category()
    {
        $category_one = Category::factory()->create();
        $category_two = Category::factory()->create();

        Recipe::factory()->count(3)->create(['category_id' => $category_one->id]);
        Recipe::factory()->count(2)->create(['category_id' => $category_two->id]);

        $response = $this->getJson("/api/recipes/category/$category_one->id");

        $response->assertOk()
            ->assertJsonCount(3);

        $response = $this->getJson("/api/recipes/category/$category_two->id");

        $response->assertOk()
            ->assertJsonCount(2);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_show_recipes_by_search_term()
    {
        $category = Category::factory()->create(['name' => 'Laranja']);

        Recipe::factory()->create(['name' => 'Bolo de Laranja']);
        Recipe::factory()->create(['category_id' => $category->id]);
        Recipe::factory()->create(['ingredients' => ['2 Ovos', '5 Laranjas']]);

        $response = $this->getJson("/api/recipes/search?q=Laran");

        $response->assertOk()
            ->assertJsonCount(2);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_show_recipes_by_search_term_not_found()
    {
        $response = $this->getJson("/api/recipes/search?q=Laran");

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Recipes not found']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_show_recipes_by_category_not_found()
    {
        $response = $this->getJson('/api/recipes/category/1000');

        $response->assertStatus(status: 404)
            ->assertJsonFragment(['message' => 'Recipes not found']);
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
            'category_id' => Category::factory()->create()->id,
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


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_create_recipe_with_invalid_data()
    {
        $token = env('API_SECRET_TOKEN');

        $payload = [
            // 'name' => 'faltando',
            'image' => 'not-a-url',
            'ingredients' => 'massa',
            'instructions' => [],
            'category_id' => 9999,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('recipes.store'), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name',
            'image',
            'ingredients',
            'instructions',
            'category_id',
        ]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_create_recipe_due_authorization()
    {
        $payload = [
            'name' => 'Massa à Bolonhesa',
            'image' => 'https://example.com/image.jpg',
            'description' => 'Deliciosa receita italiana.',
            'ingredients' => ['massa', 'carne', 'molho de tomate'],
            'instructions' => ['cozer a massa', 'preparar o molho'],
            'category_id' => Category::factory()->create()->id,
        ];

        $response = $this->postJson(route('recipes.store'), $payload);

        $response->assertStatus(status: 401);
        $response->assertJsonFragment(['message' => 'Unauthorized']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_update_recipe()
    {
        $token = env('API_SECRET_TOKEN');

        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/api/recipes/$recipe->id");

        $response->assertOk()
            ->assertJsonPath('name', fn($value) => $value !== 'Massa à Bolonhesa');

        $payload = [
            'name' => 'Massa à Bolonhesa',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('recipes.update', $recipe->id), $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Recipe updated successfully.'])
            ->assertJsonPath('data.name', 'Massa à Bolonhesa');
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_update_recipe_with_invalid_data()
    {
        $token = env('API_SECRET_TOKEN');

        $recipe = Recipe::factory()->create();

        $payload = [
            'instructions' => [],
            'category_id' => 9999,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('recipes.update', $recipe->id), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'instructions',
            'category_id',
        ]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_update_recipe_due_authorization()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->getJson("/api/recipes/$recipe->id");

        $response->assertOk()
            ->assertJsonPath('name', fn($value) => $value !== 'Massa à Bolonhesa');

        $payload = [
            'name' => 'Massa à Bolonhesa',
        ];

        $response = $this->putJson(route('recipes.update', $recipe->id), $payload);

        $response->assertStatus(status: 401);
        $response->assertJsonFragment(['message' => 'Unauthorized']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_delete_recipe()
    {
        $token = env('API_SECRET_TOKEN');

        $recipe = Recipe::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('recipes.destroy', $recipe->id));

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Recipe deleted successfully.']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_delete_recipe_due_authorization()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->deleteJson(route('recipes.destroy', $recipe->id));

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthorized']);
    }
}
