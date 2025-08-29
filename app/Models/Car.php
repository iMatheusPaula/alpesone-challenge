<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'optionals' => 'array',
        'photos' => 'array',
        'sold' => 'boolean',
        'price' => 'decimal:2',
        'created_at_source' => 'datetime',
        'updated_at_source' => 'datetime',
    ];
}