<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $demo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('love.ya93@yandex.com')
            ->markdown('mails.accept_transfer')
            ->with(
                [
                    'sum' => $this->data['sum'],
                    'name' => $this->data['name'],
                    'accept_link' => $this->data['accept_link'],
                    'cancel_link' => $this->data['cancel_link'],
                ]);
    }
}
