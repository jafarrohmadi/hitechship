<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyManagerRequest;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Manager;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Manager::with(['users'])->select(sprintf('%s.*', (new Manager)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'manager_show';
                $editGate      = 'manager_edit';
                $deleteGate    = 'manager_delete';
                $crudRoutePart = 'managers';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            });
            $table->editColumn('user', function ($row) {
                $labels = [];

                foreach ($row->users as $user) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $user->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.managers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('manager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('name', 'id');

        return view('admin.managers.create', compact('users'));
    }

    public function store(StoreManagerRequest $request)
    {
        $manager = Manager::create($request->all());
        $manager->users()->sync($request->input('users', []));

        return redirect()->route('admin.managers.index');
    }

    public function edit(Manager $manager)
    {
        abort_if(Gate::denies('manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('name', 'id');

        $manager->load('users');

        return view('admin.managers.edit', compact('users', 'manager'));
    }

    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $manager->update($request->all());
        $manager->users()->sync($request->input('users', []));

        return redirect()->route('admin.managers.index');
    }

    public function show(Manager $manager)
    {
        abort_if(Gate::denies('manager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manager->load('users');

        return view('admin.managers.show', compact('manager'));
    }

    public function destroy(Manager $manager)
    {
        abort_if(Gate::denies('manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $manager->delete();

        return back();
    }

    public function massDestroy(MassDestroyManagerRequest $request)
    {
        Manager::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
