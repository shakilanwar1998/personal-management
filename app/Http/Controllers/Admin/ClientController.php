<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ClientController extends AdminController
{
    public function index()
    {
        $clients = Client::withCount('invoices')->orderBy('name')->get();

        // Billed totals per client and currency (cancelled invoices don't count).
        $totals = Invoice::whereNotNull('client_id')->where('status', '!=', 'cancelled')
            ->selectRaw('client_id, currency, SUM(total) as total, MAX(invoice_date) as last_date')
            ->groupBy('client_id', 'currency')->get()->groupBy('client_id');

        return Inertia::render('Clients/Index', [
            'clients' => $clients->map(fn (Client $c) => array_merge($c->only([
                'id', 'name', 'email', 'address', 'city', 'state', 'postal_code', 'country', 'tax_id', 'currency', 'notes',
            ]), [
                'invoices_count' => $c->invoices_count,
                'billed' => ($totals[$c->id] ?? collect())->map(fn ($t) => ['currency' => $t->currency ?: 'USD', 'total' => (float) $t->total])->values(),
                'last_invoice_date' => ($totals[$c->id] ?? collect())->max('last_date'),
            ]))->values(),
            'countries' => InvoiceController::COUNTRIES,
            'currencies' => InvoiceController::CURRENCIES,
        ]);
    }

    public function store(Request $request)
    {
        $client = Client::create($this->validated($request));

        return back()->with('success', "Client {$client->name} added.");
    }

    public function update(Request $request, Client $client)
    {
        $client->update($this->validated($request, $client));

        return back()->with('success', "Client {$client->name} saved.");
    }

    public function destroy(Client $client)
    {
        $name = $client->name;
        // Invoices keep their own copy of the client details; only the link is removed.
        $client->delete();

        return back()->with('success', "Client {$name} deleted. Their invoices were kept.");
    }

    public static function rules(?Client $client = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('clients', 'name')->ignore($client?->id)],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country' => ['required', Rule::in(array_keys(InvoiceController::COUNTRIES))],
            'tax_id' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', Rule::in(InvoiceController::CURRENCIES)],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    private function validated(Request $request, ?Client $client = null): array
    {
        return $request->validate(self::rules($client), [
            'name.unique' => 'A client with this name already exists.',
        ]);
    }
}
