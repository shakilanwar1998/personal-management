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
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #000;
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
            align-items: flex-start;
            margin-bottom: 40px;
        }
        .header-left {
            flex: 1;
        }
        .header-left h1 {
            font-size: 32px;
            color: #000;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .invoice-info {
            font-size: 11px;
        }
        .invoice-info div {
            margin-bottom: 6px;
            line-height: 1.5;
        }
        .header-right {
            text-align: right;
            flex: 0 0 320px;
            margin-left: 40px;
            align-self: flex-start;
        }
        .sender-info {
            font-size: 11px;
            line-height: 1.8;
        }
        .sender-info strong {
            font-size: 12px;
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .main-content {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 60px;
            align-items: flex-start;
        }
        .recipient-section {
            flex: 1;
            min-width: 0;
        }
        .dates-section {
            flex: 0 0 220px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #000;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .address {
            line-height: 1.8;
            font-size: 11px;
        }
        .address strong {
            font-size: 11px;
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .dates-info {
            font-size: 11px;
            line-height: 1.8;
        }
        .dates-info div {
            margin-bottom: 12px;
        }
        .dates-info strong {
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        thead {
            background: #f5f5f5;
        }
        th {
            padding: 12px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            border-top: 1px solid #ddd;
        }
        th:first-child {
            border-left: 1px solid #ddd;
        }
        th:last-child {
            border-right: 1px solid #ddd;
        }
        tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tbody tr:last-child td {
            border-bottom: 2px solid #000;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .item-description {
            width: 40%;
        }
        .item-quantity {
            width: 10%;
        }
        .item-vat {
            width: 10%;
        }
        .item-price {
            width: 15%;
        }
        .item-total {
            width: 15%;
        }
        .totals {
            margin-top: 20px;
            margin-left: auto;
            width: 400px;
        }
        .totals-row {
            display: flex;
            justify-content: flex-end;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            gap: 40px;
        }
        .totals-row:last-child {
            border-bottom: 2px solid #000;
            font-weight: bold;
            font-size: 12px;
            padding-top: 10px;
        }
        .totals-label {
            text-align: right;
            min-width: 200px;
        }
        .totals-value {
            text-align: right;
            min-width: 120px;
        }
        .legal-note {
            margin-top: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 3px solid #000;
            font-size: 10px;
            line-height: 1.6;
            max-width: 600px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #000;
            display: flex;
            justify-content: flex-start;
            gap: 80px;
            font-size: 10px;
        }
        .footer-section {
            flex: 0 0 auto;
        }
        .footer-section strong {
            display: block;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: bold;
        }
        .footer-section div {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="header-left">
                <h1>INVOICE</h1>
                <div class="invoice-info">
                    <div><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</div>
                    <div><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="header-right">
                <div class="sender-info">
                    <strong>Md. Shakil Anwar</strong>
                    @if($invoice->business_address)
                        <div>{!! nl2br(e($invoice->business_address)) !!}</div>
                    @else
                        <div>Bochaganj, Dinajpur</div>
                        <div>5216 Dinajpur, Bangladesh</div>
                    @endif
                    @if($invoice->business_email)
                        <div style="margin-top: 5px;">{{ $invoice->business_email }}</div>
                    @else
                        <div style="margin-top: 5px;">shakilanwar1998@gmail.com</div>
                    @endif
                    @if($invoice->business_phone)
                        <div>{{ $invoice->business_phone }}</div>
                    @else
                        <div>+8801738362296</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="recipient-section">
                <div class="section-title">Bill To</div>
                <div class="address">
                    <strong>{{ $invoice->client_name }}</strong>
                    @if($invoice->client_address)
                        <div>{!! nl2br(e($invoice->client_address)) !!}</div>
                    @endif
                    @if($invoice->client_postal_code && $invoice->client_city)
                        <div>{{ $invoice->client_postal_code }} {{ $invoice->client_city }}, {{ $invoice->client_country }}</div>
                    @elseif($invoice->client_country)
                        <div>{{ $invoice->client_country }}</div>
                    @endif
                    @if($invoice->client_email)
                        <div style="margin-top: 5px;">{{ $invoice->client_email }}</div>
                    @endif
                    @if($invoice->client_tax_id)
                        <div>{{ $invoice->client_tax_id }}</div>
                    @endif
                </div>
            </div>
            <div class="dates-section">
                <div class="section-title">Service Details</div>
                <div class="dates-info">
                    @if($invoice->due_date)
                    <div>
                        <strong>Due Date:</strong>
                        <div>{{ $invoice->due_date->format('M d, Y') }}</div>
                    </div>
                    @else
                    <div>
                        <strong>Due Date:</strong>
                        <div>{{ $invoice->invoice_date->format('M d, Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="item-description">DESCRIPTION</th>
                    <th class="item-quantity text-center">QTY</th>
                    <th class="item-vat text-center">VAT</th>
                    <th class="item-price text-right">PRICE</th>
                    <th class="item-total text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td class="item-description">{!! nl2br(e($item->description)) !!}</td>
                    <td class="item-quantity text-center">{{ number_format($item->quantity, 0) }}</td>
                    <td class="item-vat text-center">{{ number_format($item->tax_rate > 0 ? $item->tax_rate : $invoice->tax_rate, 0) }}%</td>
                    <td class="item-price text-right">{{ number_format($item->unit_price, 2, ',', '.') }} {{ $invoice->currency }}</td>
                    <td class="item-total text-right">{{ number_format($item->total, 2, ',', '.') }} {{ $invoice->currency }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <div class="totals-label">Subtotal (excl. VAT)</div>
                <div class="totals-value">{{ number_format($invoice->subtotal, 2, ',', '.') }} {{ $invoice->currency }}</div>
            </div>
            @if($invoice->tax_rate > 0)
            <div class="totals-row">
                <div class="totals-label">VAT {{ number_format($invoice->tax_rate, 0) }}%</div>
                <div class="totals-value">{{ number_format($invoice->tax_amount, 2, ',', '.') }} {{ $invoice->currency }}</div>
            </div>
            @endif
            <div class="totals-row">
                <div class="totals-label"><strong>Total Amount</strong></div>
                <div class="totals-value"><strong>{{ number_format($invoice->total, 2, ',', '.') }} {{ $invoice->currency }}</strong></div>
            </div>
        </div>

        @if($invoice->notes || $invoice->terms)
        <div class="legal-note">
            @if($invoice->notes)
            <div style="margin-bottom: 10px;">{!! nl2br(e($invoice->notes)) !!}</div>
            @endif
            @if($invoice->terms)
            <div>{!! nl2br(e($invoice->terms)) !!}</div>
            @endif
        </div>
        @endif

        <div class="footer">
            <div class="footer-section">
                <strong>Bank Details</strong>
                <div>Account Name: Md. Shakil Anwar</div>
                <div>Account Number: 20503470200836404</div>
                <div>Bank: Islami Bank Bangladesh LTD</div>
                <div>Branch: Setabganj</div>
                <div>Routing Number: 125282174</div>
                <div>SWIFT Code: IBBLBDDH138</div>
            </div>
            <div class="footer-section">
                @if($invoice->business_tax_id)
                <strong>Tax Information</strong>
                <div>VAT ID: {{ $invoice->business_tax_id }}</div>
                @endif
                <div style="margin-top: 10px;">
                    <strong>Contact</strong>
                    <div>Phone: +8801738362296</div>
                    @if($invoice->business_email)
                    <div>Email: {{ $invoice->business_email }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
