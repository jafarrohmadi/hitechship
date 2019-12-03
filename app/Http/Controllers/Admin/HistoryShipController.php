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

class HistoryShipController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = HistoryShip::with(['ships'])->select(sprintf('%s.*', (new HistoryShip)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'history_ship_show';
                $editGate      = 'history_ship_edit';
                $deleteGate    = 'history_ship_delete';
                $crudRoutePart = 'history-ships';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('latitute', function ($row) {
                return $row->latitute ? $row->latitute : "";
            });
            $table->editColumn('logitude', function ($row) {
                return $row->logitude ? $row->logitude : "";
            });
            $table->editColumn('time_ship', function ($row) {
                return $row->time_ship ? $row->time_ship : "";
            });
            $table->editColumn('ship', function ($row) {
                $labels = [];

                foreach ($row->ships as $ship) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $ship->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'ship']);

            return $table->make(true);
        }

        return view('admin.historyShips.index');
    }

    public function create()
    {
        abort_if(Gate::denies('history_ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = Ship::all()->pluck('name', 'id');

        return view('admin.historyShips.create', compact('ships'));
    }

    public function store(StoreHistoryShipRequest $request)
    {
        $historyShip = HistoryShip::create($request->all());
        $historyShip->ships()->sync($request->input('ships', []));

        return redirect()->route('admin.history-ships.index');
    }

    public function edit(HistoryShip $historyShip)
    {
        abort_if(Gate::denies('history_ship_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ships = Ship::all()->pluck('name', 'id');

        $historyShip->load('ships');

        return view('admin.historyShips.edit', compact('ships', 'historyShip'));
    }

    public function update(UpdateHistoryShipRequest $request, HistoryShip $historyShip)
    {
        $historyShip->update($request->all());
        $historyShip->ships()->sync($request->input('ships', []));

        return redirect()->route('admin.history-ships.index');
    }

    public function show(HistoryShip $historyShip)
    {
        abort_if(Gate::denies('history_ship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $historyShip->load('ships');

        return view('admin.historyShips.show', compact('historyShip'));
    }

    public function destroy(HistoryShip $historyShip)
    {
        abort_if(Gate::denies('history_ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $historyShip->delete();

        return back();
    }

    public function massDestroy(MassDestroyHistoryShipRequest $request)
    {
        HistoryShip::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
