<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use App\Models\Asset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AssetController extends Controller
{
    /**
     * Display a listing of the assets.
     */
    public function index(): View
    {
        $assets = Asset::with('creator')->latest()->paginate(10);

        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create(): View
    {
        Gate::authorize('create', Asset::class);

        return view('assets.create');
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Asset::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'assigned_project' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();

        $asset = Asset::create($validated);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_table' => 'assets',
            'target_id' => $asset->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset): View
    {
        $asset->load('creator');

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset): View
    {
        Gate::authorize('update', $asset);

        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset): RedirectResponse
    {
        Gate::authorize('update', $asset);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'assigned_project' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $asset->update($validated);

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_table' => 'assets',
            'target_id' => $asset->id,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Request $request, Asset $asset): RedirectResponse
    {
        Gate::authorize('delete', $asset);

        $assetId = $asset->id;
        $asset->delete();

        AccessLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_table' => 'assets',
            'target_id' => $assetId,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}
