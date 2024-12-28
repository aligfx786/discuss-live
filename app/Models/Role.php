<?php

namespace App\Models;

use App\Enums\RoleStatus;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    protected $casts = [
        'status' => RoleStatus::class,
        'deprecated_at' => 'datetime'
    ];

    public function isUsable(): bool
    {
        return $this->status === RoleStatus::Active;
    }

    // public function isUsable(): bool
    // {
    //     // A role is usable if:
    //     // 1. It's active
    //     // 2. Not deprecated
    //     // 3. User has permission to use it
    //     return $this->status === RoleStatus::Active
    //         && $this->deprecated_at === null
    //         && auth()->user()?->can('use', $this);
    // }

    // public function isUsable(): bool
    // {
    //     // Check multiple conditions for role usability
    //     return $this->status === 'active'
    //         && auth()->user()->status === UserStatus::Active
    //         && (!$this->requires_subscription || auth()->user()->hasActiveSubscription())
    //         && $this->permissions->isNotEmpty();
    // }

    // Supporting methods
    // public function hasActiveSubscription(): bool
    // {
    //     return $this->subscription()
    //         ->where('is_active', true)
    //         ->where('ends_at', '>', now())
    //         ->exists();
    // }

    // public function isUser(): bool
    // {
    //     return $this->status === 'active'
    //         && auth()->user()->subscription?->is_active;
    // }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
