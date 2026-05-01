<?php

namespace App\Services;

use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentService
{
    public function uploadProof(Booking $booking, UploadedFile $file): Payment
    {
        $path = $file->store('payment-proofs', 'private');

        $payment = $booking->payment()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount'             => $booking->package->price,
                'method'             => 'manual_transfer',
                'status'             => 'pending',
                'payment_proof_path' => $path,
            ]
        );

        $booking->update(['status' => 'payment_review']);

        return $payment;
    }

    public function approve(Booking $booking): void
    {
        $booking->payment()->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        $booking->update(['status' => 'confirmed']);

        Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));
    }

    public function reject(Booking $booking, ?string $reason = null): void
    {
        $booking->payment()->update(['status' => 'failed']);
        $booking->update([
            'status' => 'pending_payment',
            'notes'  => $reason ?? $booking->notes,
        ]);
    }
}
