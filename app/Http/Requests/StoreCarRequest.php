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

            // JSON/array fields
            'year' => ['nullable', 'array'],
            'optionals' => ['nullable', 'array'],
            'fotos' => ['nullable', 'array'],

            'doors' => ['nullable', 'string', 'max:50'],
            'board' => ['nullable', 'string', 'max:100'],
            'chassi' => ['nullable', 'string', 'max:100'],
            'transmission' => ['nullable', 'string', 'max:100'],
            'km' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],

            'created_at_api' => ['nullable', 'date'],
            'updated_at_api' => ['nullable', 'date'],
            'sold' => ['sometimes', 'boolean'],
            'category' => ['nullable', 'string', 'max:255'],
            'url_car' => ['nullable', 'url'],
            'price' => ['nullable', 'numeric'],
            'old_price' => ['nullable', 'numeric'],
            'color' => ['nullable', 'string', 'max:100'],
            'fuel' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type.required' => 'O tipo do veículo é obrigatório',
            'type.string' => 'O tipo deve ser um texto',
            'type.max' => 'O tipo não pode ter mais que :max caracteres',
            'brand.required' => 'A marca é obrigatória',
            'brand.string' => 'A marca deve ser um texto',
            'brand.max' => 'A marca não pode ter mais que :max caracteres',
            'model.required' => 'O modelo é obrigatório',
            'model.string' => 'O modelo deve ser um texto',
            'model.max' => 'O modelo não pode ter mais que :max caracteres',
            'version.string' => 'A versão deve ser um texto',
            'version.max' => 'A versão não pode ter mais que :max caracteres',
            'year.array' => 'O ano deve ser um array',
            'optionals.array' => 'Os opcionais devem ser um array',
            'fotos.array' => 'As fotos devem ser um array',
            'doors.string' => 'As portas devem ser um texto',
            'doors.max' => 'As portas não podem ter mais que :max caracteres',
            'board.string' => 'A placa deve ser um texto',
            'board.max' => 'A placa não pode ter mais que :max caracteres',
            'chassi.string' => 'O chassi deve ser um texto',
            'chassi.max' => 'O chassi não pode ter mais que :max caracteres',
            'transmission.string' => 'A transmissão deve ser um texto',
            'transmission.max' => 'A transmissão não pode ter mais que :max caracteres',
            'km.string' => 'A quilometragem deve ser um texto',
            'km.max' => 'A quilometragem não pode ter mais que :max caracteres',
            'description.string' => 'A descrição deve ser um texto',
            'created_at_api.date' => 'A data de criação deve ser uma data válida',
            'updated_at_api.date' => 'A data de atualização deve ser uma data válida',
            'sold.boolean' => 'O campo vendido deve ser verdadeiro ou falso',
            'category.string' => 'A categoria deve ser um texto',
            'category.max' => 'A categoria não pode ter mais que :max caracteres',
            'url_car.url' => 'A URL do carro deve ser uma URL válida',
            'price.numeric' => 'O preço deve ser um valor numérico',
            'old_price.numeric' => 'O preço antigo deve ser um valor numérico',
            'color.string' => 'A cor deve ser um texto',
            'color.max' => 'A cor não pode ter mais que :max caracteres',
            'fuel.string' => 'O combustível deve ser um texto',
            'fuel.max' => 'O combustível não pode ter mais que :max caracteres',
        ];
    }
}
