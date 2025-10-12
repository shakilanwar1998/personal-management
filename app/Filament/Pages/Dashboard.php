<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BalanceOverview;
use App\Filament\Widgets\FinancialHealthWidget;
use App\Filament\Widgets\MonthlyTrendsWidget;
use App\Filament\Widgets\FinancialYearSelector;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            FinancialYearSelector::class,
            BalanceOverview::class,
            FinancialHealthWidget::class,
            MonthlyTrendsWidget::class,
        ];
    }
}
