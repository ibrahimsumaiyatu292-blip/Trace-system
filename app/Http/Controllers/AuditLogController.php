<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(): View
    {
        $logs = AccessLog::with('user')->latest()->paginate(15);

        return view('audit-logs.index', compact('logs'));
    }
}
