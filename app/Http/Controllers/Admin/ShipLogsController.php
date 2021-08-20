<?php

namespace App\Http\Controllers\Admin;

use App\EmailSendPertamina;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyShipRequest;
use App\Http\Requests\StoreShipRequest;
use App\Http\Requests\UpdateShipRequest;
use App\Ship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipLogsController extends Controller
{
    public function index($id)
    {
        abort_if(Gate::denies('ship_logs'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = EmailSendPertamina::where('ship_id', $id)->get();

        return view('admin.ships.logs', compact('ships'));
    }

    public function storeResend()
    {

    }


}
