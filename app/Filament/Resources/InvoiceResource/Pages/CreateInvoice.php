<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = (new Invoice)->generateInvoiceNumber();
        }

        // Calculate totals before saving
        $items = $data['items'] ?? [];
        $subtotal = 0;
        foreach ($items as $item) {
            if (isset($item['quantity']) && isset($item['unit_price'])) {
                $subtotal += (float) $item['quantity'] * (float) $item['unit_price'];
            }
        }
        $taxRate = (float) ($data['tax_rate'] ?? 0);
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $taxAmount;
        $data['total'] = $total;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $items = $data['items'] ?? [];
        unset($data['items']);

        $invoice = static::getModel()::create($data);

        // Create invoice items
        foreach ($items as $index => $item) {
            $invoice->items()->create([
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'unit_price' => $item['unit_price'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'total' => ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0),
                'sort_order' => $index,
            ]);
        }

        // Recalculate totals to ensure accuracy
        $invoice->calculateTotals();

        return $invoice;
    }
}
