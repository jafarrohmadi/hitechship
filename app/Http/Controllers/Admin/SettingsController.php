<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySettingRequest;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Setting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $setting = Setting::first();

        return view('admin.settings.index', compact('setting'));
    }

    public function store(StoreSettingRequest $request)
    {
        $setting = Setting::updateOrCreate(['id' => 1], ['simple_report' => $request->simple_report]);

        return redirect()->route('admin.settings.index');
    }

}
