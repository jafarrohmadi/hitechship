<?php
namespace App\Http\Controllers\Admin;

use App\HistoryShip;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyHistoryShipRequest;
use App\Http\Requests\StoreHistoryShipRequest;
use App\Http\Requests\UpdateHistoryShipRequest;
use App\Ship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LogSendPertaminaController extends Controller
{
    public function index (Request $request)
    {
        return view('admin.historyShips.index');
    }

    public function resend()
    {

    }

}
