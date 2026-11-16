<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EyeMeasurement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'measurement_date' => 'date',
        'pd_binocular' => 'decimal:1',
        'pd_right' => 'decimal:1',
        'pd_left' => 'decimal:1',
        'pd_near' => 'decimal:1',
        'frame_a_width' => 'decimal:1',
        'frame_b_height' => 'decimal:1',
        'frame_dbl' => 'decimal:1',
        'frame_ed' => 'decimal:1',
        'frame_temple_length' => 'decimal:1',
        'fitting_height_right' => 'decimal:1',
        'fitting_height_left' => 'decimal:1',
        'pantoscopic_tilt' => 'decimal:1',
        'face_form_angle' => 'decimal:1',
        'vertex_distance' => 'decimal:1',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function measuredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'measured_by');
    }
}

