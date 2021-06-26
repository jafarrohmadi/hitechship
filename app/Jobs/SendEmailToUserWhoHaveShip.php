<?php

namespace App\Jobs;

use App\HistoryShip;
use App\Mail\PertaminaShipped;
use App\Mail\SendShipTrackToUserWhoHaveShipMailable;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailToUserWhoHaveShip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $historyShip;
    protected $ship;
    protected $sendEmail;
    protected $userName;

    /**
     * Create a new job instance.
     *
     * @param HistoryShip $historyShip
     * @param Ship $ship
     * @param $sendEmail
     * @param $userName
     */
    public function __construct(HistoryShip $historyShip, Ship $ship , $sendEmail, $userName)
    {
        $this->historyShip = $historyShip;
        $this->ship = $ship;
        $this->sendEmail = $sendEmail;
        $this->userName = $userName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->sendEmail)->send(new SendShipTrackToUserWhoHaveShipMailable($this->historyShip, $this->ship, $this->userName));
    }
}
