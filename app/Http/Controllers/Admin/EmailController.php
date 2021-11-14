<?php

namespace App\Http\Controllers\Admin;

use App\EmailDestination;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEmailDestinationRequest;
use App\Http\Requests\StoreEmailDestinationRequest;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('email_destination_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $email = EmailDestination::all();

        return view('admin.email-destination.index', compact('email'));
    }

    public function create()
    {
        abort_if(Gate::denies('email_destination_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $email = EmailDestination::all()->pluck('email', 'id');

        return view('admin.email-destination.create', compact('email'));
    }

    public function store(StoreEmailDestinationRequest $request)
    {
        $email = EmailDestination::create($request->all());

        return redirect()->route('admin.email-destination.index');
    }

    public function edit($email)
    {
        abort_if(Gate::denies('email_destination_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $email = EmailDestination::find($email);
        return view('admin.email-destination.edit', compact('email'));
    }

    public function update(StoreEmailDestinationRequest $request, $email)
    {
        $email = EmailDestination::find($email);
        $email->update($request->all());

        return redirect()->route('admin.email-destination.index');
    }


    public function destroy(EmailDestination $emailDestination)
    {
       abort_if(Gate::denies('email_destination_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $emailDestination->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmailDestinationRequest $request)
    {
        EmailDestination::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
