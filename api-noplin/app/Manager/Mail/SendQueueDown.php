<?php

namespace App\Manager\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQueueDown extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     *
     */
    public function __construct()
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('queue_down')->with('time', now()->format('H:i:s'));
    }
}
