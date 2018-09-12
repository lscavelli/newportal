<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    public $attributes;
    private $template;
    public $to;
    public $from;

    /**
     * Create a new message instance.
     * @param $from
     * @param $to
     * @param $template
     * @param $attr
     */
    public function __construct($from, $to,$template,$attr)
    {
        $this->attributes = $attr;
        $this->template = $template;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->from($this->from['address'],$this->from['name']);

        foreach($this->to as $recipient) {
            $this->to($recipient['address'],$recipient['name']);
        }

        $this->subject("Richiesta informazioni");

        // Return view
        return $this->view($this->template);
    }
}
