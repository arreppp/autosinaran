<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Received</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f59e0b; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 24px; border: 1px solid #e5e7eb; }
        .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Payment Received</h1>
        <p>Auto Sinaran Workshop</p>
    </div>
    <div class="content">
        <p>Dear <strong>{{ $booking->customer_name }}</strong>,</p>
        <p>We have received your payment proof for booking <strong>{{ $booking->booking_number }}</strong>.</p>
        <p>Our team is currently reviewing your payment. You will receive a confirmation email within <strong>24 hours</strong>.</p>
        <p>Thank you for your patience!</p>
    </div>
    <div class="footer">
        <p>Auto Sinaran Workshop &mdash; Thank you for your booking!</p>
    </div>
</div>
</body>
</html>
