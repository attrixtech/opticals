<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'branch_id',
        'role',
        'phone',
        'commission_rate',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'commission_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'owner']);
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['manager', 'store_manager', 'branch_manager']);
    }

    public function isOptometrist(): bool
    {
        return in_array($this->role, ['optometrist', 'doctor']);
    }

    public function isLabTechnician(): bool
    {
        return in_array($this->role, ['lab_technician', 'technician']);
    }

    public function isCashier(): bool
    {
        return in_array($this->role, ['cashier', 'sales', 'sales_executive']);
    }

    public function canAccess(string $module): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return match ($module) {
            'dashboard' => true,
            'pos' => in_array($this->role, ['manager', 'cashier', 'sales']),
            'prescriptions' => in_array($this->role, ['manager', 'optometrist', 'cashier']),
            'customers' => in_array($this->role, ['manager', 'optometrist', 'cashier', 'sales']),
            'orders' => in_array($this->role, ['manager', 'cashier', 'sales', 'optometrist']),
            'laboratory' => in_array($this->role, ['manager', 'lab_technician']),
            'repairs' => in_array($this->role, ['manager', 'lab_technician', 'cashier']),
            'inventory' => in_array($this->role, ['manager', 'cashier']),
            'invoices' => in_array($this->role, ['manager', 'cashier']),
            default => false,
        };
    }
}
