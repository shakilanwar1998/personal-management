<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        $invoice->load('items');

        $pdf = Pdf::loadView('invoices.invoice', [
            'invoice' => $invoice,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function view(Invoice $invoice)
    {
        $invoice->load('items');

        return view('invoices.invoice', [
            'invoice' => $invoice,
        ]);
    }
}
