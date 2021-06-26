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
    public function index (Request $request)
    {
        if ($request->ajax()) {
            $query = HistoryShip::with(['ship'])->select(sprintf('%s.*', (new HistoryShip)->table));
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
                )
                );
            }
            );
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            }
            );
            $table->editColumn('history_ids', function ($row) {
                return $row->history_ids ? $row->history_ids : "";
            }
            );
            $table->editColumn('sin', function ($row) {
                return $row->sin ? $row->sin : "";
            }
            );
            $table->editColumn('min', function ($row) {
                return $row->min ? $row->min : "";
            }
            );
            $table->editColumn('region_name', function ($row) {
                return $row->region_name ? $row->region_name : "";
            }
            );
            $table->addColumn('ship_ship_ids', function ($row) {
                return $row->ship ? $row->ship->ship_ids : '';
            }
            );
            $table->editColumn('ship.name', function ($row) {
                return $row->ship ? (is_string($row->ship) ? $row->ship : $row->ship->name) : '';
            }
            );
            $table->editColumn('payload', function ($row) {
                return $row->payload ? $row->payload : "";
            }
            );
            $table->editColumn('ota_message_size', function ($row) {
                return $row->ota_message_size ? $row->ota_message_size : "";
            }
            );
            $table->rawColumns(['actions', 'placeholder', 'ship']);
            return $table->make(true);
        }
        return view('admin.historyShips.index');
    }

    public function create ()
    {
        abort_if(Gate::denies('history_ship_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ships = Ship::all()->pluck('ship_ids', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.historyShips.create', compact('ships'));
    }

    public function store (StoreHistoryShipRequest $request)
    {
        $historyShip = HistoryShip::create($request->all());
        return redirect()->route('admin.history-ships.index');
    }

    public function edit (HistoryShip $historyShip)
    {
        abort_if(Gate::denies('history_ship_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $ships = Ship::all()->pluck('ship_ids', 'id')->prepend(trans('global.pleaseSelect'), '');
        $historyShip->load('ship');
        return view('admin.historyShips.edit', compact('ships', 'historyShip'));
    }

    public function update (UpdateHistoryShipRequest $request, HistoryShip $historyShip)
    {
        $historyShip->update($request->all());
        return redirect()->route('admin.history-ships.index');
    }

    public function show (HistoryShip $historyShip)
    {
        abort_if(Gate::denies('history_ship_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $historyShip->load('ship');
        return view('admin.historyShips.show', compact('historyShip'));
    }

    public function destroy (HistoryShip $historyShip)
    {
        abort_if(Gate::denies('history_ship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $historyShip->delete();
        return back();
    }

    public function massDestroy (MassDestroyHistoryShipRequest $request)
    {
        HistoryShip::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

}
