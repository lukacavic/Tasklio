<!-- resources/views/custom-stack.blade.php-->
<div>
    <div class="flex gap-2">
        <x-heroicon-s-phone class="w-5 h-5 text-primary-500" />
        <div class="text-sm">{{ $getRecord()->created_at }}</div>
    </div>
    <div class="flex gap-2">
        <x-heroicon-s-envelope class="w-5 h-5 text-primary-500" />
        <div class="text-sm">{{ $getRecord()->name }}</div>
    </div>
</div>
