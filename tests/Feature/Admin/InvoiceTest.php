<?php

namespace Tests\Feature\Admin;

use App\Models\Income;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'invoice_number' => 'INV-2026-0100',
            'invoice_date' => '2026-03-01',
            'due_date' => '2026-03-31',
            'status' => 'sent',
            'currency' => 'EUR',
            'client_name' => 'Acme GmbH',
            'client_email' => 'billing@acme.test',
            'client_country' => 'DE',
            'tax_rate' => 19,
            'items' => [
                ['description' => 'Design', 'quantity' => 2, 'unit_price' => 100, 'tax_rate' => 0],
                ['description' => 'Hosting', 'quantity' => 1, 'unit_price' => 50.5, 'tax_rate' => 0],
            ],
        ], $overrides);
    }

    public function test_index_create_and_edit_pages_render(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin/invoices')->assertOk()
            ->assertInertia(fn ($p) => $p->component('Invoices/Index'));
        $this->actingAs($user)->get('/admin/invoices/create')->assertOk()
            ->assertInertia(fn ($p) => $p->component('Invoices/Form')->where('invoice.invoice_number', 'INV-' . now()->year . '-0001'));

        $this->actingAs($user)->post('/admin/invoices', $this->payload());
        $invoice = Invoice::first();
        $this->actingAs($user)->get("/admin/invoices/{$invoice->id}/edit")->assertOk()
            ->assertInertia(fn ($p) => $p->component('Invoices/Form')->has('invoice.items', 2));
    }

    public function test_create_with_items_computes_totals(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/invoices', $this->payload())
            ->assertRedirect('/admin/invoices')
            ->assertSessionHasNoErrors();

        $invoice = Invoice::with('items')->first();
        $this->assertCount(2, $invoice->items);
        $this->assertEquals(['Design', 'Hosting'], $invoice->items->pluck('description')->all());
        $this->assertEquals(250.50, (float) $invoice->subtotal);
        $this->assertEquals(47.60, (float) $invoice->tax_amount);
        $this->assertEquals(298.10, (float) $invoice->total);
    }

    public function test_update_replaces_items_and_keeps_number(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/invoices', $this->payload());
        $invoice = Invoice::first();

        $this->actingAs($user)->put("/admin/invoices/{$invoice->id}", $this->payload([
            'invoice_number' => 'CHANGED-1',
            'tax_rate' => 0,
            'items' => [['description' => 'Only line', 'quantity' => 3, 'unit_price' => 10, 'tax_rate' => 0]],
        ]))->assertSessionHasNoErrors();

        $invoice->refresh()->load('items');
        $this->assertSame('INV-2026-0100', $invoice->invoice_number);
        $this->assertCount(1, $invoice->items);
        $this->assertEquals(30.0, (float) $invoice->total);
        $this->assertDatabaseCount('invoice_items', 1);
    }

    public function test_validation_and_number_uniqueness(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/invoices', $this->payload());

        $this->actingAs($user)->post('/admin/invoices', $this->payload())
            ->assertSessionHasErrors('invoice_number');

        $this->actingAs($user)->post('/admin/invoices', $this->payload([
            'invoice_number' => 'INV-X',
            'due_date' => '2026-01-01',
            'status' => 'bogus',
            'client_country' => 'FR',
            'items' => [['description' => '', 'quantity' => 0, 'unit_price' => -1]],
        ]))->assertSessionHasErrors(['due_date', 'status', 'client_country', 'items.0.description', 'items.0.quantity', 'items.0.unit_price']);

        $this->actingAs($user)->post('/admin/invoices', $this->payload(['invoice_number' => 'INV-Y', 'items' => []]))
            ->assertSessionHasErrors('items');
    }

    public function test_record_payment_creates_one_income(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/invoices', $this->payload());
        $invoice = Invoice::first();

        $this->actingAs($user)->post("/admin/invoices/{$invoice->id}/payment", ['paid_at' => '2026-04-02', 'record_income' => true])
            ->assertSessionHasErrors('amount_bdt');

        $this->actingAs($user)->post("/admin/invoices/{$invoice->id}/payment", [
            'paid_at' => '2026-04-02', 'record_income' => true, 'amount_bdt' => 38000,
        ])->assertSessionHasNoErrors();

        $invoice->refresh();
        $this->assertSame('paid', $invoice->status);
        $this->assertSame('2026-04-02', $invoice->paid_at->toDateString());
        $income = Income::first();
        $this->assertSame($income->id, $invoice->income_id);
        $this->assertSame('Invoice Payment', $income->income_source);
        $this->assertSame(38000.0, $income->amount);

        // Recording again must not add a second income.
        $this->actingAs($user)->post("/admin/invoices/{$invoice->id}/payment", [
            'paid_at' => '2026-04-03', 'record_income' => true, 'amount_bdt' => 1,
        ]);
        $this->assertDatabaseCount('incomes', 1);

        // Paying without recording income is allowed.
        $this->actingAs($user)->post('/admin/invoices', $this->payload(['invoice_number' => 'INV-2']));
        $second = Invoice::where('invoice_number', 'INV-2')->first();
        $this->actingAs($user)->post("/admin/invoices/{$second->id}/payment", ['paid_at' => '2026-04-05', 'record_income' => false])
            ->assertSessionHasNoErrors();
        $this->assertSame('paid', $second->fresh()->status);
        $this->assertNull($second->fresh()->income_id);
        $this->assertDatabaseCount('incomes', 1);
    }

    public function test_duplicate_copies_items_as_new_draft(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/invoices', $this->payload(['status' => 'paid']));
        $original = Invoice::first();

        $response = $this->actingAs($user)->post("/admin/invoices/{$original->id}/duplicate");

        $copy = Invoice::where('id', '!=', $original->id)->with('items')->first();
        $response->assertRedirect("/admin/invoices/{$copy->id}/edit");
        $this->assertNotSame($original->invoice_number, $copy->invoice_number);
        $this->assertSame('draft', $copy->status);
        $this->assertSame(now()->toDateString(), $copy->invoice_date->toDateString());
        $this->assertCount(2, $copy->items);
        $this->assertEquals((float) $original->total, (float) $copy->total);
        $this->assertNull($copy->income_id);
    }

    public function test_bulk_delete_removes_items_too(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/invoices', $this->payload());
        $this->actingAs($user)->post('/admin/invoices/bulk-delete', ['ids' => [Invoice::first()->id]]);

        $this->assertDatabaseCount('invoices', 0);
        $this->assertDatabaseCount('invoice_items', 0);
    }
}
