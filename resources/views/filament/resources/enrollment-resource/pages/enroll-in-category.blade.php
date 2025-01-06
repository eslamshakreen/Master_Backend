<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center space-x-2">
            <x-filament::button type="submit" formnovalidate>
                تسجيل
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
