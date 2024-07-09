@php($record = $this->record)

<x-filament::page>

    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @endif

    <form wire:submit.prevent="submitComment" class="pb-5">
        {{ $this->form }}

        <x-filament::button type="submit" @class('mt-4')>
            {{ __($selectedCommentId ? 'Izmjena' : 'Dodaj komentar') }}
        </x-filament::button>

        @if($selectedCommentId)
            <button type="button" wire:click="cancelEditComment"
                    class="px-3 py-2 bg-warning-500 hover:bg-warning-600 text-white rounded mt-3">
                {{ __('Cancel') }}
            </button>
        @endif
    </form>

        @foreach($record->comments->sortByDesc('created_at') as $comment)
            <div
                class="w-full flex flex-col gap-2 @if(!$loop->last) pb-5 mb-5 border-b border-gray-200 @endif ticket-comment">
                <div class="w-full flex justify-between">
                            <span class="flex items-center gap-1 text-gray-500 text-sm">
                                <span class="font-medium flex items-center gap-1">
                                    <x-user-avatar :user="$comment->user"/>
                                    {{ $comment->user->name }}
                                </span>
                                <span class="text-gray-400 px-2">|</span>
                                {{ $comment->created_at->format('d.m.Y g:i') }}
                                ({{ $comment->created_at->diffForHumans() }})
                            </span>
                    @if($comment->user_id === auth()->user()->id)
                        <div class="actions flex items-center gap-2">
                            <button type="button" wire:click="editComment({{ $comment->id }})"
                                    class="text-primary-500 text-xs hover:text-primary-600 hover:underline">
                                Uredi
                            </button>
                            <span class="text-gray-300">|</span>


                            <button type="button" wire:click="deleteComment({{ $comment->id }})"
                                    class="text-danger-500 text-xs hover:text-danger-600 hover:underline">
                                Izbriši
                            </button>
                        </div>
                    @endif
                </div>
                <div class="w-full prose">
                    {!! $comment->content !!}
                </div>
            </div>
        @endforeach

</x-filament::page>
