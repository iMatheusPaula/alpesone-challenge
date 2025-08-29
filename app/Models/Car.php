<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'external_id',
        'type',
        'brand',
        'model',
        'version',
        'model_year',
        'build_year',
        'optionals',
        'doors',
        'board',
        'chassi',
        'transmission',
        'km',
        'description',
        'category',
        'url_car',
        'old_price',
        'price',
        'color',
        'fuel',
        'photos',
        'sold',
        'created_at_source',
        'updated_at_source',
    ];

    protected function casts(): array
    {
        return [
            'optionals' => 'array',
            'photos' => 'array',
            'sold' => 'boolean',
            'price' => 'decimal:2',
            'created_at_source' => 'datetime',
            'updated_at_source' => 'datetime',
        ];
    }
}