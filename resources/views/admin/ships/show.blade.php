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
                            {{ trans('cruds.ship.fields.name') }}
                        </th>
                        <td>
                            {{ $ship->name }}
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
                            {{ trans('cruds.ship.fields.owner') }}
                        </th>
                        <td>
                            {{ $ship->owner }}
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
@endsection