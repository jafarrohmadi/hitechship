@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.historyShip.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.history-ships.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="history_ids">{{ trans('cruds.historyShip.fields.history_ids') }}</label>
                <input class="form-control {{ $errors->has('history_ids') ? 'is-invalid' : '' }}" type="text" name="history_ids" id="history_ids" value="{{ old('history_ids', '') }}" required>
                @if($errors->has('history_ids'))
                    <div class="invalid-feedback">
                        {{ $errors->first('history_ids') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.history_ids_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sin">{{ trans('cruds.historyShip.fields.sin') }}</label>
                <input class="form-control {{ $errors->has('sin') ? 'is-invalid' : '' }}" type="text" name="sin" id="sin" value="{{ old('sin', '') }}">
                @if($errors->has('sin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.sin_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="min">{{ trans('cruds.historyShip.fields.min') }}</label>
                <input class="form-control {{ $errors->has('min') ? 'is-invalid' : '' }}" type="text" name="min" id="min" value="{{ old('min', '') }}">
                @if($errors->has('min'))
                    <div class="invalid-feedback">
                        {{ $errors->first('min') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.min_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="region_name">{{ trans('cruds.historyShip.fields.region_name') }}</label>
                <input class="form-control {{ $errors->has('region_name') ? 'is-invalid' : '' }}" type="text" name="region_name" id="region_name" value="{{ old('region_name', '') }}">
                @if($errors->has('region_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('region_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.region_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="receive_utc">{{ trans('cruds.historyShip.fields.receive_utc') }}</label>
                <input class="form-control datetime {{ $errors->has('receive_utc') ? 'is-invalid' : '' }}" type="text" name="receive_utc" id="receive_utc" value="{{ old('receive_utc') }}">
                @if($errors->has('receive_utc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receive_utc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.receive_utc_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="message_utc">{{ trans('cruds.historyShip.fields.message_utc') }}</label>
                <input class="form-control datetime {{ $errors->has('message_utc') ? 'is-invalid' : '' }}" type="text" name="message_utc" id="message_utc" value="{{ old('message_utc') }}">
                @if($errors->has('message_utc'))
                    <div class="invalid-feedback">
                        {{ $errors->first('message_utc') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.message_utc_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ship_id">{{ trans('cruds.historyShip.fields.ship') }}</label>
                <select class="form-control select2 {{ $errors->has('ship') ? 'is-invalid' : '' }}" name="ship_id" id="ship_id">
                    @foreach($ships as $id => $ship)
                        <option value="{{ $id }}" {{ old('ship_id') == $id ? 'selected' : '' }}>{{ $ship }}</option>
                    @endforeach
                </select>
                @if($errors->has('ship_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ship_id') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.ship_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="payload">{{ trans('cruds.historyShip.fields.payload') }}</label>
                <input class="form-control {{ $errors->has('payload') ? 'is-invalid' : '' }}" type="text" name="payload" id="payload" value="{{ old('payload', '') }}">
                @if($errors->has('payload'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payload') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.payload_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ota_message_size">{{ trans('cruds.historyShip.fields.ota_message_size') }}</label>
                <input class="form-control {{ $errors->has('ota_message_size') ? 'is-invalid' : '' }}" type="text" name="ota_message_size" id="ota_message_size" value="{{ old('ota_message_size', '') }}">
                @if($errors->has('ota_message_size'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ota_message_size') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.ota_message_size_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
