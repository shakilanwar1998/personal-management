<x-filament-widgets::widget>
    <x-filament::card>
        <x-slot name="heading">
            Financial Year Selection
        </x-slot>
        
        <x-slot name="description">
            Select a financial year to view data (July to June)
        </x-slot>

        <form wire:submit.prevent="submit">
            {{ $this->form }}
        </form>
    </x-filament::card>
</x-filament-widgets::widget>
