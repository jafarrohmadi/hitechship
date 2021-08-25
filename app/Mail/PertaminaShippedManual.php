<?php

namespace App\Mail;

use App\Helpers\CronData;
use App\HistoryShip;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PertaminaShippedManual extends Mailable
{
    use Queueable, SerializesModels;

    public $subject , $content , $filename;
    /**
     * Create a new message instance.
     *
     * @param HistoryShip $historyShip
     * @param Ship $ship
     */
    public function __construct($subject , $content , $filename )
    {
        $this->subject = $subject ;
        $this->content = $content;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view([])->attachData($this->content, $this->filename, [
            'mime' => 'text/plain',
        ]);
    }
}
