<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'client_name',
        'client_address',
        'client_city',
        'client_state',
        'client_postal_code',
        'client_country',
        'client_tax_id',
        'client_email',
        'business_name',
        'business_address',
        'business_city',
        'business_state',
        'business_postal_code',
        'business_country',
        'business_tax_id',
        'business_email',
        'business_phone',
        'business_website',
        'currency',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'status',
        'notes',
        'terms',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum('total');
        $taxAmount = $subtotal * ($this->tax_rate / 100);
        $total = $subtotal + $taxAmount;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ]);
    }

    public function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $prefix = 'INV-' . $year . '-';
        
        // Find the last invoice number for this year
        $lastInvoice = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice && preg_match('/' . preg_quote($prefix, '/') . '(\d+)$/', $lastInvoice->invoice_number, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
