<?php

namespace App\Http\Controllers\Admin;

use App\Models\Income;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class InvoiceController extends AdminController
{
    public const CURRENCIES = ['USD', 'EUR', 'GBP'];

    public const COUNTRIES = [
        'US' => 'United States',
        'DE' => 'Germany',
        'GB' => 'United Kingdom',
        'CA' => 'Canada',
        'AU' => 'Australia',
    ];

    private const BUSINESS_FIELDS = [
        'business_name', 'business_address', 'business_city', 'business_state', 'business_postal_code',
        'business_country', 'business_tax_id', 'business_email', 'business_phone', 'business_website',
    ];

    public function index(Request $request)
    {
        $query = $this->filtered($request);
        [$sort, $dir] = $this->sortColumn($request, ['invoice_date', 'due_date', 'total', 'invoice_number'], 'invoice_date');

        $invoices = $query
            ->orderBy($sort, $dir)->orderBy('id', $dir)
            ->paginate($this->perPage($request))
            ->withQueryString()
            ->through(fn (Invoice $i) => $this->row($i));

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
            'filters' => $request->only(['search', 'status', 'client_country', 'year', 'from', 'to', 'sort', 'dir', 'per_page']),
            'summary' => $this->summary(),
            'statuses' => Invoice::STATUSES,
            'countries' => self::COUNTRIES,
        ]);
    }

    public function create()
    {
        $last = Invoice::latest('id')->first();
        $business = $last ? $last->only(self::BUSINESS_FIELDS) : array_fill_keys(self::BUSINESS_FIELDS, null);

        return Inertia::render('Invoices/Form', [
            'invoice' => array_merge([
                'id' => null,
                'invoice_number' => (new Invoice)->generateInvoiceNumber(),
                'invoice_date' => now()->toDateString(),
                'due_date' => null,
                'status' => 'draft',
                'currency' => 'USD',
                'client_name' => '',
                'client_email' => '',
                'client_address' => '',
                'client_city' => '',
                'client_state' => '',
                'client_postal_code' => '',
                'client_country' => 'US',
                'client_tax_id' => '',
                'tax_rate' => 0,
                'notes' => '',
                'terms' => '',
                'items' => [['description' => '', 'quantity' => 1, 'unit_price' => '', 'tax_rate' => 0]],
            ], $business),
            'statuses' => Invoice::STATUSES,
            'currencies' => self::CURRENCIES,
            'countries' => self::COUNTRIES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $items = $data['items'];
            unset($data['items']);
            $invoice = Invoice::create($data + ['subtotal' => 0, 'tax_amount' => 0, 'total' => 0]);
            $this->saveItems($invoice, $items);
        });

        return redirect()->route('admin.invoices.index')->with('success', "Invoice {$data['invoice_number']} created.");
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');

        return Inertia::render('Invoices/Form', [
            'invoice' => array_merge($invoice->toArray(), [
                'tax_rate' => (float) $invoice->tax_rate,
                'items' => $invoice->items->map(fn ($i) => [
                    'description' => $i->description,
                    'quantity' => (float) $i->quantity,
                    'unit_price' => (float) $i->unit_price,
                    'tax_rate' => (float) $i->tax_rate,
                ])->values(),
            ]),
            'statuses' => Invoice::STATUSES,
            'currencies' => self::CURRENCIES,
            'countries' => self::COUNTRIES,
        ]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $this->validated($request, $invoice);
        // The number is fixed once the invoice exists.
        unset($data['invoice_number']);

        DB::transaction(function () use ($invoice, $data) {
            $items = $data['items'];
            unset($data['items']);
            $invoice->update($data);
            $this->saveItems($invoice, $items);
        });

        return back()->with('success', "Invoice {$invoice->invoice_number} saved.");
    }

    public function destroy(Invoice $invoice)
    {
        $number = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', "Invoice {$number} deleted.");
    }

    public function bulkDestroy(Request $request)
    {
        $n = 0;
        foreach (Invoice::whereIn('id', $this->bulkIds($request))->get() as $invoice) {
            $invoice->delete();
            $n++;
        }

        return back()->with('success', "{$n} invoice(s) deleted.");
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'paid_at' => ['required', 'date'],
            'record_income' => ['boolean'],
            'amount_bdt' => ['nullable', 'required_if:record_income,true', 'numeric', 'min:0.01', 'max:9999999999999'],
        ], [
            'amount_bdt.required_if' => 'Enter the amount you received in BDT.',
        ], ['amount_bdt' => 'amount received']);

        DB::transaction(function () use ($invoice, $data) {
            $invoice->status = 'paid';
            $invoice->paid_at = $data['paid_at'];

            if (($data['record_income'] ?? false) && ! $invoice->income_id) {
                $income = Income::create([
                    'date' => $data['paid_at'],
                    'amount' => $data['amount_bdt'],
                    'income_source' => 'Invoice Payment',
                    'remarks' => "Invoice {$invoice->invoice_number} · {$invoice->client_name}",
                ]);
                $invoice->income_id = $income->id;
            }

            $invoice->save();
        });

        return back()->with('success', "Invoice {$invoice->invoice_number} marked as paid.");
    }

    public function duplicate(Invoice $invoice)
    {
        $invoice->load('items');

        $copy = DB::transaction(function () use ($invoice) {
            $copy = $invoice->replicate(['invoice_number', 'paid_at', 'income_id']);
            $copy->invoice_number = (new Invoice)->generateInvoiceNumber();
            $copy->status = 'draft';
            $copy->invoice_date = now()->toDateString();
            $copy->due_date = $invoice->due_date && $invoice->invoice_date
                ? now()->addDays((int) $invoice->invoice_date->diffInDays($invoice->due_date))->toDateString()
                : null;
            $copy->save();

            foreach ($invoice->items as $index => $item) {
                $copy->items()->create([
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'total' => $item->quantity * $item->unit_price,
                    'sort_order' => $index,
                ]);
            }
            $copy->load('items')->calculateTotals();

            return $copy;
        });

        return redirect()->route('admin.invoices.edit', $copy)->with('success', "Created {$copy->invoice_number} from {$invoice->invoice_number}.");
    }

    private function saveItems(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();

        foreach (array_values($items) as $index => $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'tax_rate' => $item['tax_rate'] ?? 0,
                'total' => (float) $item['quantity'] * (float) $item['unit_price'],
                'sort_order' => $index,
            ]);
        }

        $invoice->load('items')->calculateTotals();
    }

    private function filtered(Request $request): Builder
    {
        $query = Invoice::query();

        // Invoices default to all time; the period filter only applies when chosen.
        if ($request->filled('year')) {
            $this->applyPeriod($query, $request, 'invoice_date');
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(fn ($q) => $q->where('invoice_number', 'like', "%{$search}%")
                ->orWhere('client_name', 'like', "%{$search}%")
                ->orWhere('client_email', 'like', "%{$search}%"));
        }

        if (in_array($request->input('status'), Invoice::STATUSES, true)) {
            $query->where('status', $request->input('status'));
        }

        if (array_key_exists((string) $request->input('client_country'), self::COUNTRIES)) {
            $query->where('client_country', $request->input('client_country'));
        }

        return $query;
    }

    private function summary(): array
    {
        $year = now()->year;
        $byCurrency = [];

        $add = function (string $currency, string $key, float $value) use (&$byCurrency) {
            $byCurrency[$currency] ??= ['currency' => $currency, 'outstanding' => 0.0, 'paid_year' => 0.0, 'overdue' => 0.0];
            $byCurrency[$currency][$key] += $value;
        };

        foreach (Invoice::whereIn('status', ['sent', 'due', 'overdue'])->get(['currency', 'total', 'due_date', 'status']) as $i) {
            $add($i->currency ?: 'USD', 'outstanding', (float) $i->total);
            if ($i->status === 'overdue' || ($i->due_date && $i->due_date->lt(now()->startOfDay()))) {
                $add($i->currency ?: 'USD', 'overdue', (float) $i->total);
            }
        }

        $paid = Invoice::where('status', 'paid')
            ->where(fn ($q) => $q->whereYear('paid_at', $year)->orWhere(fn ($q) => $q->whereNull('paid_at')->whereYear('invoice_date', $year)))
            ->get(['currency', 'total']);
        foreach ($paid as $i) {
            $add($i->currency ?: 'USD', 'paid_year', (float) $i->total);
        }

        ksort($byCurrency);

        return [
            'currencies' => array_values($byCurrency),
            'drafts' => Invoice::where('status', 'draft')->count(),
            'open' => Invoice::whereIn('status', ['sent', 'due', 'overdue'])->count(),
            'count' => Invoice::count(),
            'year' => $year,
        ];
    }

    private function row(Invoice $i): array
    {
        $open = ! in_array($i->status, ['paid', 'cancelled', 'draft'], true);

        return [
            'id' => $i->id,
            'invoice_number' => $i->invoice_number,
            'invoice_date' => $i->invoice_date?->toDateString(),
            'due_date' => $i->due_date?->toDateString(),
            'paid_at' => $i->paid_at?->toDateString(),
            'client_name' => $i->client_name,
            'client_email' => $i->client_email,
            'client_country' => $i->client_country,
            'status' => $i->status,
            'currency' => $i->currency ?: 'USD',
            'total' => (float) $i->total,
            'income_id' => $i->income_id,
            'is_overdue' => $open && $i->due_date && $i->due_date->lt(now()->startOfDay()),
        ];
    }

    private function validated(Request $request, ?Invoice $invoice = null): array
    {
        $rules = [
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'status' => ['required', Rule::in(Invoice::STATUSES)],
            'currency' => ['required', Rule::in(self::CURRENCIES)],

            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'client_address' => ['nullable', 'string', 'max:2000'],
            'client_city' => ['nullable', 'string', 'max:255'],
            'client_state' => ['nullable', 'string', 'max:255'],
            'client_postal_code' => ['nullable', 'string', 'max:255'],
            'client_country' => ['required', Rule::in(array_keys(self::COUNTRIES))],
            'client_tax_id' => ['nullable', 'string', 'max:255'],

            'business_name' => ['nullable', 'string', 'max:255'],
            'business_email' => ['nullable', 'email', 'max:255'],
            'business_phone' => ['nullable', 'string', 'max:255'],
            'business_website' => ['nullable', 'url', 'max:255'],
            'business_address' => ['nullable', 'string', 'max:2000'],
            'business_city' => ['nullable', 'string', 'max:255'],
            'business_state' => ['nullable', 'string', 'max:255'],
            'business_postal_code' => ['nullable', 'string', 'max:255'],
            'business_country' => ['nullable', 'string', 'max:255'],
            'business_tax_id' => ['nullable', 'string', 'max:255'],

            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'terms' => ['nullable', 'string', 'max:5000'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:5000'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01', 'max:99999999'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];

        if (! $invoice) {
            $rules['invoice_number'] = ['required', 'string', 'max:255', Rule::unique('invoices', 'invoice_number')];
        }

        $data = $request->validate($rules, [
            'items.required' => 'Add at least one item.',
            'items.min' => 'Add at least one item.',
        ], [
            'items.*.description' => 'description',
            'items.*.quantity' => 'quantity',
            'items.*.unit_price' => 'unit price',
            'items.*.tax_rate' => 'tax rate',
        ]);

        $data['tax_rate'] = (float) ($data['tax_rate'] ?? 0);

        return $data;
    }
}
