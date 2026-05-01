<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\BillplzService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
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

    public function upload(StorePaymentRequest $request, Booking $booking): \Illuminate\Http\RedirectResponse
    {
        $this->paymentService->uploadProof($booking, $request->file('payment_proof'));

        return redirect()->route('booking.confirm', $booking);
    }

    /** Redirect user to Billplz payment page. */
    public function billplzPay(Booking $booking): \Illuminate\Http\RedirectResponse
    {
        $bill = $this->billplzService->createBill($booking);

        $booking->payment()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount'          => $booking->package->price,
                'method'          => 'billplz',
                'status'          => 'pending',
                'transaction_ref' => $bill['id'],
            ]
        );

        $booking->update(['status' => 'payment_review']);

        return redirect()->away($bill['url']);
    }

    /** Billplz webhook — fires when payment status changes (paid or failed). */
    public function billplzCallback(Request $request): \Illuminate\Http\Response
    {
        $data = $request->all();

        if (! $this->billplzService->verifySignature($data)) {
            abort(400, 'Invalid signature');
        }

        $payment = Payment::where('transaction_ref', $data['id'])->first();

        if ($payment && $data['paid'] === 'true') {
            $this->paymentService->approve($payment->booking);
        }

        return response('OK', 200);
    }

    /** Billplz redirects customer back here after payment attempt. */
    public function billplzRedirect(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Redirect params come as billplz[key]=value
        $raw = $request->all();

        // Flatten billplz[...] keys into a plain array for signature verification
        $data = [];
        foreach ($raw as $key => $value) {
            if (preg_match('/^billplz\[(.+)\]$/', $key, $m)) {
                $data[$m[1]] = $value;
            } else {
                $data[$key] = $value;
            }
        }

        if (! $this->billplzService->verifySignature($data)) {
            abort(400, 'Invalid signature');
        }

        $payment = Payment::where('transaction_ref', $data['id'])->first();

        if (! $payment) {
            abort(404);
        }

        return redirect()->route('booking.confirm', $payment->booking_id);
    }
}
