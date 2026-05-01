<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Package;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(): Response
    {
        return Inertia::render('Booking/Step1Package', [
            'packages' => Package::where('is_active', true)->get(),
        ]);
    }

    public function packages(): Response
    {
        return Inertia::render('Booking/Step1Package', [
            'packages' => Package::where('is_active', true)->get(),
        ]);
    }

    public function date(Package $package): Response
    {
        return Inertia::render('Booking/Step2Date', [
            'package' => $package,
        ]);
    }

    public function store(StoreBookingRequest $request): \Illuminate\Http\RedirectResponse
    {
        $package = Package::findOrFail($request->package_id);
        $date    = Carbon::parse($request->booking_date);

        if (! $this->bookingService->isDateAvailable($package, $date)) {
            return back()->withErrors(['booking_date' => 'This date is no longer available. Please choose another date.']);
        }

        $booking = Booking::create($request->validated());

        return redirect()->route('booking.payment', $booking);
    }

    public function confirm(Booking $booking): Response
    {
        return Inertia::render('Booking/Confirmation', [
            'booking' => $booking->load(['package', 'payment']),
        ]);
    }

    public function availability(Request $request, Package $package): JsonResponse
    {
        $month = $request->query('month', now()->format('Y-m'));

        return response()->json(
            $this->bookingService->getAvailability($package, $month)
        );
    }
}
