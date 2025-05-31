<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Services\RecipeService;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(protected RecipeService $recipeService) {}


    // Find All Recipes
    public function index()
    {
        $recipes = $this->recipeService->findAll();

        if (!$recipes || $recipes->isEmpty()) {
            return response()->json(['message' => 'No recipes found'], 404);
        }

        return response()->json($recipes, 200);
    }


    // Find Recipe by Id
    public function show(int $id)
    {
        $recipe = $this->recipeService->findById($id);

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        return response()->json($recipe, 200);
    }


    // Find Recipe by Category
    public function byCategory(int $categoryId)
    {
        $recipes = $this->recipeService->findByCategoryId($categoryId);

        if (!$recipes || $recipes->isEmpty()) {
            return response()->json(['message' => 'Recipes not found'], 404);
        }

        return response()->json($recipes, 200);
    }


    // Find Recipes by Name, Category or Ingredients
    public function search(Request $request)
    {
        $recipes = $this->recipeService->findBySearchTerm($request);

        if (!$recipes || $recipes->isEmpty()) {
            return response()->json(['message' => 'Recipes not found'], 404);
        }

        return response()->json($recipes, 200);
    }


    // Create Recipe
    public function store(CreateRecipeRequest $createRecipeRequest)
    {
        $recipe = $this->recipeService->store($createRecipeRequest);

        return response()->json([
            'message' => 'Recipe created successfully.',
            'data' => $recipe,
        ], 201);
    }


    // Update Recipe
    public function update(UpdateRecipeRequest $updateRecipeRequest, $id)
    {
        $recipe = $this->recipeService->update($updateRecipeRequest, $id);

        return response()->json([
            'message' => 'Recipe updated successfully.',
            'data' => $recipe,
        ], 200);
    }


    // Delete Recipe
    public function destroy($id)
    {
        $recipe = $this->recipeService->destroy($id);

        return response()->json([
            'message' => 'Recipe deleted successfully.',
            'data' => $recipe,
        ], 200);
    }
}
