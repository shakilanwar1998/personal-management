<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FinancialYearSelector;
use Filament\Pages\Page;

abstract class BasePage extends Page
{
    protected function getHeaderWidgets(): array
    {
        return [
            FinancialYearSelector::class,
        ];
    }
}
