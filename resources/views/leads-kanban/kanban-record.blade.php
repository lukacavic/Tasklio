<div
    id="{{ $record->id }}"
    wire:click="recordClicked('{{ $record->id }}', {{ @json_encode($record) }})"
    class="record transition bg-white dark:bg-gray-700 rounded-lg px-4 py-2 cursor-grab font-medium text-gray-600 dark:text-gray-200"
    @if($record->just_updated)
        x-data
    x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        "
    @endif
>
    <div class="flex justify-between">
        <div>
            {{ $record->company }}

            @if ($record->priority_id == 3)
                <x-heroicon-s-star class="inline-block text-primary-400 w-4 h-4"/>
            @endif
        </div>
        <div class="text-xs text-right text-gray-400">{{ $record->fullName }}</div>
    </div>

    <div class="text-xs text-gray-400 border-l-4 pl-2 mt-2 mb-2">
        {{ strip_tags($record->description) }}
    </div>

    @if($record->source)
        <div class="text-xs text-gray-400 border-l-4 pl-2 mt-2 mb-2">
            <b>Izvor: </b>{{ strip_tags($record->source->name) }}
        </div>
    @endif

    @if($record->assignedUser)
        <div class="flex hover:-space-x-1 -space-x-3">
            @if($record->assignedUser->avatar_url)
                <x-filament::avatar
                    src="{{asset($record->assignedUser->avatar_url)}}"
                />
            @else
                <div class="w-8 h-8 transition-all rounded-full bg-gray-200 border-2 border-white"></div>
            @endif
        </div>
    @endif

    <div class="mt-2 relative">
        <div class="absolute h-1 bg-primary-500 rounded-full" style="width: 56%"></div>
        <div class="h-1 bg-gray-200 rounded-full"></div>
    </div>
</div>
