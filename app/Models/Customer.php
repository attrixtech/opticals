<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date_of_birth' => 'date',
        'anniversary_date' => 'date',
        'outstanding_balance' => 'decimal:2',
        'loyalty_points' => 'integer',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class)->orderBy('prescription_date', 'desc');
    }

    public function latestPrescription()
    {
        return $this->hasOne(Prescription::class)->latestOfMany('prescription_date');
    }

    public function eyeMeasurements(): HasMany
    {
        return $this->hasMany(EyeMeasurement::class)->orderBy('measurement_date', 'desc');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class)->orderBy('order_date', 'desc');
    }

    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class)->orderBy('received_date', 'desc');
    }
}

