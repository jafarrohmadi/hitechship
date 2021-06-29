<?php

namespace App\Jobs;

use App\EmailTerminal;
use App\HistoryShip;
use App\Mail\PertaminaShipped;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailPertamina implements ShouldQueue
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
     * @param EmailTerminal $emailTerminal
     */
    public function __construct(HistoryShip $historyShip, Ship $ship, EmailTerminal $emailTerminal)
    {
        $this->historyShip = $historyShip;
        $this->ship = $ship;
        $this->emailTerminal = $emailTerminal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->emailTerminal as $email)
        {
            Mail::to($email)->send(new PertaminaShipped($this->historyShip, $this->ship));
        }
    }
}
