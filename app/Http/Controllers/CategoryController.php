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
    public function index()
    {
        $categories = $this->categoryService->findAll();

        if (!$categories || $categories->isEmpty()) {
            return response()->json(['message' => 'No categories found'], 404);
        }

        return response()->json($categories, 200);
    }


    // Create Category
    public function store(CreateCategoryRequest $createCategoryRequest)
    {
        $category = $this->categoryService->store($createCategoryRequest);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }


    // Update Category
    public function update(UpdateCategoryRequest $updateCategoryRequest, $id)
    {
        $category = $this->categoryService->update($updateCategoryRequest, $id);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category,
        ], 200);
    }


    // Delete Category
    public function destroy($id)
    {
        $category = $this->categoryService->destroy($id);

        return response()->json([
            'message' => 'Category deleted successfully.',
            'data' => $category,
        ], 200);
    }
}
