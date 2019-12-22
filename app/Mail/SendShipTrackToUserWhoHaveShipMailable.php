<?php

namespace App\Mail;

use App\HistoryShip;
use App\Ship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendShipTrackToUserWhoHaveShipMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $historyShip;
    public $ship;
    public $userName;
    public $heading;
    public $latitude;
    public $longitude;
    public $speed;
    public $last;
    public $image;

    /**
     * Create a new message instance.
     *
     * @param HistoryShip $historyShip
     * @param Ship $ship
     * @param $userName
     */
    public function __construct(HistoryShip $historyShip, Ship $ship, $userName)
    {
        $this->historyShip = $historyShip;
        $this->ship = $ship;
        $this->userName = $userName;
    }

    protected function getSubject()
    {
        return 'Gps Hourly Report ' . $this->ship->name;
    }

    public function printMapLeafleat($id)
    {
        $siteURL = "https://gps-tracking.asatamatek.com/leafleat/" . $id;

        $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true");

        $googlePagespeedData = json_decode($googlePagespeedData, true);
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);

        return $screenshot;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->image = $this->printMapLeafleat($this->ship->ship_ids);
        foreach (json_decode($this->historyShip->payload)->Fields as $field) {
            $field->Name = strtolower($field->Name);
            if ($field->Name === 'heading') {
                $this->heading = $field->Value * 0.1;
            }

            if ($field->Name === 'latitude') {
                $this->latitude = round($field->Value, 4);
            }

            if ($field->Name === 'longitude') {
                $this->longitude = round($field->Value, 4);
            }

            if ($field->Name === 'speed') {
                $this->speed = $field->Value;
            }
        }
        $this->last = date('d m Y H:i:s', strtotime($this->historyShip->message_utc) + (7 * 60 * 60));
        return $this->subject($this->getSubject())->view('email.sendShipTrackToUserWhoHaveShip');
    }
}
