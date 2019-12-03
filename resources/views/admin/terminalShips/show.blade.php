@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.terminalShip.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.terminal-ships.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.terminalShip.fields.id') }}
                        </th>
                        <td>
                            {{ $terminalShip->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminalShip.fields.ship') }}
                        </th>
                        <td>
                            @foreach($terminalShip->ships as $key => $ship)
                                <span class="label label-info">{{ $ship->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminalShip.fields.terminal') }}
                        </th>
                        <td>
                            @foreach($terminalShip->terminals as $key => $terminal)
                                <span class="label label-info">{{ $terminal->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminalShip.fields.arrive_time') }}
                        </th>
                        <td>
                            {{ $terminalShip->arrive_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.terminalShip.fields.departure_time') }}
                        </th>
                        <td>
                            {{ $terminalShip->departure_time }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.terminal-ships.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>


    </div>
</div>
@endsection