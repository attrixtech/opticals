<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'prescription_date' => 'date',
        'expiry_date' => 'date',
        'od_sph' => 'decimal:2',
        'od_cyl' => 'decimal:2',
        'od_axis' => 'integer',
        'od_add' => 'decimal:2',
        'od_prism' => 'decimal:2',
        'os_sph' => 'decimal:2',
        'os_cyl' => 'decimal:2',
        'os_axis' => 'integer',
        'os_add' => 'decimal:2',
        'os_prism' => 'decimal:2',
        'pd_distance' => 'decimal:1',
        'pd_near' => 'decimal:1',
        'is_verified' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function formatPower(string $eye = 'OD'): string
    {
        $prefix = strtolower($eye);
        $sph = $this->{$prefix . '_sph'};
        $cyl = $this->{$prefix . '_cyl'};
        $axis = $this->{$prefix . '_axis'};
        $add = $this->{$prefix . '_add'};

        $out = '';
        if ($sph !== null) {
            $out .= ($sph > 0 ? '+' : '') . number_format($sph, 2) . ' SPH';
        }
        if ($cyl !== null && $cyl != 0) {
            $out .= ' / ' . ($cyl > 0 ? '+' : '') . number_format($cyl, 2) . ' CYL';
            if ($axis !== null) {
                $out .= ' x ' . $axis . '°';
            }
        }
        if ($add !== null && $add > 0) {
            $out .= ' (ADD +' . number_format($add, 2) . ')';
        }

        return $out ?: 'Plano';
    }
}

