<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentationController extends Controller
{
    public function index()
    {
        $recipesRoutes = [
            [
                'method' => 'GET',
                'path' => '/api/recipes',
                'name' => 'recipes.index',
                'description' => 'Lista todas as receitas disponíveis.',
            ],
            [
                'method' => 'GET',
                'path' => '/api/recipes/search',
                'name' => 'recipes.search',
                'description' => 'Pesquisa receitas com base num termo.',
                'query' => [
                    [
                        'name' => 'q',
                        'type' => 'string',
                        'required' => true,
                        'description' => 'Termo de pesquisa.',
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/recipes/category/{categoryId}',
                'name' => 'recipes.byCategory',
                'description' => 'Lista receitas por categoria.',
                'params' => [
                    [
                        'name' => 'categoryId',
                        'type' => 'int',
                        'required' => true,
                        'description' => 'ID da categoria.',
                    ],
                ],
            ],
            [
                'method' => 'GET',
                'path' => '/api/recipes/{id}',
                'name' => 'recipes.show',
                'description' => 'Mostra os detalhes de uma receita.',
                'params' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                        'required' => true,
                        'description' => 'ID da receita.',
                    ],
                ],
            ],
            [
                'method' => 'POST',
                'path' => '/api/recipes',
                'name' => 'recipes.store',
                'description' => 'Cria uma nova receita. Requer autenticação.',
                'auth' => true,
                'bodyParams' => [
                    [
                        'name' => 'name',
                        'description' => 'Nome da receita',
                        'required' => true,
                        'type' => 'string',
                        'maxLength' => 255,
                    ],
                    [
                        'name' => 'image',
                        'description' => 'URL da imagem da receita',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'url',
                        'maxLength' => 2048,
                    ],
                    [
                        'name' => 'description',
                        'description' => 'Descrição opcional da receita',
                        'required' => false,
                        'type' => 'string',
                    ],
                    [
                        'name' => 'ingredients',
                        'description' => 'Lista de ingredientes (mínimo 1). Cada ingrediente é uma string com até 255 caracteres.',
                        'required' => true,
                        'type' => 'array',
                        'minItems' => 1,
                        'items' => [
                            'type' => 'string',
                            'maxLength' => 255,
                        ],
                    ],
                    [
                        'name' => 'instructions',
                        'description' => 'Lista de instruções (mínimo 1). Cada instrução é uma string com até 255 caracteres.',
                        'required' => true,
                        'type' => 'array',
                        'minItems' => 1,
                        'items' => [
                            'type' => 'string',
                            'maxLength' => 255,
                        ],
                    ],
                    [
                        'name' => 'category_id',
                        'description' => 'ID da categoria válida onde a receita pertence',
                        'required' => true,
                        'type' => 'integer',
                        'exists' => 'categories,id',
                    ],
                ],
            ],
            [
                'method' => 'PUT',
                'path' => '/api/recipes/{id}',
                'name' => 'recipes.update',
                'description' => 'Atualiza uma receita existente. Requer autenticação.',
                'auth' => true,
                'params' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                        'required' => true,
                        'description' => 'ID da receita.',
                    ],
                ],
                'bodyParams' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Nome da receita (máx 255 caracteres)',
                    ],
                    [
                        'name' => 'image',
                        'type' => 'string (URL)',
                        'required' => false,
                        'description' => 'URL da imagem (máx 2048 caracteres)',
                    ],
                    [
                        'name' => 'description',
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Descrição da receita (opcional)',
                    ],
                    [
                        'name' => 'ingredients',
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Lista de ingredientes (mínimo 1)',
                    ],
                    [
                        'name' => 'instructions',
                        'type' => 'array',
                        'required' => false,
                        'description' => 'Lista de instruções (mínimo 1)',
                    ],
                    [
                        'name' => 'category_id',
                        'type' => 'integer',
                        'required' => false,
                        'description' => 'ID da categoria válida',
                    ],
                ],
            ],
            [
                'method' => 'DELETE',
                'path' => '/api/recipes/{id}',
                'name' => 'recipes.destroy',
                'description' => 'Apaga uma receita. Requer autenticação.',
                'auth' => true,
                'params' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                        'required' => true,
                        'description' => 'ID da receita.',
                    ],
                ],
            ],
        ];

        $categoryRoutes = [
            [
                'method' => 'GET',
                'path' => '/api/categories',
                'name' => 'categories.index',
                'description' => 'Lista todas as categorias de receitas.',
            ],
            [
                'method' => 'POST',
                'path' => '/api/categories',
                'name' => 'categories.store',
                'description' => 'Cria uma nova categoria. Requer autenticação.',
                'auth' => true,
                'bodyParams' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'required' => true,
                        'maxLength' => 255,
                        'description' => 'Nome da categoria.',
                    ],
                ],
            ],
            [
                'method' => 'PUT',
                'path' => '/api/categories/{id}',
                'name' => 'categories.update',
                'description' => 'Atualiza uma categoria existente. Requer autenticação.',
                'auth' => true,
                'params' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                        'required' => true,
                        'description' => 'ID da categoria.',
                    ],
                ],
                'bodyParams' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'required' => false,
                        'maxLength' => 255,
                        'description' => 'Novo nome da categoria.',
                    ],
                ],
            ],
            [
                'method' => 'DELETE',
                'path' => '/api/categories/{id}',
                'name' => 'categories.destroy',
                'description' => 'Remove uma categoria existente. Requer autenticação.',
                'auth' => true,
                'params' => [
                    [
                        'name' => 'id',
                        'type' => 'int',
                        'required' => true,
                        'description' => 'ID da categoria.',
                    ],
                ],
            ],
        ];

        return Inertia::render('Documentation', [
            'recipesRoutes' => $recipesRoutes,
            'categoryRoutes' => $categoryRoutes,
        ]);
    }
}
