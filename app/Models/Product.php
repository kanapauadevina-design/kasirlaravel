<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $path = ltrim($this->image, '/');

        if (str_starts_with($path, 'storage/')) {
            return '/'.implode('/', array_map('rawurlencode', explode('/', $path)));
        }

        return '/storage/'.implode('/', array_map('rawurlencode', explode('/', $path)));
    }
}