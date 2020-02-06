<?php
namespace App\Http\Controllers\Admin;

use App\EmailUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\Terminal;
use App\User;
use Gate;
use Illuminate\Http\Request;
use PharIo\Manifest\Email;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use Session;

class UsersController extends Controller
{
    public function index (Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['roles', 'email', 'terminals'])->join('role_user', 'users.id', '=', 'role_user.user_id')->where('role_user.role_id', 3)->select(sprintf('%s.*', (new User)->table));
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->editColumn('actions', function ($row) {
                $viewGate      = 'user_show';
                $editGate      = 'user_edit';
                $deleteGate    = 'user_delete';
                $crudRoutePart = 'users';
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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : "";
            }
            );
            $table->editColumn('username', function ($row) {
                return $row->username ? $row->username : "";
            }
            );
            $table->editColumn('roles', function ($row) {
                $labels = [];
                foreach ($row->roles as $role) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $role->title);
                }
                return implode(', ', $labels);
            }
            );
            $table->editColumn('email', function ($row) {
                $labels = [];
                foreach ($row->email as $email) {
                    $labels[] = $email->email;
                }
                return implode(', ', $labels);
            }
            );
            $table->editColumn('terminal', function ($row) {
                $labels = [];
                foreach ($row->terminals as $terminal) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $terminal->name);
                }
                return implode(' ', $labels);
            }
            );
            $table->rawColumns(['actions', 'placeholder', 'roles', 'terminal']);
            return $table->make(true);
        }
        return view('admin.users.index');
    }

    public function create ()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles     = Role::where('id', '!=', 2)->where('id', '!=', 1)->get()->pluck('title', 'id');
        $terminals = Terminal::all()->pluck('name', 'id');
//        $terminals = Terminal::whereNotIn('id',function($query) {
//
//            $query->select('terminal_id')->from('terminal_user');
//
//        })->get()->pluck('name', 'id');
        return view('admin.users.create', compact('roles', 'terminals'));
    }

    public function store (StoreUserRequest $request)
    {
        $user = User::create($request->all());
        if ($request->email) {
            foreach ($request->email as $email) {
                if($email) {
                    $emailUser          = new EmailUser();
                    $emailUser->email   = $email;
                    $emailUser->user_id = $user->id;
                    $emailUser->save();
                }
            }
        }
        $user->roles()->sync($request->input('roles', []));
        $user->terminals()->sync($request->input('terminals', []));
        return redirect()->route('admin.users.index');
    }

    public function edit (User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles     = Role::where('id', '!=', 2)->where('id', '!=', 1)->get()->pluck('title', 'id');

//        $terminals = Terminal::whereNotIn('id',function($query) use ($user){
//            $query->select('terminal_id')->from('terminal_user')->where('user_id', '!=',$user->id);
//        })->get()->pluck('name', 'id');
        $terminals = Terminal::all()->pluck('name', 'id');

        $user->load('roles', 'terminals', 'email');
        return view('admin.users.edit', compact('roles', 'user', 'terminals'));
    }

    public function update (UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        $user->terminals()->sync($request->input('terminals', []));
        if ($request->email) {
            EmailUser::where('user_id', $user->id)->delete();
            foreach ($request->email as $email) {
                $emailUser          = new EmailUser();
                $emailUser->email   = $email;
                $emailUser->user_id = $user->id;
                $emailUser->save();
            }
        }
        return redirect()->route('admin.users.index');
    }

    public function show (User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->load('roles', 'email', 'terminals', 'managerManagers', 'userManagers');
        return view('admin.users.show', compact('user'));
    }

    public function destroy (User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->delete();
        return back();
    }

    public function massDestroy (MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function changePassword ()
    {
        $user = Auth::user();
        return view('admin.users.change-password', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function storePassword (Request $request)
    {
        $this->validate($request, [
                'old_password' => 'required|check_password',
                'password' => 'required|min:6|confirmed',
            ]
        );
        $user = Auth::user();
        $request->merge(['password' => bcrypt($request->get('password'))]);
        $user->fill($request->except('_method', '_token'));
        $user->save();
        Session::flash('message', 'Password updated!');
        return back();
    }

}
