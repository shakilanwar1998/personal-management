<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private function invoicePayload(array $overrides = []): array
    {
        return array_merge([
            'invoice_number' => 'INV-2026-0200',
            'invoice_date' => '2026-03-01',
            'status' => 'draft',
            'currency' => 'EUR',
            'client_name' => 'Acme GmbH',
            'client_email' => 'billing@acme.test',
            'client_city' => 'Berlin',
            'client_country' => 'DE',
            'tax_rate' => 19,
            'items' => [['description' => 'Design', 'quantity' => 1, 'unit_price' => 100, 'tax_rate' => 0]],
        ], $overrides);
    }

    public function test_index_lists_clients_with_invoice_totals(): void
    {
        $client = Client::create(['name' => 'Acme GmbH', 'country' => 'DE', 'currency' => 'EUR']);
        Invoice::create(['invoice_number' => 'A-1', 'invoice_date' => '2026-01-01', 'client_id' => $client->id, 'client_name' => 'Acme GmbH',
            'client_country' => 'DE', 'currency' => 'EUR', 'status' => 'paid', 'subtotal' => 100, 'tax_amount' => 0, 'total' => 100]);

        $this->actingAs(User::factory()->create())->get('/admin/clients')->assertOk()
            ->assertInertia(fn ($p) => $p->component('Clients/Index')
                ->where('clients.0.name', 'Acme GmbH')
                ->where('clients.0.invoices_count', 1)
                ->where('clients.0.billed.0.total', 100));
    }

    public function test_create_update_and_delete_client(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/clients', ['name' => 'Morgan Tiddy', 'country' => 'GB', 'currency' => 'GBP', 'city' => 'Truro'])
            ->assertSessionHasNoErrors();
        $client = Client::firstWhere('name', 'Morgan Tiddy');
        $this->assertSame('Truro', $client->city);

        $this->actingAs($user)->post('/admin/clients', ['name' => 'Morgan Tiddy', 'country' => 'GB', 'currency' => 'GBP'])
            ->assertSessionHasErrors('name');

        $this->actingAs($user)->put("/admin/clients/{$client->id}", ['name' => 'Morgan Tiddy', 'country' => 'GB', 'currency' => 'GBP', 'email' => 'm@t.test'])
            ->assertSessionHasNoErrors();
        $this->assertSame('m@t.test', $client->fresh()->email);

        $invoice = Invoice::create(['invoice_number' => 'A-1', 'invoice_date' => '2026-01-01', 'client_id' => $client->id, 'client_name' => 'Morgan Tiddy',
            'client_country' => 'GB', 'currency' => 'GBP', 'status' => 'draft', 'subtotal' => 0, 'tax_amount' => 0, 'total' => 0]);

        $this->actingAs($user)->delete("/admin/clients/{$client->id}");
        $this->assertNull(Client::find($client->id));
        $this->assertNull($invoice->fresh()->client_id);
        $this->assertSame('Morgan Tiddy', $invoice->fresh()->client_name);
    }

    public function test_new_invoice_can_save_a_new_client_profile(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/invoices', $this->invoicePayload(['save_client' => true]))
            ->assertSessionHasNoErrors();

        $client = Client::sole();
        $this->assertSame('Acme GmbH', $client->name);
        $this->assertSame('Berlin', $client->city);
        $this->assertSame('EUR', $client->currency);
        $this->assertSame($client->id, Invoice::sole()->client_id);
    }

    public function test_saving_reuses_a_client_with_the_same_name(): void
    {
        $client = Client::create(['name' => 'Acme GmbH', 'country' => 'DE', 'currency' => 'EUR']);

        $this->actingAs(User::factory()->create())
            ->post('/admin/invoices', $this->invoicePayload(['save_client' => true]))
            ->assertSessionHasNoErrors();

        $this->assertSame(1, Client::count());
        $this->assertSame($client->id, Invoice::sole()->client_id);
    }

    public function test_picked_client_is_linked_and_only_updated_when_asked(): void
    {
        $user = User::factory()->create();
        $client = Client::create(['name' => 'Acme GmbH', 'city' => 'Berlin', 'country' => 'DE', 'currency' => 'EUR']);

        $this->actingAs($user)->post('/admin/invoices', $this->invoicePayload(['client_id' => $client->id, 'client_city' => 'Hamburg']))
            ->assertSessionHasNoErrors();
        $this->assertSame($client->id, Invoice::sole()->client_id);
        $this->assertSame('Berlin', $client->fresh()->city);

        $this->actingAs($user)->post('/admin/invoices', $this->invoicePayload([
            'invoice_number' => 'INV-2026-0201', 'client_id' => $client->id, 'client_city' => 'Hamburg', 'update_client' => true,
        ]))->assertSessionHasNoErrors();
        $this->assertSame('Hamburg', $client->fresh()->city);
    }

    public function test_create_page_prefills_a_chosen_client(): void
    {
        $client = Client::create(['name' => 'Morgan Tiddy', 'city' => 'Truro', 'country' => 'GB', 'currency' => 'GBP']);

        $this->actingAs(User::factory()->create())->get("/admin/invoices/create?client={$client->id}")->assertOk()
            ->assertInertia(fn ($p) => $p->component('Invoices/Form')
                ->where('invoice.client_id', $client->id)
                ->where('invoice.client_name', 'Morgan Tiddy')
                ->where('invoice.currency', 'GBP')
                ->has('clients', 1));
    }

    public function test_invoice_list_filters_by_client(): void
    {
        $a = Client::create(['name' => 'A', 'country' => 'US', 'currency' => 'USD']);
        foreach (['X-1' => $a->id, 'X-2' => null] as $number => $clientId) {
            Invoice::create(['invoice_number' => $number, 'invoice_date' => '2026-01-01', 'client_id' => $clientId, 'client_name' => 'n',
                'client_country' => 'US', 'currency' => 'USD', 'status' => 'draft', 'subtotal' => 0, 'tax_amount' => 0, 'total' => 0]);
        }

        $this->actingAs(User::factory()->create())->get("/admin/invoices?client={$a->id}")->assertOk()
            ->assertInertia(fn ($p) => $p->has('invoices.data', 1)->where('invoices.data.0.invoice_number', 'X-1'));
    }
}
