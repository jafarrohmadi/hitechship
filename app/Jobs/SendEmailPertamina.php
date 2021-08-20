<?php

namespace App\Jobs;

use App\EmailSendPertamina;
use App\EmailTerminal;
use App\Helpers\CronData;
use App\HistoryShip;
use App\Mail\PertaminaShipped;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailPertamina implements
    ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $historyShip;
    protected $ship;
    protected $emailTerminal;

    /**
     * Create a new job instance.
     *
     * @param  HistoryShip  $historyShip
     * @param  Ship  $ship
     * @param  array  $emailTerminal
     */
    public function __construct(
        HistoryShip $historyShip,
        Ship $ship,
        array $emailTerminal
    ) {
        $this->historyShip   = $historyShip;
        $this->ship          = $ship;
        $this->emailTerminal = $emailTerminal;
    }

    private function setFormatPertamina(): string
    {
        foreach (json_decode($this->historyShip->payload)->Fields as $field) {
            $field->Name = strtolower($field->Name);
            if ($field->Name === 'latitude') {
                $latitude = $field->Value;
            }
            if ($field->Name === 'longitude') {
                $longitude = $field->Value;
            }

            if ($field->Name === 'speed') {
                $speed = $field->Value;
            }
            if ($field->Name === 'heading') {
                $heading = $field->Value;
            }

        }

        $latitude  = (new CronData())->DDtoNme($latitude).',S';
        $longitude = (new CronData())->DDtoNme($longitude).',E';
        $callSign  = $this->ship->call_sign ?? 'null';
        return '"'.$callSign.'","'.$this->ship->name.'","$SKYSATU,'.date('His',
                strtotime($this->historyShip->message_utc)).',A,'.$latitude.','.$longitude.','.$speed.','.$heading.','.date('dmy').',000.0,E*68"';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->ship->additional_email_ship != null) {
            $this->emailTerminal = explode(';', $this->ship->additional_email_ship);
        }
        foreach ($this->emailTerminal as $email) {
            Mail::to($email)->send(new PertaminaShipped($this->historyShip, $this->ship));
        }

        $data                        = new EmailSendPertamina();
        $data->ship_id               = $this->ship->id;
        $data->history_ship_id       = $this->historyShip->id;
        $data->last_seen_time        = date('Y-m-d H:i:s');
        $data->last_sent_destination = json_encode($this->emailTerminal);
        $data->last_sent_status      = 'Delivered';
        $data->subject               = $this->ship->name.date('dMY-Hi');
        $data->filename_chr          = $this->ship->name.date('dMY-Hi').'.chr';
        $data->content               = $this->setFormatPertamina();

        // check for failures
        if (Mail::failures()) {
            $data->last_sent_status = 'Failed';
        }
        $data->save();
    }
}
