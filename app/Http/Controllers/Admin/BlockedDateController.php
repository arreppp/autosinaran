<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlockedDateController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/BlockedDates/Index', [
            'blockedDates' => BlockedDate::orderBy('date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date'   => ['required', 'date', 'unique:blocked_dates,date'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        BlockedDate::create($request->only('date', 'reason'));

        return back()->with('success', 'Date blocked.');
    }

    public function destroy(BlockedDate $blockedDate): RedirectResponse
    {
        $blockedDate->delete();

        return back()->with('success', 'Date unblocked.');
    }
}
