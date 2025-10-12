<?php

namespace App\Services\Traits;

use Carbon\Carbon;

trait FinancialYear
{
    /**
     * Get the current financial year
     */
    public function getCurrentFinancialYear(): string
    {
        $now = Carbon::now();
        $year = $now->year;
        
        // If we're in July or later, we're in the next financial year
        if ($now->month >= 7) {
            return $year . '-' . ($year + 1);
        }
        
        // If we're before July, we're in the current financial year
        return ($year - 1) . '-' . $year;
    }
    
    /**
     * Get financial year start and end dates
     */
    public function getFinancialYearDates(string $financialYear): array
    {
        $parts = explode('-', $financialYear);
        $startYear = (int) $parts[0];
        $endYear = (int) $parts[1];
        
        return [
            'start' => Carbon::create($startYear, 7, 1)->startOfDay(),
            'end' => Carbon::create($endYear, 6, 30)->endOfDay()
        ];
    }
    
    /**
     * Get all available financial years from the earliest record
     */
    public function getAvailableFinancialYears(): array
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
            // If no data, return current financial year
            return [$this->getCurrentFinancialYear()];
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
        $currentFY = $this->getCurrentFinancialYear();
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
     * Check if a date falls within a financial year
     */
    public function isDateInFinancialYear(Carbon $date, string $financialYear): bool
    {
        $dates = $this->getFinancialYearDates($financialYear);
        return $date->between($dates['start'], $dates['end']);
    }
    
    /**
     * Get financial year from a specific date
     */
    public function getFinancialYearFromDate(Carbon $date): string
    {
        $year = $date->year;
        
        if ($date->month >= 7) {
            return $year . '-' . ($year + 1);
        }
        
        return ($year - 1) . '-' . $year;
    }
}
