<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'version' => ['nullable', 'string', 'max:255'],
            'model_year' => ['nullable', 'string', 'max:4'],
            'build_year' => ['nullable', 'string', 'max:4'],

            // JSON/array fields
            'optionals' => ['nullable', 'array'],
            'photos' => ['nullable', 'array'],

            'doors' => ['nullable', 'string', 'max:50'],
            'board' => ['nullable', 'string', 'max:100'],
            'chassi' => ['nullable', 'string', 'max:100'],
            'transmission' => ['nullable', 'string', 'max:100'],
            'km' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'sold' => ['sometimes', 'boolean'],
            'category' => ['nullable', 'string', 'max:255'],
            'url_car' => ['nullable', 'url'],
            'price' => ['nullable', 'numeric'],
            'old_price' => ['nullable', 'numeric'],
            'color' => ['nullable', 'string', 'max:100'],
            'fuel' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'O tipo do veículo é obrigatório',
            'type.string' => 'O tipo deve ser um texto',
            'type.max' => 'O tipo não pode ter mais que :max caracteres',
            'brand.required' => 'A marca do veículo é obrigatória',
            'brand.string' => 'A marca deve ser um texto',
            'brand.max' => 'A marca não pode ter mais que :max caracteres',
            'model.required' => 'O modelo do veículo é obrigatório',
            'model.string' => 'O modelo deve ser um texto',
            'model.max' => 'O modelo não pode ter mais que :max caracteres',
            'version.string' => 'A versão deve ser um texto',
            'version.max' => 'A versão não pode ter mais que :max caracteres',
            'model_year.string' => 'O ano do modelo deve ser um texto',
            'model_year.max' => 'O ano do modelo não pode ter mais que :max caracteres',
            'build_year.string' => 'O ano de fabricação deve ser um texto',
            'build_year.max' => 'O ano de fabricação não pode ter mais que :max caracteres',
            'optionals.array' => 'Os opcionais devem ser uma lista',
            'photos.array' => 'As fotos devem ser uma lista',
            'doors.string' => 'O número de portas deve ser um texto',
            'doors.max' => 'O número de portas não pode ter mais que :max caracteres',
            'board.string' => 'A placa deve ser um texto',
            'board.max' => 'A placa não pode ter mais que :max caracteres',
            'chassi.string' => 'O chassi deve ser um texto',
            'chassi.max' => 'O chassi não pode ter mais que :max caracteres',
            'transmission.string' => 'A transmissão deve ser um texto',
            'transmission.max' => 'A transmissão não pode ter mais que :max caracteres',
            'km.string' => 'A quilometragem deve ser um texto',
            'km.max' => 'A quilometragem não pode ter mais que :max caracteres',
            'description.string' => 'A descrição deve ser um texto',
            'sold.boolean' => 'O campo vendido deve ser verdadeiro ou falso',
            'category.string' => 'A categoria deve ser um texto',
            'category.max' => 'A categoria não pode ter mais que :max caracteres',
            'url_car.url' => 'A URL do carro deve ser uma URL válida',
            'price.numeric' => 'O preço deve ser um número',
            'old_price.numeric' => 'O preço antigo deve ser um número',
            'color.string' => 'A cor deve ser um texto',
            'color.max' => 'A cor não pode ter mais que :max caracteres',
            'fuel.string' => 'O combustível deve ser um texto',
            'fuel.max' => 'O combustível não pode ter mais que :max caracteres',
        ];
    }
}
