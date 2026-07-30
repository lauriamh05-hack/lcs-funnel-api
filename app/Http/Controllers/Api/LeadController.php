<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Http;
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
            Http::withHeaders([
                'api-key' => config('services.brevo.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [
            ['email' => $lead->email, 'name' => $lead->name],
                ],
                'subject' => 'Ton guide LCS 2026-2027 est arrivé 🎓',
            'htmlContent' => view('emails.lead-guide', ['lead' => $lead])->render(),
        ]);
}

        return response()->json([
            'message' => 'Merci ! Ton guide arrive par email/WhatsApp.',
            'lead_id' => $lead->id,
        ], 201);
    }
}
