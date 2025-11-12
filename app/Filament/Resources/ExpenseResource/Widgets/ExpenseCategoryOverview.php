<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Filament\Widgets\ChartWidget;

class ExpenseCategoryOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $currentYear = now()->year; // Assuming you're using Laravel's Carbon for date manipulation
        self::$heading = $currentYear;

        $categories = ExpenseCategory::where('is_stats',true)->get();

        $expenses = array();
        foreach ($categories as $category) {
            $childCategories = ExpenseCategory::where('parent',$category->id)->pluck('id')->toArray();
            $ids = array_merge([$category->id],$childCategories);

            $sum = Expense::whereYear('date', $currentYear)
                ->whereIn('category_id',$ids)
                ->sum('amount');

            $expenses[$category->name] = is_numeric($sum) ? (float) $sum : 0.0;

        }
        return [
            'datasets' => [
                [
                    'label' => 'Yearly Expenses',
                    'data' => array_values($expenses),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => array_keys($expenses),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
