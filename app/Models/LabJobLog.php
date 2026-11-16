<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabJobLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function labJobCard(): BelongsTo
    {
        return $this->belongsTo(LabJobCard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

