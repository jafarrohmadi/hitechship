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
                            {{ trans('cruds.historyShip.fields.history_ids') }}
                        </th>
                        <td>
                            {{ $historyShip->history_ids }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.sin') }}
                        </th>
                        <td>
                            {{ $historyShip->sin }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.min') }}
                        </th>
                        <td>
                            {{ $historyShip->min }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.region_name') }}
                        </th>
                        <td>
                            {{ $historyShip->region_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.receive_utc') }}
                        </th>
                        <td>
                            {{ $historyShip->receive_utc }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.message_utc') }}
                        </th>
                        <td>
                            {{ $historyShip->message_utc }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.ship') }}
                        </th>
                        <td>
                            {{ $historyShip->ship->ship_ids ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.payload') }}
                        </th>
                        <td>
                            {{ $historyShip->payload }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.historyShip.fields.ota_message_size') }}
                        </th>
                        <td>
                            {{ $historyShip->ota_message_size }}
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
