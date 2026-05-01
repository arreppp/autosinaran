<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $todayBookings    = Booking::whereDate('booking_date', today())->count();
        $pendingPayments  = Booking::where('status', 'payment_review')->count();
        $totalRevenue     = Payment::where('status', 'paid')->sum('amount');
        $recentBookings   = Booking::with('package')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Admin/Dashboard', compact(
            'todayBookings',
            'pendingPayments',
            'totalRevenue',
            'recentBookings'
        ));
    }
}
