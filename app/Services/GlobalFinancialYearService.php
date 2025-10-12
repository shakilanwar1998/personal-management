<?php

namespace App\Services;

use App\Models\Settings;
use App\Services\Traits\FinancialYear;
use Carbon\Carbon;

class GlobalFinancialYearService
{
    use FinancialYear;

    /**
     * Set the selected financial year globally
     */
    public static function setSelectedFinancialYear(string $financialYear): void
    {
        Settings::setSelectedFinancialYear($financialYear);
    }

    /**
     * Get the currently selected financial year
     */
    public static function getSelectedFinancialYear(): string
    {
        return Settings::getSelectedFinancialYear();
    }

    /**
     * Get the current financial year (fallback)
     */
    public static function getCurrentFinancialYear(): string
    {
        return Settings::getCurrentFinancialYear();
    }

    /**
     * Get financial year dates for the selected year
     */
    public static function getSelectedFinancialYearDates(): array
    {
        $financialYear = self::getSelectedFinancialYear();
        $parts = explode('-', $financialYear);
        $startYear = (int) $parts[0];
        $endYear = (int) $parts[1];
        
        return [
            'start' => Carbon::create($startYear, 7, 1)->startOfDay(),
            'end' => Carbon::create($endYear, 6, 30)->endOfDay()
        ];
    }

    /**
     * Get all available financial years
     */
    public static function getAvailableFinancialYears(): array
    {
        $years = [];
        
        // Get the earliest date from expenses, income, and investments
        $earliestExpense = \App\Models\Expense::min('date');
        $earliestIncome = \App\Models\Income::min('date');
        $earliestInvestment = \App\Models\Investment::min('date');
        
        $earliestDate = collect([$earliestExpense, $earliestIncome, $earliestInvestment])
            ->filter()
            ->min();
            
        if (!$earliestDate) {
            // If no data, return current and previous financial years
            $currentFY = self::getCurrentFinancialYear();
            $currentYear = now()->year;
            $previousFY = ($currentYear - 1) . '-' . $currentYear;
            return [$currentFY, $previousFY];
        }
        
        $startDate = Carbon::parse($earliestDate);
        $currentDate = Carbon::now();
        
        // Generate financial years from earliest date to current
        $currentYear = $startDate->year;
        $currentMonth = $startDate->month;
        
        // Determine the first financial year
        if ($currentMonth >= 7) {
            $firstFY = $currentYear . '-' . ($currentYear + 1);
        } else {
            $firstFY = ($currentYear - 1) . '-' . $currentYear;
        }
        
        $years[] = $firstFY;
        
        // Generate subsequent financial years
        $tempDate = Carbon::create($startDate->year, 7, 1);
        
        while ($tempDate->year < $currentDate->year || 
               ($tempDate->year == $currentDate->year && $tempDate->month <= $currentDate->month)) {
            $tempDate->addYear();
            $fy = $tempDate->year . '-' . ($tempDate->year + 1);
            if ($fy !== $firstFY) {
                $years[] = $fy;
            }
        }
        
        return array_reverse($years); // Most recent first
    }

    /**
     * Reset to current financial year
     */
    public static function resetToCurrent(): void
    {
        self::setSelectedFinancialYear(self::getCurrentFinancialYear());
    }
}
