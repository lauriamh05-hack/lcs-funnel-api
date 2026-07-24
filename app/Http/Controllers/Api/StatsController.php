<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;

class StatsController extends Controller
{
    public function index()
    {
        $total = Lead::count();
        $wantsWhatsapp = Lead::where('wants_whatsapp', true)->count();
        $reserved = Lead::whereNotNull('open_day_slot')->count();
        $enrolled = Lead::where('status', 'inscrit')->count();

        $bySource = Lead::selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->get();

        return response()->json([
            'total_leads' => $total,
            'wants_whatsapp' => $wantsWhatsapp,
            'reserved_open_day' => $reserved,
            'enrolled' => $enrolled,
            'conversion_rate' => $total > 0 ? round(($enrolled / $total) * 100, 1) : 0,
            'by_source' => $bySource,
            'recent_leads' => Lead::latest()->take(10)->get(['id', 'name', 'whatsapp', 'source', 'status', 'created_at']),
        ]);
    }

    public function markEnrolled(Lead $lead)
    {
        $lead->update(['status' => 'inscrit']);
        return response()->json(['message' => 'Lead marqué comme inscrit.']);
    }
}
