<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model

{
    use SoftDeletes;

    // Relationship to the current owner
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Original author of the post
    public function originalAuthor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_author_id');
    }

    // Users who have reposted the post
    public function repostedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('reposted_at')
            ->withTimestamps();
    }

    // Repost a post
    // public function repost(Post $post)
    // {
    //     // Attach the post to the user with the current timestamp
    //     $this->repostedPosts()->attach($post->id, ['reposted_at' => now()]);
    // }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'post_user_likes')->withTimestamps();
    }

    // A post belongs to a category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
