<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;

class BillplzService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('billplz.sandbox', true)
            ? 'https://www.billplz-sandbox.com/api/v3'
            : 'https://www.billplz.com/api/v3';
    }

    public function createBill(Booking $booking): array
    {
        $response = Http::withBasicAuth(config('billplz.api_key'), '')
            ->post("{$this->baseUrl}/bills", [
                'collection_id'   => config('billplz.collection_id'),
                'email'           => $booking->customer_email,
                'mobile'          => $booking->customer_phone,
                'name'            => $booking->customer_name,
                'amount'          => (int) round($booking->package->price * 100),
                'description'     => "Booking {$booking->booking_number} – {$booking->package->name}",
                'callback_url'    => route('booking.payment.callback'),
                'redirect_url'    => route('booking.payment.return', $booking),
                'reference_1_label' => 'Booking No.',
                'reference_1'       => $booking->booking_number,
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Billplz bill creation failed: ' . $response->body());
        }

        return $response->json();
    }

    public function verifyXSignature(array $params): bool
    {
        $key = config('billplz.x_signature');

        if (empty($key)) {
            return true;
        }

        $xSig = $params['billplz']['x_signature'] ?? '';

        $fields = [
            $params['billplz']['id']                  ?? '',
            $params['billplz']['paid']                ?? '',
            $params['billplz']['paid_at']             ?? '',
            $params['billplz']['transaction_id']      ?? '',
            $params['billplz']['transaction_status']  ?? '',
        ];

        $computed = hash_hmac('sha256', implode('|', $fields), $key);

        return hash_equals($computed, $xSig);
    }
}
