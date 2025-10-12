<?php

namespace App\Filament\Widgets;

use App\Services\GlobalFinancialYearService;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;

class FinancialYearSelector extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.financial-year-selector';
    
    protected static ?int $sort = -1; // Show at the top
    
    public ?string $selectedFinancialYear = null;
    public $data = [];

    public function mount(): void
    {
        $this->selectedFinancialYear = GlobalFinancialYearService::getSelectedFinancialYear();
        $this->form->fill([
            'financial_year' => $this->selectedFinancialYear
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('financial_year')
                    ->label('Financial Year')
                    ->options([
                        '2023-2024' => '2023-2024',
                        '2024-2025' => '2024-2025',
                        '2025-2026' => '2025-2026',
                    ])
                    ->default(GlobalFinancialYearService::getSelectedFinancialYear())
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        if ($state) {
                            GlobalFinancialYearService::setSelectedFinancialYear($state);
                            $this->selectedFinancialYear = $state;
                            $this->js('window.location.reload()');
                        }
                    })
            ])
            ->statePath('data');
    }

    public function getSelectedFinancialYear(): string
    {
        return GlobalFinancialYearService::getSelectedFinancialYear();
    }
}
