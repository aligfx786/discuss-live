<?php

namespace App\Models;

use App\Enums\ChirpStatus;
use App\Events\ChirpCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chirp extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'message',
        'restored_at',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'restored_at' => 'datetime',
    ];

    // Now that we have our event class, we're ready to dispatch it any time a Chirp is created. You may dispatch events anywhere in your application lifecycle, but as our event relates to the creation of an Eloquent model, we can configure our Chirp model to dispatch the event for us.
    // Now any time a new Chirp is created, the ChirpCreated event will be dispatched.
    protected $dispatchesEvents = [
        'created' => ChirpCreated::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
