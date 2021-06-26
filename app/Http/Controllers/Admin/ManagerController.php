<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyManagerRequest;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Http\Requests\UpdateUserRequest;
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
            $query = Manager::with([ 'manager', 'users' ])->select(sprintf('%s.*', (new Manager)->table));
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
                    )
                );
            }
            );
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            }
            );
            $table->addColumn('manager_name', function ($row) {
                return $row->manager ? $row->manager->name : '';
            }
            );
            $table->editColumn('manager.email', function ($row) {
                return $row->manager ? (is_string($row->manager) ? $row->manager : $row->manager->email) : '';
            }
            );

            $table->addColumn('role_manager', 'Manager');

            $table->editColumn('user', function ($row) {
                $labels = [];
                foreach ($row->users as $user) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $user->name);
                }
                return implode(' ', $labels);
            }
            );
            $table->rawColumns([ 'actions', 'placeholder', 'role_manager', 'manager', 'user' ]);
            return $table->make(true);
        }
        return view('admin.managers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('manager_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
//        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
//            ->whereNotIn('users.id', function ($query) {
//
//                $query->select('manager_user.user_id')->from('manager_user');
//
//            })
//            ->where('role_user.role_id', 3)->get()->pluck('name', 'id');
        $users =
            User::join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', 3)->get()->pluck('name', 'id');
        return view('admin.managers.create', compact('users'));
    }

    public function store(StoreManagerRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync(2);
        $manager             = new Manager();
        $manager->manager_id = $user->id;
        $manager->save();
        $manager->users()->sync($request->input('users', []));
        return redirect()->route('admin.managers.index');
    }

    public function edit(Manager $manager)
    {
        abort_if(Gate::denies('manager_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $managers = User::find($manager->manager_id);

        $users =
            User::join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', 3)->get()->pluck('name', 'id');
//        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
//            ->whereNotIn('users.id',function($query) use ($manager){
//                $query->select('manager_user.user_id')->from('manager_user')->where('manager_id', '!=', $manager->id);
//            })->where('role_user.role_id', 3)->get()->pluck('name', 'id');
        $manager->load('manager', 'users');
        return view('admin.managers.edit', compact('managers', 'users', 'manager'));
    }

    public function update(UpdateManagerRequest $request, Manager $manager)
    {
        $user = User::find($manager->manager_id);
        $user->update($request->all());
        $manager->users()->sync($request->input('users', []));
        return redirect()->route('admin.managers.index');
    }

    public function show(Manager $manager)
    {
        $user = User::find($manager->manager_id);
        abort_if(Gate::denies('manager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $manager->load('manager', 'users');
        return view('admin.managers.show', compact('manager', 'user'));
    }

    public function destroy(Manager $manager)
    {
        $id = $manager->manager_id;
        abort_if(Gate::denies('manager_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $manager->delete();
        $user = User::destroy($id);
        return back();
    }

    public function massDestroy(MassDestroyManagerRequest $request)
    {
        Manager::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

}
