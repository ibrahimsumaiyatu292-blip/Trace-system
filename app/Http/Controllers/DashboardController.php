<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Procurement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard with summary metrics.
     */
    public function __invoke(): View
    {
        $totalAssets = Asset::count();
        $totalProcurements = Procurement::count();

        $procurementsByStatus = [
            'pending' => Procurement::where('status', 'pending')->count(),
            'approved' => Procurement::where('status', 'approved')->count(),
            'completed' => Procurement::where('status', 'completed')->count(),
        ];

        return view('dashboard', compact(
            'totalAssets',
            'totalProcurements',
            'procurementsByStatus'
        ));
    }
}
