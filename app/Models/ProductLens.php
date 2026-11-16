<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLens extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_photochromic' => 'boolean',
        'is_blue_cut' => 'boolean',
        'is_polarized' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

