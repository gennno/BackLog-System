<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlog;

class DashboardController extends Controller
{
public function index()
{
    // Total temuan
    $totalTemuan = Backlog::count();

    // Total backlog (all status)
    $totalBacklog = Backlog::count();

    // Backlog waiting for plan repair
    $planRepair = Backlog::where('plan_repair', 'Next PS')->count();

    // Backlog completed
    $backlogCompleted = Backlog::where('status', 'Close')->count();

    // Chart: backlog per status
    $statusData = Backlog::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

    // Chart: backlog per kategori (component) — no longer needed for chart, but you can keep it if used elsewhere
    $kategoriData = Backlog::selectRaw('component as kategori, COUNT(*) as count')
        ->groupBy('component')
        ->pluck('count', 'kategori');

    // ✅ New: Open vs Close Backlog counts
    $openBacklog  = Backlog::where('status', 'Open BL')->count();
    $closeBacklog = Backlog::where('status', 'Close')->count();

    return view('dashboard', [
        'totalTemuan'     => $totalTemuan,
        'totalBacklog'    => $totalBacklog,
        'planRepair'      => $planRepair,
        'backlogCompleted'=> $backlogCompleted,
        'statusData'      => $statusData,
        'kategoriData'    => $kategoriData, // can be removed if unused
        'openBacklog'     => $openBacklog,
        'closeBacklog'    => $closeBacklog,
    ]);
}

}
