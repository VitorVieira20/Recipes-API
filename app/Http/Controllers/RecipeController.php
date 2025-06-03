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
    /**
     * @OA\Get(
     *     path="/recipes",
     *     tags={"Recipes"},
     *     summary="List all recipes",
     *     @OA\Response(response=200, description="List of recipes"),
     *     @OA\Response(response=404, description="Recipes not found")
     * )
     */
    public function index()
    {
        $recipes = $this->recipeService->findAll();

        if (!$recipes || $recipes->isEmpty()) {
            return response()->json(['message' => 'Recipes not found'], 404);
        }

        return response()->json($recipes, 200);
    }


    // Find Recipe by Id
    /**
     * @OA\Get(
     *     path="/recipes/{id}",
     *     tags={"Recipes"},
     *     summary="Get a recipe by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the recipe",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Recipe found"),
     *     @OA\Response(response=404, description="Recipe not found")
     * )
     */
    public function show(int $id)
    {
        $recipe = $this->recipeService->findById($id);

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        return response()->json($recipe, 200);
    }


    // Find Recipe by Category
    /**
     * @OA\Get(
     *     path="/recipes/category/{categoryId}",
     *     tags={"Recipes"},
     *     summary="Get recipes by category ID",
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Recipes found"),
     *     @OA\Response(response=404, description="Recipes not found")
     * )
     */
    public function byCategory(int $categoryId)
    {
        $recipes = $this->recipeService->findByCategoryId($categoryId);

        if (!$recipes || $recipes->isEmpty()) {
            return response()->json(['message' => 'Recipes not found'], 404);
        }

        return response()->json($recipes, 200);
    }


    // Find Recipes by Name, Category or Ingredients
    /**
     * @OA\Get(
     *     path="/recipes/search",
     *     tags={"Recipes"},
     *     summary="Search recipes by name, category or ingredients",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="Search term",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Search results"),
     *     @OA\Response(response=404, description="No recipes found")
     * )
     */
    public function search(Request $request)
    {
        $recipes = $this->recipeService->findBySearchTerm($request);

        if (!$recipes || $recipes->isEmpty()) {
            return response()->json(['message' => 'Recipes not found'], 404);
        }

        return response()->json($recipes, 200);
    }


    // Create Recipe
    /**
     * @OA\Post(
     *     path="/recipes",
     *     tags={"Recipes"},
     *     summary="Create a new recipe",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","image","ingredients","instructions","category_id"},
     *             @OA\Property(property="name", type="string", example="Bolo de Laranja"),
     *             @OA\Property(property="image", type="string", format="url", example="https://example.com/image.jpg"),
     *             @OA\Property(property="description", type="string", example="Delicioso bolo cítrico"),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="instructions", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Recipe created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateRecipeRequest $createRecipeRequest)
    {
        $recipe = $this->recipeService->store($createRecipeRequest);

        return response()->json([
            'message' => 'Recipe created successfully.',
            'data' => $recipe,
        ], 201);
    }


    // Update Recipe
    /**
     * @OA\Put(
     *     path="/recipes/{id}",
     *     tags={"Recipes"},
     *     summary="Update an existing recipe",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the recipe to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Novo nome"),
     *             @OA\Property(property="image", type="string", format="url", example="https://example.com/image.jpg"),
     *             @OA\Property(property="description", type="string", example="Nova descrição"),
     *             @OA\Property(property="ingredients", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="instructions", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Recipe updated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateRecipeRequest $updateRecipeRequest, $id)
    {
        $recipe = $this->recipeService->update($updateRecipeRequest, $id);

        return response()->json([
            'message' => 'Recipe updated successfully.',
            'data' => $recipe,
        ], 200);
    }


    // Delete Recipe
    /**
     * @OA\Delete(
     *     path="/recipes/{id}",
     *     tags={"Recipes"},
     *     summary="Delete a recipe",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the recipe to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Recipe deleted"),
     *     @OA\Response(response=404, description="Recipe not found")
     * )
     */
    public function destroy($id)
    {
        $recipe = $this->recipeService->destroy($id);

        return response()->json([
            'message' => 'Recipe deleted successfully.',
            'data' => $recipe,
        ], 200);
    }
}