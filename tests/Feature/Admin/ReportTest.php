<?php

namespace Tests\Feature\Admin;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private function seedYear(): array
    {
        $living = ExpenseCategory::create(['name' => 'Living', 'parent' => 0]);
        $food = ExpenseCategory::create(['name' => 'Food', 'parent' => $living->id]);
        $transport = ExpenseCategory::create(['name' => 'Transport', 'parent' => 0]);

        // Inside 2025
        Expense::create(['date' => '2025-01-01', 'category_id' => $food->id, 'amount' => 100]);
        Expense::create(['date' => '2025-12-31', 'category_id' => $living->id, 'amount' => 50]);
        Expense::create(['date' => '2025-06-15', 'category_id' => $transport->id, 'amount' => 30]);
        Income::create(['date' => '2025-01-01', 'amount' => 1000, 'income_source' => 'Salary']);
        Income::create(['date' => '2025-12-31', 'amount' => 500, 'income_source' => 'Remittance']);

        // Boundary dates in adjacent years — must be excluded from 2025
        Expense::create(['date' => '2024-12-31', 'category_id' => $food->id, 'amount' => 9999]);
        Expense::create(['date' => '2026-01-01', 'category_id' => $food->id, 'amount' => 8888]);
        Income::create(['date' => '2024-12-31', 'amount' => 7777, 'income_source' => 'Salary']);
        Income::create(['date' => '2026-01-01', 'amount' => 6666, 'income_source' => 'Salary']);

        return [$living, $food, $transport];
    }

    public function test_report_sums_january_to_december_only(): void
    {
        [$living] = $this->seedYear();

        $this->actingAs(User::factory()->create())
            ->get('/admin/reports?year=2025&compare=2024')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Reports/Index')
                ->where('year', 2025)
                ->where('compare', 2024)
                ->where('summary.income', 1500)
                ->where('summary.expense', 180)
                ->where('summary.savings', 1320)
                ->where('compareSummary.expense', 9999)
                ->where('months.0.income', 1000)
                ->where('months.0.expense', 100)
                ->where('months.11.expense', 50)
                ->where('months.11.cumulative', 1320)
                ->where('matrix.total', 180)
                ->where('matrix.rows.0.id', $living->id)
                ->where('matrix.rows.0.total', 150)
                ->where('matrix.rows.0.months.0', 100)
                ->where('matrix.rows.0.months.11', 50)
                ->where('sources.total', 1500)
                ->where('top.0.amount', 100)
                ->has('top', 3)
            );
    }

    public function test_year_over_year_folds_children_into_parents(): void
    {
        [$living] = $this->seedYear();

        $this->actingAs(User::factory()->create())
            ->get('/admin/reports?year=2025&compare=2024')
            ->assertInertia(fn ($page) => $page
                ->where('yoy.0.id', $living->id)
                ->where('yoy.0.current', 150)
                ->where('yoy.0.previous', 9999)
            );
    }

    public function test_csv_export_downloads(): void
    {
        $this->seedYear();

        $res = $this->actingAs(User::factory()->create())->get('/admin/reports/export?year=2025');
        $res->assertOk();
        $this->assertStringContainsString('text/csv', $res->headers->get('Content-Type'));
        $this->assertStringContainsString('report-2025.csv', $res->headers->get('Content-Disposition'));

        $csv = $res->streamedContent();
        $this->assertStringContainsString('Monthly summary', $csv);
        $this->assertStringContainsString('Spending by category', $csv);
        $this->assertStringContainsString('Income by source', $csv);
        $this->assertStringContainsString('Total,1500.00,180.00,1320.00', $csv);
        $this->assertStringNotContainsString('9999', $csv);
    }

    public function test_empty_year_renders(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/reports?year=2030')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->where('summary.income', 0)->has('matrix.rows', 0));
    }
}
