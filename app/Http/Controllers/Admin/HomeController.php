<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Ship;
use App\HistoryShip;

class HomeController extends BaseController
{
    public function index ()
    {
        return view('home');
    }

    public function Authentication ($user, $pass)
    {
        $this->accessId = $user;
        $this->passw    = $pass;
    }

    public function getInfoUtcTime ()
    {
        return parent::getInfoUtcTime();
    }

    public function getInfoErrors ()
    {
        return parent::getInfoErrors();
    }

    public function getInfoVersion ()
    {
        return parent::getInfoVersion();
    }

    public function getSubAccountInfos ()
    {
        return parent::getSubAccountInfos();
    }

    public function getBroadcastInfos ()
    {
        return parent::getBroadcastInfos();
    }

    public function getMobilesPaged ()
    {
        return parent::getMobilesPaged();
    }

    public function getReturnMessages ()
    {
        return parent::getReturnMessages();
    }

    public function getForwardStatus ()
    {
        return parent::getForwardStatus();
    }

    public function getForwardMessages ()
    {
        return parent::getForwardMessages();
    }

    public function getDashboard()
    {
        return view('admin.dashboard.index');
    }

    public function getDataShip()
    {
        $ship = Ship::with('shipHistoryShipsLatest')->orderBy('owner', 'asc')->get()->groupBy('owner');
        return $ship;
    }

    public function getDataShipById($id)
    {
        $ship = Ship::with('shipHistoryShipsLatest')->where('ships.id' , $id)->orderBy('owner', 'asc')->get()->groupBy('owner');

        return $ship;
    }

    public function getDataHistoryShipById($id)
    {
        $shipHistory = HistoryShip::join('ships', 'ships.id' , '=', 'history_ships.ship_id')->where('ships.ship_ids', $id)->get();
        return $shipHistory;
    }

}
