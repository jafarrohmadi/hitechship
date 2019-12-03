<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipRequest;
use App\Http\Requests\UpdateShipRequest;
use App\Http\Resources\Admin\ShipResource;
use App\Ship;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ship_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShipResource(Ship::all());
    }

    public function store(StoreShipRequest $request)
    {
        $ship = Ship::create($request->all());

        return (new ShipResource($ship))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Ship $ship)
    {
        abort_if(Gate::denies('ship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShipResource($ship);
    }

    public function update(UpdateShipRequest $request, Ship $ship)
    {
        $ship->update($request->all());

        return (new ShipResource($ship))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Ship $ship)
    {
        abort_if(Gate::denies('ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ship->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
