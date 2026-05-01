<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Packages/Index', [
            'packages' => Package::latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Packages/Form', ['package' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'items'       => ['required', 'array', 'min:1'],
            'items.*'     => ['required', 'string', 'max:100'],
            'is_active'   => ['boolean'],
        ]);

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package created.');
    }

    public function edit(Package $package): Response
    {
        return Inertia::render('Admin/Packages/Form', compact('package'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'items'       => ['required', 'array', 'min:1'],
            'items.*'     => ['required', 'string', 'max:100'],
            'is_active'   => ['boolean'],
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->update(['is_active' => false]);

        return redirect()->route('admin.packages.index')->with('success', 'Package deactivated.');
    }
}
