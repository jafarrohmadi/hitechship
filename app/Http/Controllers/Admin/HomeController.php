<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Ship;

class HomeController extends BaseController
{
    public function index ()
    {
        $settings1         = [
            'chart_title' => 'History data',
            'chart_type' => 'latest_entries',
            'report_type' => 'group_by_date',
            'model' => 'App\\User',
            'group_by_field' => 'email_verified_at',
            'group_by_period' => 'day',
            'aggregate_function' => 'count',
            'filter_field' => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class' => 'col-md-12',
            'entries_number' => '20',
            'fields' => [
                'name' => '',
            ],
        ];
        $settings1['data'] = [];
        if (class_exists($settings1['model'])) {
            $settings1['data'] = $settings1['model']::latest()
                ->take($settings1['entries_number'])
                ->get();
        }
        if (!array_key_exists('fields', $settings1)) {
            $settings1['fields'] = [];
        }
        $settings2 = [
            'chart_title' => 'Terminal',
            'chart_type' => 'pie',
            'report_type' => 'group_by_date',
            'model' => 'App\\Terminal',
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'aggregate_function' => 'count',
            'filter_field' => 'created_at',
            'group_by_field_format' => 'Y-m-d H:i:s',
            'column_class' => 'col-md-6',
            'entries_number' => '5',
        ];
        $chart2    = new LaravelChart($settings2);
        return view('home', compact('settings1', 'chart2'));
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
        $ship = Ship::with('shipHistoryShipsLatest')->orderBy('owner', 'desc')->get()->groupBy('owner');
        
        return $ship;
    }

    public function getDataShipById($id)
    {
        $ship = Ship::with('shipHistoryShipsLatest')->where('ships.id' , $id)->orderBy('owner', 'desc')->get()->groupBy('owner');
        
        return $ship;
    }

}
