<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    // Find All Categories
    public function findAll()
    {
        return Category::with('recipes')->withCount('recipes')->get();
    }


    // Create Category
    public function store(array $data)
    {
        return Category::create($data);
    }


    // Update Category
    public function update(array $data, int $id)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category->fresh();
    }


    // Delete Category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return $category;
    }
}