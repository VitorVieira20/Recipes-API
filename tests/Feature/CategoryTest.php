<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_list_categories()
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson(route('categories.index'));

        $response->assertOk()
            ->assertJsonCount(3);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_list_categories_not_found()
    {
        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Categories not found']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_create_category()
    {
        $token = env('API_SECRET_TOKEN');

        $payload = [
            'name' => 'Sobremesa',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('categories.store'), $payload);

        $response->assertStatus(201);
        $response->assertCreated()
            ->assertJsonFragment(['message' => 'Category created successfully.']);
        $response->assertJsonPath('data.name', 'Sobremesa');
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_create_category_with_invalid_data()
    {
        $token = env('API_SECRET_TOKEN');

        $payload = [];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('categories.store'), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_create_category_due_authorization()
    {
        $payload = [
            'name' => 'Sobremesa',
        ];

        $response = $this->postJson(route('categories.store'), $payload);

        $response->assertStatus(status: 401);
        $response->assertJsonFragment(['message' => 'Unauthorized']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_update_recipe()
    {
        $token = env('API_SECRET_TOKEN');

        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.index', $category->id));

        $response->assertOk()
            ->assertJsonPath('data.name', fn($value) => $value !== 'Sobremsa');

        $payload = [
            'name' => 'Sobremsa',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('categories.update', $category->id), $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Category updated successfully.'])
            ->assertJsonPath('data.name', 'Sobremsa');
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_update_category_with_invalid_data()
    {
        $token = env('API_SECRET_TOKEN');

        $category = Category::factory()->create();

        $payload = [
            'name' => [],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('categories.update', $category->id), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name',
        ]);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_update_category_due_authorization()
    {
        $category = Category::factory()->create();

        $response = $this->getJson(route('categories.index', $category->id));

        $response->assertOk()
            ->assertJsonPath('data.name', fn($value) => $value !== 'Sobremesa');

        $payload = [
            'name' => 'Sobremesa',
        ];

        $response = $this->putJson(route('categories.update', $category->id), $payload);

        $response->assertStatus(status: 401);
        $response->assertJsonFragment(['message' => 'Unauthorized']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_can_delete_category()
    {
        $token = env('API_SECRET_TOKEN');

        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('categories.destroy', $category->id));

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Category deleted successfully.']);
    }


    #[\PHPUnit\Framework\Attributes\Test]
    public function test_cannot_delete_category_due_authorization()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson(route('categories.destroy', $category->id));

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Unauthorized']);
    }
}
