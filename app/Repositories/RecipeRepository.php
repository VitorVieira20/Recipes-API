<?php

namespace App\Repositories;

use App\Models\Recipe;

class RecipeRepository
{
    // Find All
    public function findAll()
    {
        return Recipe::with('category')->get();
    }


    // Find by Id
    public function findById(int $id): ?Recipe
    {
        return Recipe::with('category')->where('id', $id)->first();
    }


    // Find by Category Id
    public function findByCategoryId(int $categoryId)
    {
        return Recipe::with('category')->where('category_id', $categoryId)->get();
    }


    // Find by Search Term (Name, Category or and Ingredients)
    public function findBySearchTerm($request)
    {
        $term = strtolower($request->query('q'));

        return Recipe::with('category')
            ->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
            ->orWhereHas(
                'category',
                fn($q) =>
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
            )
            ->orWhereRaw('LOWER(JSON_EXTRACT(ingredients, "$[*]")) LIKE ?', ["%{$term}%"])
            ->get();


        /* return Recipe::with('category')
            ->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
            ->orWhereHas(
                'category',
                fn($q) =>
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
            )
            ->orWhereRaw('ingredients::text ILIKE ?', ["%{$term}%"])
            ->get(); */
    }

    // Create Recipe
    public function store(array $data)
    {
        return Recipe::create($data);
    }


    // Update Recipe
    public function update(array $data, int $id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->update($data);
        return $recipe->fresh();
    }


    // Delete Recipe
    public function destroy(int $id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return $recipe;
    }
}
