<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => UserStatus::class,
        'suspended_until' => 'datetime',
        'last_seen_at' => 'datetime'
    ];

    // public function canAccessRole(Role $role): bool
    // {
    //     return $this->status === UserStatus::Active
    //         && $role->isUsable()
    //         && $this->hasRequiredSubscriptionFor($role);
    // }

    // public function isActive(): bool
    // {
    //     return $this->status === UserStatus::Active
    //         && ($this->suspended_until === null || $this->suspended_until < now());
    // }

    //     public function suspend(string $reason, Carbon $until = null)
    //     {
    //         $this->update([
    //             'status' => UserStatus::Suspended,
    //             'suspended_until' => $until ?? now()->addDays(7),
    //             'suspension_reason' => $reason
    //         ]);
    // 
    //         event(new UserSuspended($this));
    //     }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Posts authored by the user
    public function authoredPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'original_author_id');
    }

    // Posts reposted by the user
    public function repostedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->withPivot('reposted_at')
            ->withTimestamps();
    }

    // user post like relation
    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_user_likes')->withTimestamps();
    }

    public function likePost($postId)
    {
        $this->likedPosts()->attach($postId);
    }

    public function unlikePost($postId)
    {
        $this->likedPosts()->detach($postId);
    }

    public function hasLikedPost($postId)
    {
        return $this->likedPosts()->where('post_id', $postId)->exists();
    }

    public function chirps(): HasMany
    {
        return $this->hasMany(Chirp::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isModerator()
    {
        return $this->hasRole('moderator');
    }

    public function isEditor()
    {
        return $this->hasRole('editor');
    }
}
