<?php

namespace App\Services;

use App\Models\BlockedDate;
use App\Models\Booking;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BookingService
{
    private const SLOTS_PER_DAY = 5;

    public function getAvailability(Package $package, string $month): array
    {
        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end   = $start->copy()->endOfMonth();
        $today = Carbon::today();

        $bookingCounts = Booking::where('package_id', $package->id)
            ->whereBetween('booking_date', [$start, $end])
            ->whereIn('status', ['pending_payment', 'payment_review', 'confirmed'])
            ->selectRaw('booking_date, COUNT(*) as count')
            ->groupBy('booking_date')
            ->pluck('count', 'booking_date')
            ->mapWithKeys(fn($count, $date) => [Carbon::parse($date)->format('Y-m-d') => $count]);

        $blockedDates = BlockedDate::whereBetween('date', [$start, $end])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->flip();

        $availability = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');

            if ($current->lt($today)) {
                $availability[$dateStr] = 'past';
            } elseif (isset($blockedDates[$dateStr])) {
                $availability[$dateStr] = 'blocked';
            } elseif (($bookingCounts[$dateStr] ?? 0) >= self::SLOTS_PER_DAY) {
                $availability[$dateStr] = 'full';
            } else {
                $availability[$dateStr] = 'available';
            }

            $current->addDay();
        }

        return $availability;
    }

    public function isDateAvailable(Package $package, Carbon $date): bool
    {
        if (BlockedDate::whereDate('date', $date)->exists()) {
            return false;
        }

        $count = Booking::where('package_id', $package->id)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending_payment', 'payment_review', 'confirmed'])
            ->count();

        return $count < self::SLOTS_PER_DAY;
    }
}
