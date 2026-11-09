@php
    $country = strtoupper((string) $invoice->client_country);

    // Regional formatting: numbers, dates and tax wording follow the client's country.
    $locale = match ($country) {
        'DE' => ['decimal' => ',', 'thousands' => '.', 'date' => 'd.m.Y', 'tax' => 'VAT'],
        'GB' => ['decimal' => '.', 'thousands' => ',', 'date' => 'j M Y', 'tax' => 'VAT'],
        'US' => ['decimal' => '.', 'thousands' => ',', 'date' => 'M j, Y', 'tax' => 'Sales Tax'],
        default => ['decimal' => '.', 'thousands' => ',', 'date' => 'j M Y', 'tax' => 'Tax'],
    };

    $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'];
    $currency = $invoice->currency ?: 'USD';
    $symbol = $symbols[$currency] ?? null;

    $money = function ($amount) use ($locale, $symbol, $currency, $country) {
        $number = number_format((float) $amount, 2, $locale['decimal'], $locale['thousands']);

        if (! $symbol) {
            return $number . ' ' . $currency;
        }

        // German convention puts the symbol after the amount.
        return $country === 'DE' ? $number . ' ' . $symbol : $symbol . $number;
    };

    $date = fn ($value) => $value?->format($locale['date']);

    $countries = [
        'US' => 'United States',
        'DE' => 'Germany',
        'GB' => 'United Kingdom',
        'CA' => 'Canada',
        'AU' => 'Australia',
    ];

    $sender = [
        'name' => $invoice->business_name ?: 'Shakil Anwar',
        'title' => 'Software Engineer',
        'address' => $invoice->business_address ?: "Bochaganj, Dinajpur\n5216 Dinajpur, Bangladesh",
        'email' => $invoice->business_email ?: 'shakilanwar1998@gmail.com',
        'phone' => $invoice->business_phone ?: '+8801738362296',
        'website' => $invoice->business_website,
        'tax_id' => $invoice->business_tax_id,
    ];

    $bank = [
        'Account Name' => 'Md. Shakil Anwar',
        'Account Number' => '5081126005091',
        'Bank' => 'Bank Asia PLC',
        'Branch' => 'Mirpur Branch',
        'Routing Number' => '070262986',
        'SWIFT / BIC' => 'BALBBDDH',
        'Country' => 'Bangladesh',
    ];

    // The free-text address often repeats the city, county, postcode or country
    // that are also stored separately, so strip those parts before laying it out.
    $structured = collect([
        $invoice->client_city,
        $invoice->client_state,
        $invoice->client_postal_code,
        $invoice->client_country,
        $countries[$country] ?? null,
        ['GB' => 'UK', 'US' => 'USA'][$country] ?? null,
    ])->filter()->sortByDesc(fn ($value) => strlen($value));

    $isStructured = fn ($part) => trim($structured->reduce(
        fn ($rest, $value) => preg_replace('/(?<!\w)' . preg_quote($value, '/') . '(?!\w)/i', '', $rest),
        $part
    ), " \t,.") === '';

    $streetLines = collect(preg_split('/\R/', (string) $invoice->client_address))
        ->map(fn ($line) => collect(explode(',', $line))
            ->map(fn ($part) => trim($part))
            ->reject(fn ($part) => $part === '' || $isStructured($part))
            ->implode(', '))
        ->filter();

    $localityLines = match ($country) {
        'US', 'CA', 'AU' => [collect([$invoice->client_city, trim($invoice->client_state . ' ' . $invoice->client_postal_code)])->filter()->implode(', ')],
        'GB' => [collect([$invoice->client_city, $invoice->client_state])->filter()->implode(', '), $invoice->client_postal_code],
        default => [trim($invoice->client_postal_code . ' ' . $invoice->client_city)],
    };

    $clientLines = $streetLines
        ->merge($localityLines)
        ->push($countries[$country] ?? $invoice->client_country)
        ->map(fn ($line) => trim((string) $line))
        ->filter()
        ->values();


    // Services supplied from outside the EU/UK to a VAT-registered business customer.
    $reverseCharge = (float) $invoice->tax_rate == 0.0 && $invoice->client_tax_id && in_array($country, ['DE', 'GB']);
    $reverseChargeNote = match ($country) {
        'DE' => 'Reverse charge: VAT to be accounted for by the recipient (Steuerschuldnerschaft des Leistungsempfängers, §13b UStG).',
        'GB' => 'Reverse charge: customer to account for VAT to HMRC.',
        default => null,
    };

    $statusColors = [
        'draft' => ['#f1f5f9', '#475569'],
        'sent' => ['#e0e7ff', '#3730a3'],
        'due' => ['#fef3c7', '#92400e'],
        'paid' => ['#dcfce7', '#166534'],
        'overdue' => ['#fee2e2', '#991b1b'],
        'cancelled' => ['#f1f5f9', '#64748b'],
    ];
    [$statusBg, $statusFg] = $statusColors[$invoice->status] ?? $statusColors['draft'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Helvetica Neue', Arial, 'DejaVu Sans', sans-serif;
            font-size: 10.5px;
            line-height: 1.55;
            color: #334155;
            background: #fff;
        }
        .page {
            max-width: 794px;
            margin: 0 auto;
            background: #fff;
        }
        .accent-bar {
            height: 6px;
            background: #4f46e5;
        }
        .content {
            padding: 36px 52px 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .muted {
            color: #64748b;
        }
        .label {
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        /* Header */
        .brand-name {
            font-size: 17px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.2px;
        }
        .brand-lines {
            margin-top: 6px;
            color: #64748b;
            line-height: 1.6;
        }
        .title {
            font-size: 34px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .invoice-number {
            margin-top: 8px;
            font-size: 11px;
            color: #64748b;
        }
        .status {
            display: inline-block;
            margin-top: 10px;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Meta strip */
        .meta {
            margin-top: 28px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .meta td {
            padding: 14px 18px;
            border-right: 1px solid #e2e8f0;
            width: 25%;
        }
        .meta td:last-child {
            border-right: none;
        }
        .meta-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .meta-value.accent {
            color: #4f46e5;
        }

        /* Parties */
        .parties {
            margin-top: 24px;
        }
        .parties td {
            width: 50%;
        }
        .party-name {
            font-size: 12.5px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
        }
        .party-lines {
            line-height: 1.65;
            color: #475569;
        }

        /* Line items */
        .items {
            margin-top: 26px;
        }
        .items th {
            padding: 10px 12px;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #64748b;
            text-align: left;
            border-bottom: 1.5px solid #0f172a;
        }
        .items td {
            padding: 13px 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .items td.description {
            color: #0f172a;
        }
        .items td.amount {
            font-weight: bold;
            color: #0f172a;
        }
        .items .col-desc { width: 46%; }
        .items .col-qty { width: 10%; }
        .items .col-tax { width: 10%; }
        .items .col-price { width: 17%; }
        .items .col-total { width: 17%; }

        /* Totals */
        .summary {
            margin-top: 16px;
        }
        .totals td {
            padding: 7px 12px;
        }
        .totals .row-divider td {
            border-bottom: 1px solid #e2e8f0;
        }
        .amount-due {
            margin-top: 10px;
            background: #4f46e5;
            border-radius: 8px;
        }
        .amount-due td {
            padding: 10px 16px;
            line-height: 1.2;
            color: #fff;
            vertical-align: middle;
        }
        .amount-due .due-label {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #c7d2fe;
        }
        .amount-due .due-value {
            font-size: 19px;
            font-weight: bold;
            letter-spacing: -0.3px;
        }

        /* Notes */
        .note {
            margin-top: 22px;
            padding: 12px 16px;
            background: #f8fafc;
            border-left: 3px solid #4f46e5;
            border-radius: 0 6px 6px 0;
            color: #475569;
        }
        .note + .note {
            margin-top: 10px;
        }

        /* Payment */
        .payment {
            margin-top: 24px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .payment > tbody > tr > td {
            padding: 18px 20px;
        }
        .payment-title {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 10px;
        }
        .kv td {
            padding: 2.5px 0;
        }
        .kv .k {
            width: 42%;
            color: #94a3b8;
        }
        .kv .v {
            color: #0f172a;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 22px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 9px;
        }
        .thanks {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="accent-bar"></div>
        <div class="content">

            {{-- Header --}}
            <table>
                <tr>
                    <td>
                        <div class="brand-name">{{ $sender['name'] }}</div>
                        <div class="brand-lines">{{ $sender['title'] }}</div>
                    </td>
                    <td class="text-right" style="width: 45%;">
                        <div class="title">Invoice</div>
                        <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                        <span class="status" style="background: {{ $statusBg }}; color: {{ $statusFg }};">{{ $invoice->status }}</span>
                    </td>
                </tr>
            </table>

            {{-- Key dates & amount --}}
            <table class="meta">
                <tr>
                    <td>
                        <div class="label">Invoice Date</div>
                        <div class="meta-value">{{ $date($invoice->invoice_date) }}</div>
                    </td>
                    <td>
                        <div class="label">{{ $country === 'GB' ? 'Tax Point' : 'Service Date' }}</div>
                        <div class="meta-value">{{ $date($invoice->invoice_date) }}</div>
                    </td>
                    @if($invoice->due_date)
                    <td>
                        <div class="label">Due Date</div>
                        <div class="meta-value">{{ $date($invoice->due_date) }}</div>
                    </td>
                    @endif
                    <td>
                        <div class="label">Amount Due</div>
                        <div class="meta-value accent">{{ $money($invoice->total) }}</div>
                    </td>
                </tr>
            </table>

            {{-- Parties --}}
            <table class="parties">
                <tr>
                    <td style="padding-right: 24px;">
                        <div class="label">Billed To</div>
                        <div class="party-name">{{ $invoice->client_name }}</div>
                        <div class="party-lines">
                            {!! $clientLines->map(fn ($line) => e($line))->implode('<br>') !!}
                            @if($invoice->client_email)
                                <br>{{ $invoice->client_email }}
                            @endif
                            @if($invoice->client_tax_id)
                                <br>{{ in_array($country, ['DE', 'GB']) ? 'VAT No.' : 'Tax ID' }}: {{ $invoice->client_tax_id }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="label">From</div>
                        <div class="party-name">{{ $sender['name'] }}</div>
                        <div class="party-lines">
                            {!! nl2br(e($sender['address'])) !!}<br>
                            {{ $sender['email'] }}<br>
                            {{ $sender['phone'] }}
                            @if($sender['tax_id'])
                                <br>VAT / Tax ID: {{ $sender['tax_id'] }}
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Line items --}}
            <table class="items">
                <thead>
                    <tr>
                        <th class="col-desc">Description</th>
                        <th class="col-qty text-right">Qty</th>
                        <th class="col-tax text-right">{{ $locale['tax'] === 'Sales Tax' ? 'Tax' : $locale['tax'] }}</th>
                        <th class="col-price text-right">Unit Price</th>
                        <th class="col-total text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="description">{!! nl2br(e($item->description)) !!}</td>
                        <td class="text-right">{{ rtrim(rtrim(number_format($item->quantity, 2, $locale['decimal'], $locale['thousands']), '0'), $locale['decimal']) }}</td>
                        <td class="text-right">{{ number_format($item->tax_rate > 0 ? $item->tax_rate : $invoice->tax_rate, 0) }}%</td>
                        <td class="text-right">{{ $money($item->unit_price) }}</td>
                        <td class="text-right amount">{{ $money($item->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <table class="summary">
                <tr>
                    <td style="width: 52%;"></td>
                    <td>
                        <table class="totals">
                            <tr class="row-divider">
                                <td class="muted">Subtotal{{ $locale['tax'] === 'VAT' ? ' (excl. VAT)' : '' }}</td>
                                <td class="text-right">{{ $money($invoice->subtotal) }}</td>
                            </tr>
                            <tr class="row-divider">
                                <td class="muted">{{ $locale['tax'] }} ({{ rtrim(rtrim(number_format($invoice->tax_rate, 2), '0'), '.') }}%)</td>
                                <td class="text-right">{{ $money($invoice->tax_amount) }}</td>
                            </tr>
                        </table>
                        <table class="amount-due">
                            <tr>
                                <td class="due-label">Total Due ({{ $currency }})</td>
                                <td class="text-right due-value">{{ $money($invoice->total) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- Notes --}}
            @if($reverseCharge && $reverseChargeNote)
                <div class="note">{{ $reverseChargeNote }}</div>
            @endif
            @if($invoice->notes)
                <div class="note">{!! nl2br(e($invoice->notes)) !!}</div>
            @endif
            @if($invoice->terms)
                <div class="note">{!! nl2br(e($invoice->terms)) !!}</div>
            @endif

            {{-- Payment --}}
            <table class="payment">
                <tr>
                    <td style="width: 58%; border-right: 1px solid #e2e8f0;">
                        <div class="payment-title">Payment Details</div>
                        <table class="kv">
                            @foreach($bank as $key => $value)
                            <tr>
                                <td class="k">{{ $key }}</td>
                                <td class="v">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <div class="payment-title">Payment Terms</div>
                        <div class="party-lines">
                            @if($invoice->due_date)
                                Please pay {{ $money($invoice->total) }}&nbsp;by {{ $date($invoice->due_date) }}.<br><br>
                            @endif
                            Send payment through a money transfer service such as Western Union, Wise or Xoom.<br><br>
                            Choose <strong style="color: #0f172a;">direct bank deposit</strong> to the account shown, with the payout in <strong style="color: #0f172a;">BDT</strong>.
                            <br><br>
                            Questions? {{ $sender['email'] }}
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Footer --}}
            <table class="footer">
                <tr>
                    <td>
                        <div class="thanks">Thank you for your business.</div>
                    </td>
                    <td class="text-right" style="vertical-align: middle;">
                        {{ $sender['name'] }} &nbsp;·&nbsp; {{ $invoice->invoice_number }}
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>
</html>
