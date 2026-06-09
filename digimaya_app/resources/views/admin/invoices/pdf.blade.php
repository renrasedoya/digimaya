@php
    use App\Services\InvoicePeriodFormatter;

    // Resolve Bill To data per mode
    $billTo = [
        'name' => null,
        'address' => null,
        'contact_lines' => [],
        'project_name' => null,
        'period_display' => null,
    ];

    if ($invoice->project_id && $invoice->project) {
        // Mode 1: Project linked
        $client = $invoice->client;
        $billTo['name'] = $client?->business_name ?? '-';
        $billTo['address'] = $client?->address;
        if ($client?->contact_name) $billTo['contact_lines'][] = $client->contact_name;
        if ($client?->contact_phone) $billTo['contact_lines'][] = $client->contact_phone;
        if ($client?->contact_email) $billTo['contact_lines'][] = $client->contact_email;
        $billTo['project_name'] = $invoice->project->name;
    } elseif ($invoice->client_id && $invoice->client) {
        // Mode 2: Client only
        $client = $invoice->client;
        $billTo['name'] = $client->business_name;
        $billTo['address'] = $client->address;
        if ($client->contact_name) $billTo['contact_lines'][] = $client->contact_name;
        if ($client->contact_phone) $billTo['contact_lines'][] = $client->contact_phone;
        if ($client->contact_email) $billTo['contact_lines'][] = $client->contact_email;
    } else {
        // Mode 3: Custom
        $billTo['name'] = $invoice->custom_client_name ?: '-';
        $billTo['address'] = $invoice->custom_client_address;
        if ($invoice->custom_client_contact) $billTo['contact_lines'][] = $invoice->custom_client_contact;
    }

    // Period display (always show if set, regardless of mode)
    if ($invoice->period_start && $invoice->period_end) {
        $billTo['period_display'] = InvoicePeriodFormatter::format($invoice->period_start, $invoice->period_end);
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 25px 25px; }
        * { font-family: DejaVu Sans, sans-serif; box-sizing: border-box; }
        body { font-size: 9pt; color: #1f2937; line-height: 1.5; margin: 0; padding: 0; }
        h1, h2, h3, h4 { margin: 0; padding: 0; }

        .header { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
        .header td { vertical-align: top; }
        .company-legal { font-size: 8pt; font-weight: bold; color: #4b5563; margin-bottom: 6px; letter-spacing: 0.3px; }
        .company-meta { font-size: 8pt; color: #6b7280; line-height: 1.6; }
        .invoice-title { text-align: right; font-size: 14pt; font-weight: bold; color: #111827; letter-spacing: 1px; }
        .invoice-meta { text-align: right; font-size: 8pt; color: #6b7280; margin-top: 4px; line-height: 1.6; }
        .invoice-meta strong { color: #1f2937; font-size: 9pt; }

        .info-row { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-row td { vertical-align: top; width: 50%; padding: 0 12px 0 0; }
        .info-label { font-size: 7pt; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; margin-bottom: 2px; }
        .info-value { font-size: 10pt; font-weight: bold; color: #111827; }
        .info-detail { font-size: 8pt; color: #4b5563; margin-top: 1px; }

        .items-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .items-table thead th { background-color: #1f2937; color: #ffffff; font-size: 8pt; font-weight: bold; text-align: left; text-transform: uppercase; letter-spacing: 0.5px; padding: 9px 8px; }
        .items-table thead th.text-right { text-align: right; }
        .items-table tbody td { padding: 9px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; font-size: 9pt; }
        .items-table tbody tr:last-child td { border-bottom: 2px solid #1f2937; }
        .items-table .text-right { text-align: right; }
        .items-table .item-main { color: #1f2937; }
        .items-table .item-sub { font-size: 7pt; color: #6b7280; margin-top: 2px; line-height: 1.4; }

        .totals-wrap { width: 100%; margin-bottom: 20px; }
        .totals { width: 40%; border-collapse: collapse; background-color: #f9fafb; margin-left: auto; }
        .totals td { padding: 7px 12px; font-size: 9pt; white-space: nowrap; }
        .totals .label { color: #6b7280; text-align: left; }
        .totals .value { text-align: right; color: #1f2937; }
        .totals .grand-total { font-size: 10pt; font-weight: bold; color: #111827; border-top: 2px solid #1f2937; padding-top: 9px; }

        .bank-info { background-color: #f9fafb; padding: 12px; margin-bottom: 16px; }
        .bank-info .info-label { margin-bottom: 6px; }
        .bank-info table { width: 100%; border-collapse: collapse; }
        .bank-info td { padding: 2px 0; font-size: 8pt; }
        .bank-info .field-name { color: #6b7280; width: 35%; }
        .bank-info .field-value { color: #1f2937; font-weight: bold; }

        .notes-block { margin-bottom: 16px; padding: 12px; border-left: 3px solid #4f46e5; background-color: #eef2ff; font-size: 8pt; color: #1e3a8a; }
        .notes-block .info-label { color: #4338ca; margin-bottom: 4px; }

        .footer-notes { margin-top: 30px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 7pt; color: #6b7280; text-align: center; font-style: italic; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header">
        <tr>
            <td style="width: 60%;">
                <img src="{{ public_path('images/logo/logo-blue.png') }}" alt="Logo" height="32" style="display: block; margin-bottom: 8px;">
                <div class="company-legal">{{ $company['company_name'] ?? 'PT Digital Maya Group' }}</div>
                <div class="company-meta">
                    @if(!empty($company['company_address_line_1'])){{ $company['company_address_line_1'] }}<br>@endif
                    @if(!empty($company['company_address_line_2'])){{ $company['company_address_line_2'] }}<br>@endif
                    @if(!empty($company['company_email']))Email: {{ $company['company_email'] }}<br>@endif
                    @if(!empty($company['company_phone']))Phone: {{ $company['company_phone'] }}<br>@endif
                    @if(!empty($company['company_npwp']))NPWP: {{ $company['company_npwp'] }}@endif
                </div>
            </td>
            <td style="width: 40%;">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <strong>{{ $invoice->invoice_number }}</strong><br>
                    Issue: {{ $invoice->issue_date?->format('d M Y') }}<br>
                    Due: {{ $invoice->due_date?->format('d M Y') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Bill to + Status --}}
    <table class="info-row">
        <tr>
            <td>
                <div class="info-label">Bill To</div>
                <div class="info-value">{{ $billTo['name'] }}</div>

                @if($billTo['address'])
                    <div class="info-detail">{!! nl2br(e($billTo['address'])) !!}</div>
                @endif

                @foreach($billTo['contact_lines'] as $line)
                    <div class="info-detail">{{ $line }}</div>
                @endforeach


            </td>
            <td style="text-align: right;">
                <div class="info-label">Payment Status</div>
                @if($invoice->status === 'paid')
                    <div class="info-value" style="color: #065f46;">PAID</div>
                    @if($invoice->paid_date)
                        <div class="info-detail">on {{ $invoice->paid_date->format('d M Y') }}</div>
                    @endif
                @else
                    <div class="info-value" style="color: #92400e;">UNPAID</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- Line items --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Description</th>
                <th class="text-right" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 20%;">Unit Price</th>
                <th class="text-right" style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @if($billTo['project_name'] || $billTo['period_display'])
                <tr>
                    <td colspan="4" style="padding: 8px; background-color: #f9fafb; font-size: 8pt; color: #4b5563; border-bottom: 1px solid #e5e7eb;">
                        @if($billTo['project_name'])
                            <span style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-size: 7pt;">Project:</span>
                            <span style="color: #1f2937; margin-right: 16px;">{{ $billTo['project_name'] }}</span>
                        @endif
                        @if($billTo['period_display'])
                            <span style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-size: 7pt;">Period:</span>
                            <span style="color: #1f2937;">{{ $billTo['period_display'] }}</span>
                        @endif
                    </td>
                </tr>
            @endif
            @foreach($invoice->items as $item)
                <tr>
                    <td>
                        @if($item->service)
                            <span class="item-main">{{ $item->service->name }}</span>
                            @if(!empty($item->description))
                                <div class="item-sub">{{ $item->description }}</div>
                            @endif
                        @else
                            <span class="item-main">{{ $item->description ?: '-' }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ','), '0'), '.') }}</td>
                    <td class="text-right">Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format((float) $item->line_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals-wrap">
        <table class="totals">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">Rp {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Tax ({{ rtrim(rtrim(number_format((float) $invoice->tax_rate, 2, '.', ''), '0'), '.') }}%)</td>
                <td class="value">Rp {{ number_format((float) $invoice->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label grand-total">TOTAL</td>
                <td class="value grand-total">Rp {{ number_format((float) $invoice->total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    {{-- Bank account --}}
    @if($invoice->bankAccount)
        <div class="bank-info">
            <div class="info-label">Payment Information</div>
            <table>
                <tr>
                    <td class="field-name">Bank</td>
                    <td class="field-value">{{ $invoice->bankAccount->bank_name }}@if($invoice->bankAccount->label) ({{ $invoice->bankAccount->label }})@endif</td>
                </tr>
                <tr>
                    <td class="field-name">Account Number</td>
                    <td class="field-value">{{ $invoice->bankAccount->account_number }}</td>
                </tr>
                <tr>
                    <td class="field-name">Account Holder</td>
                    <td class="field-value">{{ $invoice->bankAccount->account_holder }}</td>
                </tr>
            </table>
        </div>
    @endif

    {{-- Per-invoice notes --}}
    @if($invoice->notes)
        <div class="notes-block">
            <div class="info-label">Notes</div>
            {!! nl2br(e($invoice->notes)) !!}
        </div>
    @endif

    {{-- Default footer notes --}}
    @if(!empty($footerNotes))
        <div class="footer-notes">
            {{ $footerNotes }}
        </div>
    @endif

</body>
</html>
