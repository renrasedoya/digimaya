<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate {{ $certificate->certificate_number }}</title>
    <style>
        @page { margin: 30px 30px; }
        * { font-family: DejaVu Sans, sans-serif; box-sizing: border-box; }
        body { font-size: 10pt; color: #1f2937; line-height: 1.5; margin: 0; padding: 0; }

        .frame {
            padding: 0;
        }

        /* Top accent bar - full width */
        .accent-top {
            width: 100%;
            height: 8pt;
            background: #165DFF;
        }

        /* Inner content with padding */
        .inner {
            padding: 30px 60px 24px 60px;
            border-left: 1pt solid #e5e7eb;
            border-right: 1pt solid #e5e7eb;
        }

        .logo-row {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo-row img {
            height: 28px;
        }

        .title-block {
            text-align: center;
            margin-bottom: 28px;
        }
        .title {
            font-size: 36pt;
            color: #111827;
            font-weight: bold;
            letter-spacing: 4pt;
            line-height: 1;
            margin-bottom: 8px;
        }
        .title-sub {
            font-size: 10pt;
            color: #6b7280;
            letter-spacing: 5pt;
            font-weight: normal;
        }

        .awarded {
            text-align: center;
            font-size: 9pt;
            color: #9ca3af;
            letter-spacing: 3pt;
            margin-bottom: 14px;
        }

        /* Recipient with side ornaments */
        .recipient-row {
            text-align: center;
            margin-bottom: 24px;
        }
        .recipient {
            font-family: 'DejaVu Serif', serif;
            font-size: 38pt;
            color: #165DFF;
            font-weight: bold;
            line-height: 1.1;
            display: inline-block;
            padding: 0 16px;
        }
        .recipient-ornament {
            display: inline-block;
            width: 60px;
            height: 1pt;
            background: #d1d5db;
            vertical-align: middle;
            margin-bottom: 8px;
        }

        .narrative {
            text-align: center;
            font-size: 11pt;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 6px;
        }

        .program {
            text-align: center;
            font-family: 'DejaVu Serif', serif;
            font-size: 18pt;
            color: #111827;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .program-desc {
            text-align: center;
            font-size: 10pt;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 6px;
        }

        .footer-divider {
            border-top: 0.5pt solid #e5e7eb;
            margin: 24px 0 14px 0;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-table td {
            text-align: center;
            padding: 0;
            vertical-align: top;
            width: 33.33%;
        }
        .footer-label {
            font-size: 7pt;
            color: #9ca3af;
            letter-spacing: 2pt;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .footer-value {
            font-size: 11pt;
            color: #1f2937;
            font-weight: bold;
        }

        .verify {
            text-align: center;
            font-size: 8pt;
            color: #9ca3af;
            margin-top: 14px;
        }
        .verify-url {
            color: #165DFF;
        }

        .issued-by {
            text-align: center;
            font-size: 8pt;
            color: #9ca3af;
            letter-spacing: 3pt;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 0.5pt solid #e5e7eb;
        }
        .issued-by strong {
            color: #165DFF;
        }

        /* Bottom accent bar */
        .accent-bottom {
            width: 100%;
            height: 8pt;
            background: #165DFF;
        }
    </style>
</head>
<body>
    <div class="frame">
        <div class="accent-top"></div>

        <div class="inner">
            <div class="logo-row">
                <img src="{{ public_path('images/logo/logo-blue.png') }}" alt="Digimaya">
            </div>

            <div class="title-block">
                <div class="title">CERTIFICATE</div>
                <div class="title-sub">OF COMPLETION</div>
            </div>

            <div class="awarded">IS HEREBY AWARDED TO</div>

            <div class="recipient-row">
                <span class="recipient-ornament"></span>
                <span class="recipient">{{ $certificate->recipient_name }}</span>
                <span class="recipient-ornament"></span>
            </div>

            <div class="narrative">in recognition of the successful completion of</div>
            <div class="program">{{ $certificate->program_name }}</div>
            @if($certificate->program_description)
                <div class="program-desc">{{ $certificate->program_description }}</div>
            @endif

            <div class="footer-divider"></div>

            <table class="footer-table">
                <tr>
                    <td>
                        <div class="footer-label">COMPLETION DATE</div>
                        <div class="footer-value">{{ $certificate->completion_date->format('d F Y') }}</div>
                    </td>
                    <td>
                        <div class="footer-label">CERTIFICATE NO.</div>
                        <div class="footer-value">{{ $certificate->certificate_number }}</div>
                    </td>
                    <td>
                        <div class="footer-label">ISSUED DATE</div>
                        <div class="footer-value">{{ $certificate->issued_date->format('d F Y') }}</div>
                    </td>
                </tr>
            </table>

            <div class="verify">
                Verify this certificate at <span class="verify-url">{{ $verifyUrl }}</span>
            </div>

            <div class="issued-by">
                ISSUED BY &nbsp;&nbsp; <strong>DIGIMAYA</strong> &nbsp;&nbsp; GOOGLE PREMIER PARTNER
            </div>
        </div>

        <div class="accent-bottom"></div>
    </div>
</body>
</html>
