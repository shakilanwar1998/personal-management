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
            font-family: 'Helvetica Neue', Arial, sans-serif;
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
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        .header-left h1 {
            font-size: 32px;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .header-right {
            text-align: right;
        }
        .invoice-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 5px;
        }
        .invoice-info strong {
            display: inline-block;
            width: 120px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .two-columns {
            display: flex;
            justify-content: space-between;
            gap: 40px;
        }
        .column {
            flex: 1;
        }
        .address {
            line-height: 1.8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead {
            background: #2563eb;
            color: white;
        }
        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        tbody tr:hover {
            background: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 300px;
        }
        .totals table {
            margin: 0;
        }
        .totals td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .totals td:first-child {
            text-align: right;
            font-weight: 600;
        }
        .totals tr:last-child td {
            border-bottom: none;
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            padding-top: 10px;
            border-top: 2px solid #2563eb;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 5px;
        }
        .notes h3 {
            font-size: 12px;
            margin-bottom: 10px;
            color: #1e293b;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border: 1px solid #333;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #fff;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="header-left">
                <h1>INVOICE</h1>
                <div class="invoice-info">
                    <div><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</div>
                    <div><strong>Date:</strong> {{ $invoice->invoice_date->format('F d, Y') }}</div>
                    @if($invoice->due_date)
                    <div><strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}</div>
                    @endif
                    <div><strong>Status:</strong> 
                        <span class="status-badge">{{ strtoupper($invoice->status) }}</span>
                    </div>
                </div>
            </div>
            <div class="header-right">
                @if($invoice->business_name)
                    <h2 style="color: #2563eb; margin-bottom: 10px;">{{ $invoice->business_name }}</h2>
                @endif
                @if($invoice->business_address)
                    <div class="address">
                        {!! nl2br(e($invoice->business_address)) !!}
                        @if($invoice->business_city)
                            <br>{{ $invoice->business_city }}
                            @if($invoice->business_state), {{ $invoice->business_state }}@endif
                            @if($invoice->business_postal_code) {{ $invoice->business_postal_code }}@endif
                        @endif
                        @if($invoice->business_country)
                            <br>{{ $invoice->business_country }}
                        @endif
                    </div>
                @endif
                @if($invoice->business_email)
                    <div style="margin-top: 10px;">Email: {{ $invoice->business_email }}</div>
                @endif
                @if($invoice->business_phone)
                    <div>Phone: {{ $invoice->business_phone }}</div>
                @endif
                @if($invoice->business_tax_id)
                    <div style="margin-top: 10px;"><strong>Tax ID:</strong> {{ $invoice->business_tax_id }}</div>
                @endif
            </div>
        </div>

        <div class="two-columns">
            <div class="column">
                <div class="section">
                    <div class="section-title">Bill To</div>
                    <div class="address">
                        <strong>{{ $invoice->client_name }}</strong><br>
                        @if($invoice->client_address)
                            {!! nl2br(e($invoice->client_address)) !!}<br>
                        @endif
                        @if($invoice->client_city)
                            {{ $invoice->client_city }}
                            @if($invoice->client_state), {{ $invoice->client_state }}@endif
                            @if($invoice->client_postal_code) {{ $invoice->client_postal_code }}@endif
                            <br>
                        @endif
                        {{ $invoice->client_country }}
                        @if($invoice->client_email)
                            <br><br>Email: {{ $invoice->client_email }}
                        @endif
                        @if($invoice->client_tax_id)
                            <br>Tax ID: {{ $invoice->client_tax_id }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
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
        </div>

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

        @if($invoice->notes || $invoice->terms)
        <div class="notes">
            @if($invoice->notes)
            <h3>Notes:</h3>
            <div>{!! nl2br(e($invoice->notes)) !!}</div>
            @endif
            @if($invoice->terms)
            <h3 style="margin-top: 15px;">Terms & Conditions:</h3>
            <div>{!! nl2br(e($invoice->terms)) !!}</div>
            @endif
        </div>
        @endif

        <div class="footer">
            <p>This is a computer-generated invoice. No signature required.</p>
            @if($invoice->business_website)
                <p>{{ $invoice->business_website }}</p>
            @endif
        </div>
    </div>
</body>
</html>
