<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CronData;
use App\Http\Controllers\BaseController;
use App\Manager;
use App\Terminal;
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
            $shiptwo = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->rightjoin('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->rightJoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->rightJoin('users', 'terminal_user.user_id', '=', 'users.id')
              //  ->leftJoin('manager_user', 'users.id', '=', 'manager_user.user_id')
                //->leftJoin('managers', 'manager_user.manager_id', '=', 'managers.id')
                ->select('ships.*', 'terminals.name As name', 'users.name As owner')
               // ->orderBy('manager_id', 'asc')
                ;

            $ship = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->rightjoin('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
            //    ->rightjoin('manager_user', 'users.id', '=', 'manager_user.user_id')
          //      ->rightjoin('managers', 'manager_user.manager_id', '=', 'managers.id')
                ->select('ships.*', 'terminals.name As name', 'users.name As owner')
                // ->orderBy('manager_id', 'asc')
                ->union($shiptwo)
                ->get()->groupBy('owner');


//            $ship = Manager::leftjoin('manager_user', 'managers.id', '=', 'manager_user.manager_id')
//                ->rightjoin('users', 'manager_user.user_id', '=', 'users.id')
//                ->leftjoin('terminal_user', 'users.id', '=', 'terminal_user.user_id')
//                ->leftJoin('terminals', 'terminal_user.terminal_id', '=', 'terminals.id')
//                ->where('users.id' ,'!=', 1)
//                ->select('managers.id As managers', 'users.name as owner', 'terminals.name As name')
//                ->get();
//            $ship = User::rightjoin('managers', 'managers.manager_id', '=', 'users.id')
//
//                ->rightjoin('terminals', 'terminal_user.terminal_id', '=', 'terminals.id')
//                ->rightjoin('ship_terminal', 'terminals.id', '=', 'ship_terminal.terminal_id')
//                ->rightjoin('ships',  'ship_terminal.ship_id', '=','ships.id')
//                ->select('users.name As user_name', 'managers.id As manager_id', 'terminals.name As terminal_name')
//                ->get();


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
