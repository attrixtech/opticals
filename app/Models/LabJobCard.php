<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LabJobCard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'optical_specifications' => 'array',
        'started_at' => 'datetime',
        'estimated_completion_at' => 'datetime',
        'completed_at' => 'datetime',
        'lab_cost' => 'decimal:2',
    ];

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LabJobLog::class)->orderBy('created_at', 'asc');
    }

    public function qualityControl(): HasOne
    {
        return $this->hasOne(QualityControl::class);
    }

    public function transitionStage(string $toStage, ?string $remarks = null, ?int $userId = null): void
    {
        $fromStage = $this->stage;
        $this->stage = $toStage;
        if ($toStage === 'completed') {
            $this->completed_at = now();
        }
        $this->save();

        $this->logs()->create([
            'from_stage' => $fromStage,
            'to_stage' => $toStage,
            'remarks' => $remarks,
            'user_id' => $userId,
        ]);
    }
}

