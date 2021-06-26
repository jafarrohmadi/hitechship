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
use App\Shipp;
use App\HistoryShip;
use Illuminate\Support\Facades\DB;

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
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '-1');
        // $user = User::find(Auth::id());
        $user = User::join('role_user', 'users.id', '=', 'role_user.user_id')->where('users.id', Auth::id())->first();
        // dd(Auth::user()->name);
        if ($user->role_id == 3) {
            $manager = Manager::all()->pluck('manager_id')->toArray();
            
            $shiptwo = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                //->rightJoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                //->rightJoin('users', 'terminal_user.user_id', '=', 'users.id')
                //->select('ships.*', 'terminals.name As name_terminal', 'users.name As owner', 'users.id As userId')
                ->select('ships.*', 'users.name As owner', 'users.id As userId')
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1)
                ->where('users.id', Auth::id());

            $shipOne = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                //->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                //->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
                //->select('ships.*', 'terminals.name As name_terminal', 'users.name As owner', 'users.id As userId')
                ->select('ships.*', 'users.name As owner', 'users.id As userId')
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
            
            // dd($ship->where('userId',4));
            $ship = $ship->where('userId',Auth::user()->id)->groupBy('owner')->map(function ($query) {
                return $query->groupBy('owner');
            });
        } else if($user->role_id == 2){

            $manager = Manager::all()->pluck('manager_id')->toArray();
            $allUser = User::all()->whereNotIn('id', $manager)->pluck('id')->toArray();
            $idmanager = Manager::all()->where('manager_id', Auth::id())->first();

            //$userManager = DB::table('manager_user')->where('manager_user.manager_id', $idmanager)->get();
            // dd($idmanager->id);

            $shiptwo = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                //->rightJoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                //->rightJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'users.name As owner', 'users.id As userId')
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1);

            $shipOne     = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                //->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                //->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'users.name As owner', 'users.id As userId')
                ->union($shiptwo)
                ->where('users.id', Auth::id())
                ->whereNotIn('users.id', $manager)
                ->get();
            
            $ship        = $shipOne
                ->map(function ($query) use ($idmanager) {
                    // $user = User::join('manager_user', 'users.id', '=', 'manager_user.user_id')
                    //     ->join('managers', 'manager_user.manager_id', '=', 'managers.id')
                    //     ->select('managers.manager_id As managerId')
                    //     ->where('users.id', $query->userId)->first();
                    // dd($user);
                    $user = DB::table('users')->join('manager_user', 'users.id', '=', 'manager_user.user_id')
                        ->where('users.id',$query->userId)
                        ->where('manager_user.manager_id',$idmanager->id)->first();
                        // dd($idmanager[1]->manager_id);
                            
                        
                    if ($user) {
                        $managerName           = User::where('id', $idmanager->manager_id)->first();
                        $query['manager_id']   = $idmanager->id;
                        $query['manager_name'] = $managerName->name;
                    } else {
                        $query['manager_id']   = 0;
                        $query['manager_name'] = '';
                    }
                    return $query;
                });
                
            
                
                
                
            $manager     = $ship->pluck('manager_id')->filter()->toArray();
            $terminalUse = $ship->pluck('userId')->filter()->toArray();
            // dd($terminalUse);
            $notUseTerminal = User::whereNotIn('id', $terminalUse)->get()->pluck('id')->toArray();

            foreach ($notUseTerminal as $notUseTerminals) {
                $userss = Ship::with('shipHistoryShipsLatest')
                    ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                    ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                    //->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                    //->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
                    ->select('ships.*', 'users.name As owner')
                    ->where('users.id', $notUseTerminals)
                    ->first();

                $ship->push($userss);
            }

        //    dd($ship);

            $usersManagerNotUse = [];
            $notUseManager      = Manager::whereNotIn('manager_id', [Auth::id()])->get()->pluck('manager_id')->toArray();
            foreach ($notUseManager as $notUseManagers) {
                $userss                             = User::where('id', $notUseManagers)->first();
                $usersManagerNotUse['manager_id']   = $notUseManagers;
                $usersManagerNotUse['manager_name'] = $userss->name;
            }
            $ship->push($usersManagerNotUse);
            // dd($ship->where('manager_id',$idmanager[1]->id));
            $ship = $ship->where('manager_id', $idmanager->id)->groupBy('manager_name')->map(function ($query) {
                
                return $query->groupBy('owner');
            });
        } else {
            $manager = Manager::all()->pluck('manager_id')->toArray();
            // dd($manager);
            $shiptwo = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                //->rightJoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                //->rightJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->select('ships.*', 'users.name As owner', 'users.id As userId')
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1);

            $shipOne     = Ship::with('shipHistoryShipsLatest')
                ->rightjoin('ship_user', 'ships.id', '=', 'ship_user.ship_id')
                ->rightjoin('users', 'ship_user.user_id', '=', 'users.id')
                //->leftjoin('terminal_user', 'terminals.id', '=', 'terminal_user.terminal_id')
                //->leftJoin('users', 'terminal_user.user_id', '=', 'users.id')
                ->leftJoin('manager_user', 'users.id', '=', 'manager_user.user_id')
                // ->leftJoin('managers', 'manager_user.manager_id', '=', 'managers.manager_id')
                ->select('ships.*', 'users.name As owner', 'users.id As userId','manager_user.manager_id As managerId')
                ->whereNotIn('users.id', $manager)
                ->where('users.id', '!=', 1)
                //->limit(18)
                // ->whereNotNull('managers.manager_id')
                ->get();
            
                // dd($shipOne);
            $ship        = $shipOne
                ->map(function ($query) use ($manager) {
                    $user = User::join('manager_user', 'users.id', '=', 'manager_user.user_id')
                        ->join('managers', 'manager_user.manager_id', '=', 'managers.id')
                        ->select('managers.manager_id As managerId')
                        ->where('users.id', $query->userId)->first();
                    
                    $manager = Manager::where('managers.id', $query->managerId)->first();

                    // dd($query);
                    if ($user) {
                        //if($manager->manager_id == null)
                        if(!is_object($manager)) {
                            $query['manager_id']   = 0;
                            $query['manager_name'] = '';
                        } else {
                            $managerName           = User::where('id', $manager->manager_id)->first();
                            $query['manager_id']   = $manager->manager_id;
                            $query['manager_name'] = $managerName->name;
                        }
                    } else {
                        $query['manager_id']   = 0;
                        $query['manager_name'] = '';
                    }
                    return $query;
                });


            $manager     = $ship->pluck('manager_id')->filter()->toArray();
            

            // dd($ship);

            $usersManagerNotUse = [];
            $notUseManager      = Manager::whereNotIn('manager_id', $manager)->get()->pluck('manager_id')->toArray();
            foreach ($notUseManager as $notUseManagers) {
                $userss                             = User::where('id', $notUseManagers)->first();
                $usersManagerNotUse['manager_id']   = $notUseManagers;
                $usersManagerNotUse['manager_name'] = $userss->name;
            }
            $ship->push($usersManagerNotUse);
            // dd($ship->groupBy('manager_name'));
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
