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
                <label class="required" for="latitute">{{ trans('cruds.historyShip.fields.latitute') }}</label>
                <input class="form-control {{ $errors->has('latitute') ? 'is-invalid' : '' }}" type="text" name="latitute" id="latitute" value="{{ old('latitute', '') }}" required>
                @if($errors->has('latitute'))
                    <div class="invalid-feedback">
                        {{ $errors->first('latitute') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.latitute_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="logitude">{{ trans('cruds.historyShip.fields.logitude') }}</label>
                <input class="form-control {{ $errors->has('logitude') ? 'is-invalid' : '' }}" type="text" name="logitude" id="logitude" value="{{ old('logitude', '') }}" required>
                @if($errors->has('logitude'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logitude') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.logitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="time_ship">{{ trans('cruds.historyShip.fields.time_ship') }}</label>
                <input class="form-control {{ $errors->has('time_ship') ? 'is-invalid' : '' }}" type="text" name="time_ship" id="time_ship" value="{{ old('time_ship', '') }}" required>
                @if($errors->has('time_ship'))
                    <div class="invalid-feedback">
                        {{ $errors->first('time_ship') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.time_ship_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ships">{{ trans('cruds.historyShip.fields.ship') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('ships') ? 'is-invalid' : '' }}" name="ships[]" id="ships" multiple>
                    @foreach($ships as $id => $ship)
                        <option value="{{ $id }}" {{ in_array($id, old('ships', [])) ? 'selected' : '' }}>{{ $ship }}</option>
                    @endforeach
                </select>
                @if($errors->has('ships'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ships') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.historyShip.fields.ship_helper') }}</span>
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