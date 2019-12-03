<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTerminalShipRequest;
use App\Http\Requests\StoreTerminalShipRequest;
use App\Http\Requests\UpdateTerminalShipRequest;
use App\Ship;
use App\Terminal;
use App\TerminalShip;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TerminalShipController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('terminal_ship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $terminalShips = TerminalShip::all();

        return view('admin.terminalShips.index', compact('terminalShips'));
    }

    public function create()
    {
        abort_if(Gate::denies('terminal_ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = Ship::all()->pluck('name', 'id');

        $terminals = Terminal::all()->pluck('name', 'id');

        return view('admin.terminalShips.create', compact('ships', 'terminals'));
    }

    public function store(StoreTerminalShipRequest $request)
    {
        $terminalShip = TerminalShip::create($request->all());
        $terminalShip->ships()->sync($request->input('ships', []));
        $terminalShip->terminals()->sync($request->input('terminals', []));

        return redirect()->route('admin.terminal-ships.index');
    }

    public function edit(TerminalShip $terminalShip)
    {
        abort_if(Gate::denies('terminal_ship_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = Ship::all()->pluck('name', 'id');

        $terminals = Terminal::all()->pluck('name', 'id');

        $terminalShip->load('ships', 'terminals');

        return view('admin.terminalShips.edit', compact('ships', 'terminals', 'terminalShip'));
    }

    public function update(UpdateTerminalShipRequest $request, TerminalShip $terminalShip)
    {
        $terminalShip->update($request->all());
        $terminalShip->ships()->sync($request->input('ships', []));
        $terminalShip->terminals()->sync($request->input('terminals', []));

        return redirect()->route('admin.terminal-ships.index');
    }

    public function show(TerminalShip $terminalShip)
    {
        abort_if(Gate::denies('terminal_ship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $terminalShip->load('ships', 'terminals');

        return view('admin.terminalShips.show', compact('terminalShip'));
    }

    public function destroy(TerminalShip $terminalShip)
    {
        abort_if(Gate::denies('terminal_ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $terminalShip->delete();

        return back();
    }

    public function massDestroy(MassDestroyTerminalShipRequest $request)
    {
        TerminalShip::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
