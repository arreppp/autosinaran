<?php

namespace App\Services;

use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    public function initiateBillplz(Booking $booking, string $billId): Payment
    {
        $payment = $booking->payment()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount'          => $booking->package->price,
                'method'          => 'billplz',
                'status'          => 'pending',
                'transaction_ref' => $billId,
            ]
        );

        $booking->update(['status' => 'payment_review']);

        return $payment;
    }

    public function confirmBillplz(Booking $booking, string $billId): void
    {
        $booking->payment()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'method'          => 'billplz',
                'status'          => 'paid',
                'transaction_ref' => $billId,
                'paid_at'         => now(),
            ]
        );

        $booking->update(['status' => 'confirmed']);

        Mail::to($booking->customer_email)->send(new BookingConfirmed($booking));
    }

    public function markFailed(Booking $booking): void
    {
        $booking->payment()->where('booking_id', $booking->id)
            ->update(['status' => 'failed']);

        $booking->update(['status' => 'pending_payment']);
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
