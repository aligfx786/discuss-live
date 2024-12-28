<?php

namespace App\Models;

use App\Enums\ChirpStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chirp extends Model
{
    use SoftDeletes;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'message',
    ];

    // protected $dates = ['archived_at'];

    //     public function archive() {
    //         // Store archive metadata
    //         $this->update([
    //             'status' => ChirpStatus::Archived,
    //             'archived_at' => now(),
    //             'archived_by' => auth()->id(),
    //             'archive_reason' => request('reason')
    //         ]);

    //         // Optionally move to archive storage
    //         event(new ChirpArchived($this));
    //         // Soft delete
    //         $this->delete();
    //     }

    //     // Query scope for archived items
    //     public function scopeArchived($query) {
    //         return $query->onlyTrashed()
    //             ->where('status', ChirpStatus::Archived);
    //     }
}
