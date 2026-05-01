<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class BillplzService
{
    private string $baseUrl;
    private string $apiKey;
    private string $collectionId;
    private string $xSignatureKey;

    public function __construct()
    {
        $this->apiKey        = config('billplz.api_key');
        $this->collectionId  = config('billplz.collection_id');
        $this->xSignatureKey = config('billplz.x_signature');
        $this->baseUrl       = config('billplz.sandbox')
            ? 'https://www.billplz-sandbox.com/api/v3'
            : 'https://www.billplz.com/api/v3';
    }

    public function createBill(Booking $booking): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->asForm()
            ->post("{$this->baseUrl}/bills", [
                'collection_id'    => $this->collectionId,
                'email'            => $booking->customer_email,
                'mobile'           => $booking->customer_phone,
                'name'             => $booking->customer_name,
                'amount'           => (int) ($booking->package->price * 100),
                'callback_url'     => route('billplz.callback'),
                'redirect_url'     => route('billplz.redirect'),
                'description'      => "Auto Sinaran - {$booking->package->name}",
                'reference_1_label' => 'Booking No',
                'reference_1'      => $booking->booking_number,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Billplz bill creation failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Verify X-Signature from Billplz callback (POST) or redirect (GET).
     *
     * Billplz sorts params alphabetically, concatenates as key+value pairs
     * separated by "|", then signs with HMAC-SHA256.
     */
    public function verifySignature(array $data): bool
    {
        $incoming = $data['x_signature'] ?? '';
        unset($data['x_signature']);

        ksort($data);

        $content = implode('|', array_map(
            fn($k, $v) => $k . $v,
            array_keys($data),
            array_values($data),
        ));

        $computed = hash_hmac('sha256', $content, $this->xSignatureKey);

        return hash_equals($computed, $incoming);
    }
}
