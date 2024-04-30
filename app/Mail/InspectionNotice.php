<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class InspectionNotice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $vehicles;
    public $timeFrame;

    public function __construct($vehicles, $timeFrame)
    {
        $this->vehicles = $vehicles;
        $this->timeFrame = $timeFrame;
    }

    public function build()
    {
        return $this->view('emails.inspectionNotice')
        ->with([
            'vehicles' => $this->vehicles,
            'timeFrame' => $this->timeFrame
        ]);
    }


    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Inspection Notice',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
