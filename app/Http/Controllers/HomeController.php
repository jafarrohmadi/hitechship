<?php

namespace App\Http\Controllers;

use App\HistoryShip;
use App\Mail\SendShipTrackToUserWhoHaveShipMailable;
use App\Ship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use mikehaertl\wkhtmlto\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //   $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function leafleat($shipId)
    {
        $ship = Ship::with('shipHistoryShipsLatest')->where('ship_ids', $shipId)->first();
        $data = [];
        if ($ship && $ship->shipHistoryShipsLatest) {
            foreach (json_decode($ship->shipHistoryShipsLatest[0]['payload'])->Fields as $field) {
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

            $data['id'] =  $ship->id;
            $data['name'] =  $ship->name;
            $data['eventTime'] = strtotime($ship->shipHistoryShipsLatest[0]['message_utc']) + 7 * 60 * 60 * 1000;
            $data['heading'] =  $heading ?? 0;
            $data['speed'] =  $speed ?? 0;
            $data['latitude'] =  $latitude ?? 0;
            $data['longitude'] =  $longitude ?? 0;
        }

        return view('admin.dashboard.leaf', compact('data'));
    }

    public function printMapLeafleat($id)
    {
        $siteURL = "http://hitechship.herokuapp.com/leafleat/01035506SKYB6F7";

        $googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$siteURL&screenshot=true");

        $googlePagespeedData = json_decode($googlePagespeedData, true);
        $screenshot = $googlePagespeedData['screenshot']['data'];
        $screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);

        echo "<img src=\"data:image/jpeg;base64,".$screenshot."\" />";
    }

    public function printBlob()
    {
        echo "<img src=\"data:image/jpeg;base64,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\" />";
    }
    public function mail()
    {
        $historyShip = HistoryShip::where('history_ids', 5471584126)->first();
        $ship = Ship::where('id', 4)->first();
        $userName = 'oyo';
        $name = 'Krunal';
        Mail::to('rohmadijafar@gmail.com')->send(new SendShipTrackToUserWhoHaveShipMailable($historyShip, $ship, $userName));

        return 'Email was sent';
       // return view('email.sendGpsToUser');
    }


}
