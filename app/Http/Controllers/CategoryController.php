<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}


    // Find All Categories
    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="List all categories",
     *     @OA\Response(response=200, description="List of categories"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function index()
    {
        $categories = $this->categoryService->findAll();

        if (!$categories || $categories->isEmpty()) {
            return response()->json(['message' => 'Categories not found'], 404);
        }

        return response()->json($categories, 200);
    }


    // Create Category
    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Sobremesas"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateCategoryRequest $createCategoryRequest)
    {
        $category = $this->categoryService->store($createCategoryRequest);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }


    // Update Category
    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update an existing category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Novo nome"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateCategoryRequest $updateCategoryRequest, $id)
    {
        $category = $this->categoryService->update($updateCategoryRequest, $id);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category,
        ], 200);
    }


    // Delete Category
    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category deleted"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function destroy($id)
    {
        $category = $this->categoryService->destroy($id);

        return response()->json([
            'message' => 'Category deleted successfully.',
            'data' => $category,
        ], 200);
    }
}
