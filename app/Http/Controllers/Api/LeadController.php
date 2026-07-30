<?php

namespace App\Http\Controllers\Api;

use App\Mail\LeadGuideMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        if (empty($validated['email']) && empty($validated['whatsapp'])) {
            return response()->json([
                'message' => 'Un email ou un numéro WhatsApp est requis.'
            ], 422);
        }

        $lead = Lead::create([
            ...$validated,
            'source' => $request->input('source', 'tiktok_lcs'),
        ]);

        if ($lead->email) {
            Mail::to($lead->email)->send(new LeadGuideMail($lead));
        }

        return response()->json([
            'message' => 'Merci ! Ton guide arrive par email/WhatsApp.',
            'lead_id' => $lead->id,
        ], 201);
    }
}
