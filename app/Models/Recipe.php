<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'ingredients',
        'instructions',
        'category_id',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
