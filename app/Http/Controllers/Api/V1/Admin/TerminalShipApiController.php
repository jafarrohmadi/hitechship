<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTerminalShipRequest;
use App\Http\Requests\UpdateTerminalShipRequest;
use App\Http\Resources\Admin\TerminalShipResource;
use App\TerminalShip;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TerminalShipApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('terminal_ship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TerminalShipResource(TerminalShip::with(['ships', 'terminals'])->get());
    }

    public function store(StoreTerminalShipRequest $request)
    {
        $terminalShip = TerminalShip::create($request->all());
        $terminalShip->ships()->sync($request->input('ships', []));
        $terminalShip->terminals()->sync($request->input('terminals', []));

        return (new TerminalShipResource($terminalShip))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TerminalShip $terminalShip)
    {
        abort_if(Gate::denies('terminal_ship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TerminalShipResource($terminalShip->load(['ships', 'terminals']));
    }

    public function update(UpdateTerminalShipRequest $request, TerminalShip $terminalShip)
    {
        $terminalShip->update($request->all());
        $terminalShip->ships()->sync($request->input('ships', []));
        $terminalShip->terminals()->sync($request->input('terminals', []));

        return (new TerminalShipResource($terminalShip))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TerminalShip $terminalShip)
    {
        abort_if(Gate::denies('terminal_ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $terminalShip->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
