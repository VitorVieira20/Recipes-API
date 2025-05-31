<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Category;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::where('name', 'tartes')->first();

        Recipe::create([
            'name' => 'Tarte de Amêndoa',
            'image' => 'https://www.receitasdeportugal.com/wp-content/uploads/2018/01/tarte-amendoa.jpg',
            'description' => 'Uma deliciosa tarte com cobertura crocante de amêndoas caramelizadas. A base é leve e fofa, perfeita para qualquer ocasião especial.',
            'ingredients' => [
                '4 Ovos',
                '200g de Açúcar',
                '100g de Farinha',
                '1 Colher de Chá de Fermento',
                '100g de Manteiga derretida',
                'Cobertura: 100g de Amêndoas laminadas',
                '100g de Açúcar',
                '100g de Manteiga',
                '3 Colheres de Sopa de Leite',
            ],
            'instructions' => [
                'Pré-aquece o forno a 180ºC.',
                'Bate os ovos com o açúcar até ficar fofo.',
                'Adiciona a farinha, o fermento e a manteiga derretida. Mistura bem.',
                'Leva ao forno numa forma forrada com papel vegetal por cerca de 20 minutos.',
                'Enquanto o bolo coze, prepara a cobertura: leva ao lume a manteiga, açúcar, leite e amêndoas até ferver e engrossar ligeiramente.',
                'Retira o bolo do forno, espalha a cobertura por cima e leva novamente ao forno até dourar (cerca de 10 minutos).',
            ],
            'category_id' => $category->id,
        ]);
    }
}
