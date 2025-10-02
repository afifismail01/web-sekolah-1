<x-filament-panels::page>
    <div class="space-x-6">
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}
        </form>
        <x-filament::button wire:click='submit' class="mt-2">
            Simpan Perubahan
        </x-filament::button>
    </div>
</x-filament-panels::page>
