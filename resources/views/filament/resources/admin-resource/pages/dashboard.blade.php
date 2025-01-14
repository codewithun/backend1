<x-filament::page>
    <div class="grid grid-cols-2 gap-4">
        <x-filament::widget :widget="new \App\Filament\Resources\AdminResource\Widgets\AccountWidget" />
        <x-filament::widget :widget="new \App\Filament\Resources\AdminResource\Widgets\DateTime" />
    </div>
</x-filament::page>