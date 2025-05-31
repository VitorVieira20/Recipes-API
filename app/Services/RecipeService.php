<?php

namespace App\Services;

use App\Http\Requests\CreateRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use App\Repositories\RecipeRepository;
use Illuminate\Database\Eloquent\Collection;

class RecipeService
{
    public function __construct(protected RecipeRepository $recipeRepository) {}


    // Find All
    public function findAll()
    {
        return $this->formatRecipe($this->recipeRepository->findAll());
    }


    // Find by Id
    public function findById(int $id)
    {
        return $this->formatRecipe($this->recipeRepository->findById($id));
    }


    // Find by Category Id
    public function findByCategoryId(int $categoryId)
    {
        return $this->formatRecipe($this->recipeRepository->findByCategoryId($categoryId));
    }


    // Find by Search Term (Name, Category or and Ingredients)
    public function findBySearchTerm($request)
    {
        return $this->formatRecipe($this->recipeRepository->findBySearchTerm($request));
    }


    // Create Recipe
    public function store(CreateRecipeRequest $createRecipeRequest)
    {
        $validated = $createRecipeRequest->validated();

        return $this->recipeRepository->store([
            'name' => $validated['name'],
            'image' => $validated['image'],
            'description' => $validated['description'],
            'ingredients' => $validated['ingredients'],
            'instructions' => $validated['instructions'],
            'category_id' => $validated['category_id']
        ]);
    }


    // Update Recipe
    public function update(UpdateRecipeRequest $request, int $id)
    {
        $validated = $request->validated();

        return $this->recipeRepository->update($validated, $id);
    }


    // Delete Recipe
    public function destroy(int $id)
    {
        return $this->recipeRepository->destroy($id);
    }


    // Format Recipe
    private function formatRecipe($recipes)
    {
        if ($recipes instanceof Collection) {
            return $recipes->map(fn($recipe) => $this->mapRecipe($recipe));
        }

        if ($recipes) {
            return $this->mapRecipe($recipes);
        }

        return null;
    }


    // Map Recipe
    private function mapRecipe($recipe)
    {
        return [
            'id' => $recipe->id,
            'name' => $recipe->name,
            'image' => $recipe->image,
            'description' => $recipe->description,
            'ingredients' => $recipe->ingredients,
            'instructions' => $recipe->instructions,
            'category' => $recipe->category->name ?? null,
        ];
    }
}
