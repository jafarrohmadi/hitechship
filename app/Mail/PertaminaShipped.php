<?php

namespace App\Mail;

use App\HistoryShip;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PertaminaShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $ship, $historyShip;

    /**
     * Create a new message instance.
     *
     * @param HistoryShip $historyShip
     * @param Ship $ship
     */
    public function __construct(HistoryShip $historyShip, Ship $ship)
    {
        $this->ship = $ship;
        $this->historyShip = $historyShip;
    }

    public function getFile(): string
    {
      //  "YDXK","PELITA","$aims1,123644,A,0344.964,S,10617.813,E,11.7,186.5,101219,000.0,E*68"
        return  '"'.$this->ship->call_sign.'","'.$this->ship->name.'","'.$this->ship->owner.'","'.date('His', $this->historyShip->message_utc).'"
        ,"A",';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->attachData($this->pdf, 'name.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
}
