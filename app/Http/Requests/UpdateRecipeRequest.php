<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function wantsJson()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|url|max:2048',
            'description' => 'nullable|string',
            'ingredients' => 'sometimes|required|array|min:1',
            'ingredients.*' => 'required|string|max:255',
            'instructions' => 'sometimes|required|array|min:1',
            'instructions.*' => 'required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da receita é obrigatório.',
            'name.max' => 'O nome da receita não pode ter mais de 255 caracteres.',

            'image.required' => 'A imagem é obrigatória.',
            'image.url' => 'O campo imagem deve ser uma URL válida.',
            'image.max' => 'A URL da imagem não pode ter mais de 2048 caracteres.',

            'ingredients.required' => 'A lista de ingredientes é obrigatória.',
            'ingredients.array' => 'Os ingredientes devem estar num formato de lista.',
            'ingredients.min' => 'Deves fornecer pelo menos um ingrediente.',
            'ingredients.*.required' => 'Todos os ingredientes devem ser preenchidos.',
            'ingredients.*.string' => 'Cada ingrediente deve ser uma string.',
            'ingredients.*.max' => 'Cada ingrediente pode ter no máximo 255 caracteres.',

            'instructions.required' => 'A lista de instruções é obrigatória.',
            'instructions.array' => 'As instruções devem estar num formato de lista.',
            'instructions.min' => 'Deves fornecer pelo menos uma instrução.',
            'instructions.*.required' => 'Todas as instruções devem ser preenchidas.',
            'instructions.*.string' => 'Cada instrução deve ser uma string.',
            'instructions.*.max' => 'Cada instrução pode ter no máximo 255 caracteres.',

            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não é válida.',
        ];
    }
}
