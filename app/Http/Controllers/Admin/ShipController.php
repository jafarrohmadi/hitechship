<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyShipRequest;
use App\Http\Requests\StoreShipRequest;
use App\Http\Requests\UpdateShipRequest;
use App\Ship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = Ship::all();

        return view('admin.ships.index', compact('ships'));
    }

    public function ourTable()
    {
        abort_if(Gate::denies('ship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = Ship::all();

        return view('admin.ships.our-table', compact('ships'));

    }

    public function create()
    {
        abort_if(Gate::denies('ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ships.create');
    }

    public function store(StoreShipRequest $request)
    {
        $ship = Ship::create($request->all());

        return redirect()->route('admin.ships.index');
    }

    public function edit(Ship $ship)
    {
        abort_if(Gate::denies('ship_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ships.edit', compact('ship'));
    }

    public function update(UpdateShipRequest $request, Ship $ship)
    {
        $ship->update($request->all());

        return redirect()->route('admin.ships.index');
    }

    public function show(Ship $ship)
    {
        abort_if(Gate::denies('ship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //$ship->load('shipHistoryShips', 'shipTerminals', 'shipUsers');
        $ship->load('shipHistoryShips', 'shipUsers');

        return view('admin.ships.show', compact('ship'));
    }

    public function destroy(Ship $ship)
    {
        abort_if(Gate::denies('ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ship->delete();

        return back();
    }

    public function massDestroy(MassDestroyShipRequest $request)
    {
        Ship::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
