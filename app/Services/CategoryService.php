<?php

namespace App\Services;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(protected CategoryRepository $categoryRepository) {}


    // Find All Categories
    public function findAll()
    {
        return $this->categoryRepository->findAll()
            ->map(function ($categorie) {
                return [
                    'id' => $categorie->id,
                    'name' => $categorie->name,
                    'image' => $categorie->recipes[0]['image'] ?? null,
                    'count' => $categorie->recipes_count,
                ];
            });
    }


    // Create Category
    public function store(CreateCategoryRequest $createCategoryRequest)
    {
        $validated = $createCategoryRequest->validated();

        return $this->categoryRepository->store([
            'name' => $validated['name']
        ]);
    }


    // Update Category
    public function update(UpdateCategoryRequest $updateCategoryRequest, int $id)
    {
        $validated = $updateCategoryRequest->validated();

        return $this->categoryRepository->update($validated, $id);
    }


    // Delete Category
    public function destroy(int $id)
    {
        return $this->categoryRepository->destroy($id);
    }
}
