<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CronData;
use App\Http\Controllers\BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Ship;
use App\HistoryShip;

class HomeController extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function Authentication($user, $pass)
    {
        $this->accessId = $user;
        $this->passw    = $pass;
    }

    public function getInfoUtcTime()
    {
        return parent::getInfoUtcTime();
    }

    public function getInfoErrors()
    {
        return parent::getInfoErrors();
    }

    public function getInfoVersion()
    {
        return parent::getInfoVersion();
    }

    public function getSubAccountInfos()
    {
        return parent::getSubAccountInfos();
    }

    public function getBroadcastInfos()
    {
        return parent::getBroadcastInfos();
    }

    public function getMobilesPaged()
    {
        return parent::getMobilesPaged();
    }

    public function getReturnMessages()
    {
        return parent::getReturnMessages();
    }

    public function getForwardStatus()
    {
        return parent::getForwardStatus();
    }

    public function getForwardMessages()
    {
        return parent::getForwardMessages();
    }

    public function getDashboard()
    {
        return view('admin.dashboard.index');
    }

    public function getDataShip()
    {
        $user = User::find(Auth::id());
        
        if (Auth::id() !== 1) {
            $ship = Ship::with('shipHistoryShipsLatest')
                ->join('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->join('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->join('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->join('users', 'terminal_user.user_id', '=', 'users.id')
                ->where('users.id', Auth::id())
                ->select('ships.*' )
                ->orderBy('owner', 'asc')
                ->get()->groupBy('owner');
        } else {
            $ship = Ship::with('shipHistoryShipsLatest')
                ->orderBy('owner', 'asc')
                ->get()->groupBy('owner');
        }

        return $ship;
    }

    public function getDataShipById($id)
    {
        $ship =
            Ship::with('shipHistoryShipsLatest')->where('ships.id', $id)->orderBy('owner', 'asc')->get()->groupBy('owner');

        return $ship;
    }

    public function getDataHistoryShipById($id)
    {
        $shipHistory =
            HistoryShip::join('ships', 'ships.id', '=', 'history_ships.ship_id')->where('ships.ship_ids', $id)->get();
        return $shipHistory;
    }

    public function getAverageSpeed($data)
    {
        $val      = [];
        $sendData = [];

        foreach (json_decode($data) as $datas) {
            $val[$datas[1]][] = $datas[2];
        }

        foreach ($val as $ship_id => $date) {
            $speed = [];

            $shipName = Ship::select('name')->where('id', $ship_id)->first();

            $shipHistory = HistoryShip::whereBetween('message_utc', [ min($date), max($date) ])
                ->where('ship_id', $ship_id)
                ->pluck('payload');

            foreach ($shipHistory as $payload) {
                foreach (json_decode($payload)->Fields as $fields)

                    if (strtolower($fields->Name) === 'speed') {
                        $speed[] = $fields->Value * 0.1;
                    }

            }

            $speed      = array_sum($speed) / count($speed);
            $sendData[] = [ 'name' => $shipName->name, 'speed' => round($speed, 4) ];
        }

        return $sendData;
    }
}
