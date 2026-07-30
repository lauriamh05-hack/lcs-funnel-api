<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadGuideMail extends Mailable
{
    use Queueable, SerializesModels;

    public Lead $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function build()
    {
        return $this->subject('Ton guide LCS 2026-2027 est arrivé 🎓')
            ->view('emails.lead-guide');
    }
}
