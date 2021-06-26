@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ship.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ships.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.id') }}
                        </th>
                        <td>
                            {{ $ship->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.ship_ids') }}
                        </th>
                        <td>
                            {{ $ship->ship_ids }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.name') }}
                        </th>
                        <td>
                            {{ $ship->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.owner') }}
                        </th>
                        <td>
                            {{ $ship->owner }}
                        </td>
                    </tr>
                     <tr>
                        <th>
                            {{ trans('cruds.ship.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $ship->updated_at->format('Y-m-d H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.region_name') }}
                        </th>
                        <td>
                            {{ $ship->region_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.last_registration_utc') }}
                        </th>
                        <td>
                            {{ $ship->last_registration_utc }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.call_sign') }}
                        </th>
                        <td>
                            {{ $ship->call_sign }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.long') }}
                        </th>
                        <td>
                            {{ $ship->long }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ship.fields.type') }}
                        </th>
                        <td>
                            {{ App\Ship::TYPE_SELECT[$ship->type] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ships.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#ship_history_ships" role="tab" data-toggle="tab">
                {{ trans('cruds.historyShip.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#ship_users" role="tab" data-toggle="tab">
                {{ trans('cruds.user.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="ship_history_ships">
            @include('admin.ships.relationships.shipHistoryShips', ['historyShips' => $ship->shipHistoryShips])
        </div>
        <div class="tab-pane" role="tabpanel" id="ship_users">
            @include('admin.ships.relationships.shipUsers', ['users' => $ship->shipUsers])
        </div>
    </div>
</div>

@endsection
