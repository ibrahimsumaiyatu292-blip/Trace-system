<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\Asset;
use App\Models\Procurement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProcurementController extends Controller
{
    /**
     * Display a listing of procurements.
     */
    public function index(): View
    {
        $procurements = Procurement::with('linkedAsset')->latest()->paginate(10);

        return view('procurements.index', compact('procurements'));
    }

    /**
     * Show the form for creating a new procurement.
     */
    public function create(): View
    {
        Gate::authorize('create_asset');

        $assets = Asset::orderBy('name')->get();

        return view('procurements.create', compact('assets'));
    }

    /**
     * Store a newly created procurement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create_asset');

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'item_description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,approved,completed',
            'linked_asset_id' => 'nullable|exists:assets,id',
        ]);

        $procurement = Procurement::create($validated);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_table' => 'procurements',
            'target_id' => $procurement->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('procurements.index')->with('success', 'Procurement record created successfully.');
    }

    /**
     * Display the specified procurement.
     */
    public function show(Procurement $procurement): View
    {
        $procurement->load('linkedAsset');

        return view('procurements.show', compact('procurement'));
    }

    /**
     * Show the form for editing the specified procurement.
     */
    public function edit(Procurement $procurement): View
    {
        if (! auth()->user()->can('edit_asset') && ! auth()->user()->hasAnyRole(['admin', 'Administrator', 'Procurement Officer'])) {
            abort(403, 'Unauthorized action.');
        }

        $assets = Asset::orderBy('name')->get();

        return view('procurements.edit', compact('procurement', 'assets'));
    }

    /**
     * Update the specified procurement in storage.
     */
    public function update(Request $request, Procurement $procurement): RedirectResponse
    {
        if (! auth()->user()->can('edit_asset') && ! auth()->user()->hasAnyRole(['admin', 'Administrator', 'Procurement Officer'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'item_description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,approved,completed',
            'linked_asset_id' => 'nullable|exists:assets,id',
        ]);

        $procurement->update($validated);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_table' => 'procurements',
            'target_id' => $procurement->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('procurements.index')->with('success', 'Procurement record updated successfully.');
    }

    /**
     * Remove the specified procurement from storage.
     */
    public function destroy(Request $request, Procurement $procurement): RedirectResponse
    {
        Gate::authorize('delete_asset');

        $procurementId = $procurement->id;
        $procurement->delete();

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_table' => 'procurements',
            'target_id' => $procurementId,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('procurements.index')->with('success', 'Procurement record deleted successfully.');
    }
}
