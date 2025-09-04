<div class="inline-flex items-center gap-1 text-xs font-medium">
    @if($table->available_status === 'running' && $table->occupied_at)
        <div class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 border border-blue-200 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="text-blue-600" viewBox="0 0 16 16">
                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
            </svg>
            <span class="text-blue-700 font-mono" wire:poll.5s="updateTimer">{{ $formattedDuration }}</span>
        </div>
    @endif
</div>