<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex justify-end gap-3">
            <x-filament::button
                color="primary"
                type="submit"
            >
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
