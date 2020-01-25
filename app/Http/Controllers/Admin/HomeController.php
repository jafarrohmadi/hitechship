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
            $manager = Manager::all()->pluck('manager_id')->toArray();
            $shiptwo = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->rightjoin('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->rightJoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->rightJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'terminals.name As name', 'users.name As owner', 'users.id As userId')
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1)
                ->where('users.id', Auth::id());

            $shipOne = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->rightjoin('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'terminals.name As name', 'users.name As owner', 'users.id As userId')
                ->union($shiptwo)
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1)
                ->where('users.id', Auth::id())
                ->get();

            $ship = $shipOne
                ->map(function ($query) use ($manager) {
                    $user = User::join('manager_user', 'users.id', '=', 'manager_user.user_id')
                        ->join('managers', 'manager_user.manager_id', '=', 'managers.id')
                        ->select('managers.manager_id As managerId')
                        ->where('users.id', $query->userId)->first();
                    if ($user) {
                        $managerName           = User::where('id', $user->managerId)->first();
                        $query['manager_id']   = $user->managerId;
                        $query['manager_name'] = $managerName->name;
                    } else {
                        $query['manager_id']   = 0;
                        $query['manager_name'] = '';
                    }
                    return $query;
                });

            $manager            = $ship->pluck('manager_id')->toArray();
            $usersManagerNotUse = [];
            $notUseManager      = Manager::whereNotIn('manager_id', $manager)->get()->pluck('manager_id')->toArray();
            foreach ($notUseManager as $notUseManagers) {
                $userss                             = User::where('id', $notUseManagers)->first();
                $usersManagerNotUse['manager_id']   = $notUseManagers;
                $usersManagerNotUse['manager_name'] = $userss->name;
            }
            $ship->push($usersManagerNotUse);

            $ship = $ship->groupBy('manager_name')->map(function ($query) {
                return $query->groupBy('owner');
            });
        } else {
            $manager = Manager::all()->pluck('manager_id')->toArray();
            $shiptwo = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->rightjoin('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->rightJoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->rightJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'terminals.name As name', 'users.name As owner', 'users.id As userId')
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1);

            $shipOne = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_terminal', 'ships.id', '=', 'ship_terminal.ship_id')
                ->rightjoin('terminals', 'ship_terminal.terminal_id', '=', 'terminals.id')
                ->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                ->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'terminals.name As name', 'users.name As owner', 'users.id As userId')
                ->union($shiptwo)
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1)
                ->get();

            $ship               = $shipOne
                ->map(function ($query) use ($manager) {
                    $user = User::join('manager_user', 'users.id', '=', 'manager_user.user_id')
                        ->join('managers', 'manager_user.manager_id', '=', 'managers.id')
                        ->select('managers.manager_id As managerId')
                        ->where('users.id', $query->userId)->first();
                    if ($user) {
                        $managerName           = User::where('id', $user->managerId)->first();
                        $query['manager_id']   = $user->managerId;
                        $query['manager_name'] = $managerName->name;
                    } else {
                        $query['manager_id']   = 0;
                        $query['manager_name'] = '';
                    }
                    return $query;
                });
            $manager            = $ship->pluck('manager_id')->toArray();
            $usersManagerNotUse = [];
            $notUseManager      = Manager::whereNotIn('manager_id', $manager)->get()->pluck('manager_id')->toArray();
            foreach ($notUseManager as $notUseManagers) {
                $userss                             = User::where('id', $notUseManagers)->first();
                $usersManagerNotUse['manager_id']   = $notUseManagers;
                $usersManagerNotUse['manager_name'] = $userss->name;
            }
            $ship->push($usersManagerNotUse);

            $ship = $ship->groupBy('manager_name')->map(function ($query) {
                return $query->groupBy('owner');
            });
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
