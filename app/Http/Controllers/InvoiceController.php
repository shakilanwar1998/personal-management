<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        $invoice->load('items');
        
        // Determine template based on client country
        $template = match($invoice->client_country) {
            'DE' => 'invoices.germany',
            'US' => 'invoices.usa',
            default => 'invoices.default',
        };

        $pdf = Pdf::loadView($template, [
            'invoice' => $invoice,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function view(Invoice $invoice)
    {
        $invoice->load('items');
        
        // Determine template based on client country
        $template = match($invoice->client_country) {
            'DE' => 'invoices.germany',
            'US' => 'invoices.usa',
            default => 'invoices.default',
        };

        return view($template, [
            'invoice' => $invoice,
        ]);
    }
}
