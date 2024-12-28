<?php

use Livewire\Volt\Component;
use App\Models\Chirp;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public Collection $chirps;

    public Collection $deletedChirps;

    public ?Chirp $editing = null;

    public function mount(): void
    {
        // $this->chirps = Chirp::with('user')->latest()->get();
        $this->getChirps();
    }

    #[On('chirp-created')]
    public function getChirps(): void
    {
        $userId = Auth::id();
        $this->chirps = Chirp::where('user_id', $userId)->with('user')->latest()->get();
        $this->deletedChirps = Chirp::onlyTrashed()->where('user_id', $userId)->with('user')->latest()->get();
    }

    public function edit(Chirp $chirp): void
    {
        $this->editing = $chirp;
        $this->getChirps();
    }

    #[On('chirp-edit-canceled')]
    #[On('chirp-updated')]
    public function disableEditing(): void
    {
        $this->editing = null;
        $this->getChirps();
    }

    public function softDelete(Chirp $chirp): void
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        $this->getChirps();
    }

    public function forceDelete(Chirp $chirp): void
    {
        $this->authorize('delete', $chirp);

        $chirp->forceDelete();

        $this->getChirps();
    }

    public function restore($chirpId): void
    {
        $chirp = Chirp::withTrashed()->findOrFail($chirpId);
        $this->authorize('restore', $chirp);

        // Update the restored_at timestamp
        // $chirp->restored_at = now();
        $chirp->restore();
        $chirp->update(['restored_at' => now()]);

        $this->getChirps();
    }
}; ?>

<div class="flex">
    <!-- Global Loading Spinner -->
    <div wire:loading.delay class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="text-white font-bold">Loading...</div>
    </div>

    <!-- Main Chirps Column -->
    <div wire:loading.remove class="flex-1 mt-2">

        <!-- Loading for Chirps -->
        {{-- <div wire:loading.delay.longest wire:target="getChirps, softDelete, edit" class="text-center py-2">
            <span class="text-gray-600">Loading Chirps...</span>
        </div> --}}

        <div class="bg-white shadow-sm rounded-lg divide-y h-auto">
            @foreach ($chirps as $chirp)
                <div class="p-6 flex space-x-2" wire:key="{{ $chirp->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="ml-3 flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $chirp->user->name }}</span>
                                <small class="ml-2 text-sm text-gray-600">
                                    {{ Carbon\Carbon::parse($chirp->created_at)->format('j M Y, g:i a') }}
                                </small>

                                {{-- Show restoration history --}}
                                @if ($chirp->restored_at)
                                    <small class="text-sm text-gray-600">
                                        &middot; {{ __('restored') }} ({{ $chirp->restored_at->diffForHumans() }})
                                    </small>
                                @endif

                                {{-- Show edit history only if it was edited after restoration or if never restored --}}
                                @unless ($chirp->created_at->eq($chirp->updated_at))
                                    @if (!$chirp->restored_at || $chirp->restored_at->lt($chirp->updated_at))
                                        <small class="text-sm text-gray-600">
                                            &middot; {{ __('edited') }} ({{ $chirp->updated_at->diffForHumans() }})
                                        </small>
                                    @endif
                                @endunless
                            </div>

                            @if ($chirp->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link wire:click="edit({{ $chirp->id }})">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link wire:click="softDelete({{ $chirp->id }})">
                                            {{ __('SoftDelete') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link wire:click="forceDelete({{ $chirp->id }})"
                                            wire:confirm="Are you sure to permanently delete this chirp?">
                                            {{ __('ForceDelete') }}
                                        </x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        @if ($chirp->is($editing))
                            <livewire:chirps.edit :chirp="$chirp" :key="$chirp->id" />
                        @else
                            <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
            @if ($chirps->isEmpty())
                <p class="text-gray-500 text-center text-sm mt-4">No chirps found</p>
            @endif
        </div>
    </div>

    <!-- Left Column: Soft-Deleted Chirps -->
    <div wire:loading.remove class="w-1/3 bg-gray-100 ml-4 p-2 overflow-y-auto max-h-[calc(100vh-64px)] sticky top-0">
        <span>
            <h2 class="text-lg font-semibold mb-4">Deleted Chirps</h2>
        </span>

        <!-- Loading for Deleted Chirps -->
        {{-- <div wire:loading.delay.longest wire:target="getChirps, restore" class="text-center py-2">
            <span class="text-gray-600">Loading Deleted Chirps...</span>
        </div> --}}

        @if (!$deletedChirps->isEmpty())
            <div class="p-5 bg-white shadow-sm rounded-lg"> <!-- Single container for all deleted chirps -->
                @foreach ($deletedChirps as $chirp)
                    <div class="mb-4 border-b border-gray-200 pb-2 last:mb-0 last:border-b-0">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-800 text-sm font-medium">{{ $chirp->user->name }}</span>
                            <small class="ml-2 text-xs text-gray-500">
                                {{ $chirp->deleted_at->format('j M Y, g:i a') }}
                            </small>
                        </div>
                        <p class="mt-2 text-sm text-gray-700">{{ $chirp->message }}</p>
                        <div class="mt-2 flex justify-end">
                            <button wire:click="restore({{ $chirp->id }})"
                                class="text-gray-600 hover:text-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center text-sm mt-4">No deleted chirps</p>
        @endif
    </div>
</div>
