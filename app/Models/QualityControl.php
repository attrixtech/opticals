<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QualityControl extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'check_lens_power_sph_cyl_axis' => 'boolean',
        'check_optical_center_pd' => 'boolean',
        'check_fitting_height' => 'boolean',
        'check_lens_edging_bevel_fit' => 'boolean',
        'check_lens_surface_scratches_coating' => 'boolean',
        'check_frame_four_point_alignment' => 'boolean',
        'check_screws_and_lens_security' => 'boolean',
        'check_cleanliness_and_presentation' => 'boolean',
        'inspected_at' => 'datetime',
    ];

    public function labJobCard(): BelongsTo
    {
        return $this->belongsTo(LabJobCard::class);
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}

