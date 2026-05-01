<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Receipt – {{ $booking->booking_number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            padding: 40px 20px;
        }

        .receipt {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.07);
        }

        .receipt-header {
            background: #1d4ed8;
            color: #fff;
            padding: 28px 32px;
            text-align: center;
        }

        .receipt-header h1 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .receipt-header p {
            font-size: 13px;
            opacity: .8;
            margin-top: 4px;
        }

        .receipt-body {
            padding: 28px 32px;
        }

        .badge {
            display: inline-block;
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 4px 14px;
            border-radius: 100px;
            margin-bottom: 20px;
        }

        .booking-number {
            font-size: 20px;
            font-weight: 800;
            color: #1d4ed8;
            margin-bottom: 20px;
            letter-spacing: .5px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 9px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .row:last-of-type { border-bottom: none; }

        .row .label { color: #64748b; }
        .row .value { font-weight: 600; text-align: right; max-width: 60%; }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0 0;
            margin-top: 6px;
            border-top: 2px solid #e2e8f0;
        }

        .total-row .label { font-size: 14px; font-weight: 700; }
        .total-row .amount { font-size: 22px; font-weight: 800; color: #16a34a; }

        .receipt-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 18px 32px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .print-btn {
            display: block;
            max-width: 480px;
            margin: 20px auto 0;
            padding: 12px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
        }

        .print-btn:hover { background: #1e40af; }

        @media print {
            body { background: #fff; padding: 0; }
            .receipt { box-shadow: none; border: none; border-radius: 0; max-width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

<div class="receipt">
    <div class="receipt-header">
        <h1>Auto Sinaran Workshop</h1>
        <p>Official Payment Receipt</p>
    </div>

    <div class="receipt-body">
        <div>
            <span class="badge">Confirmed</span>
        </div>

        <div class="booking-number">{{ $booking->booking_number }}</div>

        <div class="row">
            <span class="label">Customer</span>
            <span class="value">{{ $booking->customer_name }}</span>
        </div>
        <div class="row">
            <span class="label">Email</span>
            <span class="value">{{ $booking->customer_email }}</span>
        </div>
        <div class="row">
            <span class="label">Phone</span>
            <span class="value">{{ $booking->customer_phone }}</span>
        </div>
        <div class="row">
            <span class="label">Vehicle</span>
            <span class="value">{{ $booking->vehicle_model }} ({{ strtoupper($booking->vehicle_plate) }})</span>
        </div>
        <div class="row">
            <span class="label">Package</span>
            <span class="value">{{ $booking->package->name }}</span>
        </div>
        <div class="row">
            <span class="label">Date</span>
            <span class="value">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
        </div>
        @if($booking->booking_time)
        <div class="row">
            <span class="label">Time</span>
            @php
                [$h, $m] = explode(':', $booking->booking_time);
                $period = $h >= 12 ? 'PM' : 'AM';
                $display = $h > 12 ? $h - 12 : ($h == 0 ? 12 : (int)$h);
            @endphp
            <span class="value">{{ $display }}:{{ $m }} {{ $period }}</span>
        </div>
        @endif
        @if($booking->payment?->transaction_ref)
        <div class="row">
            <span class="label">Transaction Ref.</span>
            <span class="value" style="font-family:monospace;font-size:11px;">{{ $booking->payment->transaction_ref }}</span>
        </div>
        @endif
        <div class="row">
            <span class="label">Payment Method</span>
            <span class="value">Billplz (Online)</span>
        </div>
        <div class="row">
            <span class="label">Payment Date</span>
            <span class="value">
                {{ $booking->payment?->paid_at
                    ? \Carbon\Carbon::parse($booking->payment->paid_at)->format('d M Y, g:i A')
                    : \Carbon\Carbon::parse($booking->updated_at)->format('d M Y, g:i A') }}
            </span>
        </div>

        <div class="total-row">
            <span class="label">Total Paid</span>
            <span class="amount">RM {{ number_format($booking->package->price, 2) }}</span>
        </div>
    </div>

    <div class="receipt-footer">
        <p>Thank you for choosing Auto Sinaran Workshop.</p>
        <p>Please present this receipt upon arrival.</p>
        <p style="margin-top:8px;">Generated on {{ now()->format('d M Y, g:i A') }}</p>
    </div>
</div>

<button class="print-btn" onclick="window.print()">
    Print / Save as PDF
</button>

</body>
</html>
