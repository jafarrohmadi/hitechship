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
        return view('email.index');
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
