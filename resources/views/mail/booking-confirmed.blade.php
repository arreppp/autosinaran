<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1a56db; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 24px; border: 1px solid #e5e7eb; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        .label { font-weight: bold; color: #6b7280; }
        .badge { background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 9999px; font-weight: bold; }
        .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Booking Confirmed!</h1>
        <p>Auto Sinaran Workshop</p>
    </div>
    <div class="content">
        <p>Dear <strong>{{ $booking->customer_name }}</strong>,</p>
        <p>Your booking has been confirmed. Please arrive at the workshop on the scheduled date.</p>

        <table style="width: 100%; margin-top: 16px;">
            <tr>
                <td class="label">Booking Number</td>
                <td><strong>{{ $booking->booking_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Package</td>
                <td>{{ $booking->package->name }}</td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td>{{ $booking->booking_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Vehicle</td>
                <td>{{ $booking->vehicle_model }} ({{ $booking->vehicle_plate }})</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td><span class="badge">Confirmed</span></td>
            </tr>
        </table>

        <p style="margin-top: 20px;">If you have any questions, please contact us.</p>
    </div>
    <div class="footer">
        <p>Auto Sinaran Workshop &mdash; Thank you for your booking!</p>
    </div>
</div>
</body>
</html>
