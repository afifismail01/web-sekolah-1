<x-filament-panels::page>
    <div class="mb-4">
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}
        </form>
    </div>
    <div class="mb-4">
        <x-filament::button wire:click='submit' class="mt-2">
            Simpan Perubahan
        </x-filament::button>
    </div>

    {{-- <div class="space-y-6">
        {{ $this->form }}

        <div class="pt-4">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </div> --}}
</x-filament-panels::page>
