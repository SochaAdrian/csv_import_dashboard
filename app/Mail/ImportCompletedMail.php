<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $import;

    /**
     * Create a new message instance.
     */
    public function __construct($import)
    {
        $this->import = $import;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('CSV Import Completed')
            ->view('emails.import_completed')
            ->with([
                'rows' => $this->import->rows,
            ]);
    }
}
