<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request): Response
    {
        $bookings = Booking::with(['package', 'payment'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date,   fn($q) => $q->whereDate('booking_date', $request->date))
            ->when($request->package_id, fn($q) => $q->where('package_id', $request->package_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'filters'  => $request->only(['status', 'date', 'package_id']),
        ]);
    }

    public function show(Booking $booking): Response
    {
        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking->load(['package', 'payment']),
        ]);
    }

    public function approve(Booking $booking): RedirectResponse
    {
        $this->paymentService->approve($booking);

        return back()->with('success', 'Booking confirmed successfully.');
    }

    public function reject(Request $request, Booking $booking): RedirectResponse
    {
        $this->paymentService->reject($booking, $request->reason);

        return back()->with('success', 'Booking payment rejected.');
    }

    public function proof(Booking $booking): StreamedResponse
    {
        abort_unless($booking->payment?->payment_proof_path, 404);

        return Storage::disk('private')->download($booking->payment->payment_proof_path);
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->update(['status' => 'cancelled']);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking cancelled.');
    }
}
