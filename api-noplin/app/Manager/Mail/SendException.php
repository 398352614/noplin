<?php

namespace App\Manager\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendException extends Mailable
{
    use Queueable, SerializesModels;

    public string $exception;

    /**
     * Create a new message instance.
     *
     */
    public function __construct($e)
    {
        $this->exception = $e;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->markdown('exception')->with('time', now()->format('H:i:s'));
    }
}
