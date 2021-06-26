@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.terminal.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.terminals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.id') }}
                        </th>
                        <td>
                            {{ $terminal->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.name') }}
                        </th>
                        <td>
                            {{ $terminal->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.ship') }}
                        </th>
                        <td>
                            @foreach($terminal->ships as $key => $ship)
                                <span class="label label-info">{{ $ship->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.air_comm_blocked') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->air_comm_blocked ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.power_backup') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->power_backup ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.power_main') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->power_main ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.sleep_schedule') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->sleep_schedule ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.battery_low') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->battery_low ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.speeding_start') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->speeding_start ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.speeding_end') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->speeding_end ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.modem_registration') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->modem_registration ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.geofence_in') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->geofence_in ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.geofence_out') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->geofence_out ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.destinasion_email') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $terminal->email_destination ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.destinasion_email_list') }}
                        </th>
                        <td>
                            @foreach($terminal->email as $key => $email)
                                <span class="label label-info">{{ $email->email }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminal.fields.alert_email_list') }}
                        </th>
                        <td>
                            @foreach($terminal->alertEmail as $keys => $emails)
                                <span class="label label-info">{{ $emails->email }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.terminals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
