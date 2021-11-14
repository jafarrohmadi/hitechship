<?php

namespace App\Mail;

use App\Helpers\CronData;
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

    private function getSubject()
    {
        return $this->ship->name.'-'.date('dmY-Hi');
    }

    public function printFloatWithLeadingZeros($num, $precision = 1, $leadingZeros = 3){
        $decimalSeperator = ".";
        $adjustedLeadingZeros = $leadingZeros + mb_strlen($decimalSeperator) + $precision;
        $pattern = "%0{$adjustedLeadingZeros}{$decimalSeperator}{$precision}f";
        return sprintf($pattern,$num);
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
        $callSign = $this->ship->call_sign ?? 'null';
        return  '"'.$callSign.'","'.$this->ship->name.'","$SKYSATU,'.date('His', strtotime($this->historyShip->message_utc)).',A,'.$latitude.','.$longitude.','.$this->printFloatWithLeadingZeros($speed).','.$this->printFloatWithLeadingZeros($heading).','.date('dmy').',000.0,E*68"' ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->getSubject())->view([])->attachData($this->setFormatPertamina(), $this->ship->name.'-'.date('dmY-Hi').'.chr', [
            'mime' => 'text/plain',
        ]);
    }
}
