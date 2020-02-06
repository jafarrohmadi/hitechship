<?php

namespace App\Mail;

use App\HistoryShip;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertShipped extends Mailable
{
    use Queueable, SerializesModels;
    public $ship;
    public $historyShip;
    public $emailTerminal;

    /**
     * Create a new message instance.
     *
     * @param HistoryShip $historyShip
     * @param Ship $ship
     * @param $emailTerminal
     */
    public function __construct(HistoryShip $historyShip, Ship $ship, $emailTerminal)
    {
        $this->ship          = $ship;
        $this->historyShip   = $historyShip;
        $this->emailTerminal = $emailTerminal;
    }

    protected function getSubject()
    {
        return 'Report For ' . $this->ship->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->getSubject())->view('email.alertShipped');
    }
}
