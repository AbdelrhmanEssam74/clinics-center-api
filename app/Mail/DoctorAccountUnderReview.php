<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorAccountUnderReview extends Mailable
{
    use Queueable, SerializesModels;

    public $doctor;
    public $status; // 'pending', 'rejected', or 'accepted'
    public $rejectionReason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($doctor, $status, $rejectionReason = null)
    {
        $this->doctor = $doctor;
        $this->status = $status;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = match($this->status) {
            'pending' => 'Your Doctor Account is Under Review',
            'accepted' => 'Your Doctor Account Has Been Approved!',
            'rejected' => 'Your Doctor Account Application Status',
            default => 'Regarding Your Doctor Account'
        };

        return $this->subject($subject)
                    ->markdown('emails.under_review');
    }
}
