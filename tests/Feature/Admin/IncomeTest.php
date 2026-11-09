<?php

namespace Tests\Feature\Admin;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_filters_by_calendar_year_and_source(): void
    {
        Income::create(['date' => '2025-01-01', 'amount' => 1000, 'income_source' => 'Salary']);
        Income::create(['date' => '2025-12-31', 'amount' => 500, 'income_source' => 'Salary']);
        Income::create(['date' => '2024-12-31', 'amount' => 999, 'income_source' => 'Salary']);
        Income::create(['date' => '2025-06-01', 'amount' => 70, 'income_source' => 'Gifts']);

        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/incomes?year=2025&source=Salary')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Incomes/Index')
                ->where('summary.count', 2)
                ->where('summary.total', 1500)
                ->where('chartYear', 2025)
                ->where('monthly.income.0', 1000)
                ->where('monthly.income.11', 500)
            );

        $this->actingAs($user)->get('/admin/incomes?year=all')
            ->assertInertia(fn ($page) => $page->where('summary.count', 4));

        $this->actingAs($user)->get('/admin/incomes?year=custom&from=2025-06-01&to=2025-12-31')
            ->assertInertia(fn ($page) => $page->where('summary.total', 570));
    }

    public function test_create_requires_known_source_and_bulk_delete(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/incomes', [
            'date' => '2026-02-01', 'amount' => 100, 'income_source' => 'Nonexistent',
        ])->assertSessionHasErrors('income_source');

        $this->actingAs($user)->post('/admin/incomes', [
            'date' => '2026-02-01', 'amount' => '2500.75', 'income_source' => 'Freelance', 'remarks' => 'Client A',
        ])->assertSessionHasNoErrors();

        $i = Income::first();
        $this->assertSame(2500.75, $i->amount);

        $this->actingAs($user)->put("/admin/incomes/{$i->id}", [
            'date' => '2026-02-02', 'amount' => 10, 'income_source' => 'Salary',
        ])->assertSessionHasNoErrors();
        $this->assertSame('Salary', $i->fresh()->income_source);

        $this->actingAs($user)->post('/admin/incomes/bulk-delete', ['ids' => [$i->id]]);
        $this->assertDatabaseCount('incomes', 0);
    }

    public function test_export_streams_csv(): void
    {
        Income::create(['date' => '2025-03-01', 'amount' => 1234.5, 'income_source' => 'Salary', 'remarks' => 'March']);

        $res = $this->actingAs(User::factory()->create())->get('/admin/incomes/export?year=2025');
        $res->assertOk();
        $csv = $res->streamedContent();
        $this->assertStringContainsString('Date,Source,Amount,Note', $csv);
        $this->assertStringContainsString('2025-03-01,Salary,1234.50,March', $csv);
    }

    public function test_income_categories_seeded_by_migration(): void
    {
        $this->assertTrue(IncomeCategory::where('name', 'Investment Profit')->exists());
    }
}
