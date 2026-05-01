<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Booking;
use App\Services\PaymentService;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function show(Booking $booking): Response
    {
        return Inertia::render('Booking/Step3Payment', [
            'booking' => $booking->load('package'),
        ]);
    }

    public function upload(StorePaymentRequest $request, Booking $booking): \Illuminate\Http\RedirectResponse
    {
        $this->paymentService->uploadProof($booking, $request->file('payment_proof'));

        return redirect()->route('booking.confirm', $booking);
    }
}
