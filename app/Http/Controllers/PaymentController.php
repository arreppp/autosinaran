<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\BillplzService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private BillplzService $billplzService,
    ) {}

    public function show(Booking $booking): Response
    {
        return Inertia::render('Booking/Step3Payment', [
            'booking' => $booking->load('package'),
        ]);
    }

    public function initiate(Booking $booking): RedirectResponse
    {
        $booking->load('package');

        $bill = $this->billplzService->createBill($booking);

        $this->paymentService->initiateBillplz($booking, $bill['id']);

        return redirect($bill['url']);
    }

    public function return(Booking $booking, Request $request): RedirectResponse
    {
        $paid  = $request->input('billplz.paid') === 'true';
        $billId = $request->input('billplz.id', '');

        if ($paid && $this->billplzService->verifyXSignature($request->all())) {
            $this->paymentService->confirmBillplz($booking, $billId);

            return redirect()->route('booking.payment.success', $booking);
        }

        $this->paymentService->markFailed($booking);

        return redirect()->route('booking.payment.failed', $booking);
    }

    public function callback(Request $request): JsonResponse
    {
        $billId = $request->input('billplz.id', '');
        $paid   = $request->input('billplz.paid') === 'true';

        if ($paid && $this->billplzService->verifyXSignature($request->all())) {
            $payment = Payment::where('transaction_ref', $billId)->first();

            if ($payment && $payment->status !== 'paid') {
                $this->paymentService->confirmBillplz($payment->booking, $billId);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function success(Booking $booking): Response
    {
        return Inertia::render('Booking/PaymentSuccess', [
            'booking' => $booking->load(['package', 'payment']),
        ]);
    }

    public function failed(Booking $booking): Response
    {
        return Inertia::render('Booking/PaymentFailed', [
            'booking' => $booking->load(['package', 'payment']),
        ]);
    }

    public function receipt(Booking $booking): HttpResponse
    {
        $booking->load(['package', 'payment']);

        return response()->view('receipt', ['booking' => $booking])
            ->header('Content-Type', 'text/html');
    }
}
