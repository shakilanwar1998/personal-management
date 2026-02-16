<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .header-left h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header-right {
            text-align: right;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            padding: 10px;
            text-align: left;
            background: #333;
            color: white;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 300px;
        }
        .totals td {
            padding: 8px 12px;
        }
        .totals tr:last-child td {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="header-left">
                <h1>INVOICE</h1>
                <div>
                    <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                    <div><strong>Date:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}</div>
                    @if($invoice->due_date)
                    <div><strong>Due Date:</strong> {{ $invoice->due_date->format('Y-m-d') }}</div>
                    @endif
                </div>
            </div>
            <div class="header-right">
                @if($invoice->business_name)
                    <h2>{{ $invoice->business_name }}</h2>
                @endif
                @if($invoice->business_address)
                    <div>{!! nl2br(e($invoice->business_address)) !!}</div>
                @endif
            </div>
        </div>

        <div class="section">
            <div class="section-title">Bill To</div>
            <div>
                <strong>{{ $invoice->client_name }}</strong><br>
                @if($invoice->client_address)
                    {!! nl2br(e($invoice->client_address)) !!}<br>
                @endif
                {{ $invoice->client_country }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{!! nl2br(e($item->description)) !!}</td>
                    <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                @if($invoice->tax_rate > 0)
                <tr>
                    <td>Tax ({{ number_format($invoice->tax_rate, 2) }}%):</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Total:</strong></td>
                    <td class="text-right"><strong>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        @if($invoice->notes)
        <div style="margin-top: 30px;">
            <strong>Notes:</strong><br>
            {!! nl2br(e($invoice->notes)) !!}
        </div>
        @endif
    </div>
</body>
</html>
