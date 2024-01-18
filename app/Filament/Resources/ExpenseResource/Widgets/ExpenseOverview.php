<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Expense;
use Filament\Widgets\ChartWidget;

class ExpenseOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $currentYear = now()->year; // Assuming you're using Laravel's Carbon for date manipulation
        self::$heading = $currentYear;
        $records = Expense::whereYear('date', $currentYear)->get();

        $monthlyData = [];
        foreach ($records as $expense) {
            $month = $expense->created_at->format('M'); // Assuming created_at is a timestamp field
            $monthlyData[$month][] = $expense->amount; // Replace 'amount' with the actual field containing expense data
        }

        $data = array_map(function ($expenses) {
            return array_sum($expenses);
        }, $monthlyData);

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Expenses',
                    'data' => array_values($data),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
