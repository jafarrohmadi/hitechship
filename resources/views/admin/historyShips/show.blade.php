@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.historyShip.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.history-ships.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.id') }}
                        </th>
                        <td>
                            {{ $historyShip->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.latitute') }}
                        </th>
                        <td>
                            {{ $historyShip->latitute }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.logitude') }}
                        </th>
                        <td>
                            {{ $historyShip->logitude }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.time_ship') }}
                        </th>
                        <td>
                            {{ $historyShip->time_ship }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.ship') }}
                        </th>
                        <td>
                            @foreach($historyShip->ships as $key => $ship)
                                <span class="label label-info">{{ $ship->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.history-ships.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>


    </div>
</div>
@endsection