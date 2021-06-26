<?php

namespace App\Jobs;

use App\EmailAlertTerminal;
use App\HistoryShip;
use App\Mail\AlertShipped;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $historyShip;
    protected $ship;
    protected $emailTerminal;

    /**
     * Create a new job instance.
     *
     * @param HistoryShip $historyShip
     * @param Ship $ship
     * @param EmailAlertTerminal $emailTerminal
     */
    public function __construct(HistoryShip $historyShip, Ship $ship, EmailAlertTerminal $emailTerminal)
    {
        $this->historyShip   = $historyShip;
        $this->ship          = $ship;
        $this->emailTerminal = $emailTerminal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->emailTerminal)->send(new AlertShipped($this->historyShip, $this->ship, $this->emailTerminal));
    }
}
