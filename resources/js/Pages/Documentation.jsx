import React, { useState } from 'react';
import GetEndpoint from '../Components/GetEndpoint';
import PostEndpoint from '../Components/PostEndpoint';
import PutEndpoint from '../Components/PutEndpoint';
import DeleteEndpoint from '../Components/DeleteEndpoint';

const initialRoutes = [
    {
        method: 'GET',
        path: '/api/recipes',
        name: 'recipes.index',
        description: 'Lista todas as receitas disponíveis.',
    },
    {
        method: 'GET',
        path: '/api/recipes/search',
        name: 'recipes.search',
        description: 'Pesquisa receitas com base num termo.',
        query: [
            { name: 'q', type: 'string', required: true, description: 'Termo de pesquisa.' },
        ],
    },
    {
        method: 'GET',
        path: '/api/recipes/category/{categoryId}',
        name: 'recipes.byCategory',
        description: 'Lista receitas por categoria.',
        params: [
            { name: 'categoryId', type: 'int', required: true, description: 'ID da categoria.' },
        ],
    },
    {
        method: 'GET',
        path: '/api/recipes/{id}',
        name: 'recipes.show',
        description: 'Mostra os detalhes de uma receita.',
        params: [
            { name: 'id', type: 'int', required: true, description: 'ID da receita.' },
        ],
    },
    {
        method: 'POST',
        path: '/api/recipes',
        name: 'recipes.store',
        description: 'Cria uma nova receita. Requer autenticação.',
        auth: true,
        bodyParams: [
            {
                name: 'name',
                description: 'Nome da receita',
                required: true,
                type: 'string',
                maxLength: 255,
            },
            {
                name: 'image',
                description: 'URL da imagem da receita',
                required: true,
                type: 'string',
                format: 'url',
                maxLength: 2048,
            },
            {
                name: 'description',
                description: 'Descrição opcional da receita',
                required: false,
                type: 'string',
            },
            {
                name: 'ingredients',
                description: 'Lista de ingredientes (mínimo 1). Cada ingrediente é uma string com até 255 caracteres.',
                required: true,
                type: 'array',
                minItems: 1,
                items: {
                    type: 'string',
                    maxLength: 255,
                },
            },
            {
                name: 'instructions',
                description: 'Lista de instruções (mínimo 1). Cada instrução é uma string com até 255 caracteres.',
                required: true,
                type: 'array',
                minItems: 1,
                items: {
                    type: 'string',
                    maxLength: 255,
                },
            },
            {
                name: 'category_id',
                description: 'ID da categoria válida onde a receita pertence',
                required: true,
                type: 'integer',
                exists: 'categories,id',
            },
        ]
    },
    {
        method: 'PUT',
        path: '/api/recipes/{id}',
        name: 'recipes.update',
        description: 'Atualiza uma receita existente. Requer autenticação.',
        auth: true,
        params: [
            { name: 'id', type: 'int', required: true, description: 'ID da receita.' },
        ],
        bodyParams: [
            { name: 'name', type: 'string', required: false, description: 'Nome da receita (máx 255 caracteres)' },
            { name: 'image', type: 'string (URL)', required: false, description: 'URL da imagem (máx 2048 caracteres)' },
            { name: 'description', type: 'string', required: false, description: 'Descrição da receita (opcional)' },
            { name: 'ingredients', type: 'array[string]', required: false, description: 'Lista de ingredientes (mínimo 1)' },
            { name: 'instructions', type: 'array[string]', required: false, description: 'Lista de instruções (mínimo 1)' },
            { name: 'category_id', type: 'integer', required: false, description: 'ID da categoria válida' },
        ]
    },
    {
        method: 'DELETE',
        path: '/api/recipes/{id}',
        name: 'recipes.destroy',
        description: 'Apaga uma receita. Requer autenticação.',
        auth: true,
        params: [
            { name: 'id', type: 'int', required: true, description: 'ID da receita.' },
        ],
    },
];

export default function DocumentationPage() {
    return (
        <div className="w-full mx-auto p-6 space-y-6">
            <h1 className="text-3xl text-center font-bold mb-10">Recipes API</h1>

            <div className='grid grid-cols-1 md:grid-cols-2 gap-10'>
                <div>
                    <h1 className="text-3xl text-center font-bold mb-4">Recipes</h1>
                    {initialRoutes.map((route, index) => {
                        if (route.method === "GET") {
                            return (
                                <div key={index}>
                                    <GetEndpoint route={route} />
                                </div>
                            )
                        } else if (route.method === "POST") {
                            return (
                                <div key={index}>
                                    <PostEndpoint route={route} />
                                </div>
                            );
                        } else if (route.method === "PUT") {
                            return (
                                <div key={index}>
                                    <PutEndpoint route={route} />
                                </div>
                            );
                        } else if (route.method === "DELETE") {
                            return (
                                <div key={index}>
                                    <DeleteEndpoint route={route} />
                                </div>
                            );
                        }
                    })}
                </div>

                <div className='w-full'>
                    <h1 className="text-3xl text-center font-bold mb-4">Categories</h1>
                    {initialRoutes.map((route, index) => {
                        if (route.method === "GET") {
                            return (
                                <div key={index}>
                                    <GetEndpoint route={route} />
                                </div>
                            )
                        } else if (route.method === "POST") {
                            return (
                                <div key={index}>
                                    <PostEndpoint route={route} />
                                </div>
                            );
                        } else if (route.method === "PUT") {
                            return (
                                <div key={index}>
                                    <PutEndpoint route={route} />
                                </div>
                            );
                        } else if (route.method === "DELETE") {
                            return (
                                <div key={index}>
                                    <DeleteEndpoint route={route} />
                                </div>
                            );
                        }
                    })}
                </div>
            </div>
        </div>
    );
}

/**
<div key={index} className="border border-gray-300 rounded-lg p-4 shadow-sm bg-white">
                    <div className="flex items-center justify-between">
                        <span className={`text-sm px-2 py-1 rounded font-semibold
                            ${route.method === 'GET' ? 'bg-blue-100 text-blue-800' :
                                route.method === 'POST' ? 'bg-green-100 text-green-800' :
                                    route.method === 'PUT' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800'}`}>
                            {route.method}
                        </span>

                        {route.auth && (
                            <span className="text-xs text-red-500">🔒 Autenticação necessária</span>
                        )}
                    </div>

                    <p className="mt-2 text-sm text-gray-600 font-mono">{route.path}</p>
                    <p className="text-gray-800 mt-1">{route.description}</p>

                    {route.params && (
                        <div className="mt-3 text-sm">
                            <strong>Parâmetros:</strong>
                            <ul className="list-disc list-inside">
                                {route.params.map((param, i) => (
                                    <li key={i}>
                                        <span className="font-mono">{param.name}</span> ({param.type}) - {param.description}
                                        {param.required && <span className="text-red-600"> (obrigatório)</span>}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    )}

                    <button
                        onClick={() => callApi(route.path.replace(/\{.*?\}/g, '1'))}
                        className="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Testar API
                    </button>

                    {responses[route.path.replace(/\{.*?\}/g, '1')] && (
                        <pre className="mt-3 bg-gray-100 p-3 rounded text-sm overflow-x-auto">
                            {JSON.stringify(responses[route.path.replace(/\{.*?\}/g, '1')], null, 2)}
                        </pre>
                    )}
                </div>
 */