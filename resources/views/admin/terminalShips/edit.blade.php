@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.terminalShip.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.terminal-ships.update", [$terminalShip->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="ships">{{ trans('cruds.terminalShip.fields.ship') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('ships') ? 'is-invalid' : '' }}" name="ships[]" id="ships" multiple required>
                    @foreach($ships as $id => $ship)
                        <option value="{{ $id }}" {{ (in_array($id, old('ships', [])) || $terminalShip->ships->contains($id)) ? 'selected' : '' }}>{{ $ship }}</option>
                    @endforeach
                </select>
                @if($errors->has('ships'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ships') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.terminalShip.fields.ship_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="terminals">{{ trans('cruds.terminalShip.fields.terminal') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('terminals') ? 'is-invalid' : '' }}" name="terminals[]" id="terminals" multiple>
                    @foreach($terminals as $id => $terminal)
                        <option value="{{ $id }}" {{ (in_array($id, old('terminals', [])) || $terminalShip->terminals->contains($id)) ? 'selected' : '' }}>{{ $terminal }}</option>
                    @endforeach
                </select>
                @if($errors->has('terminals'))
                    <div class="invalid-feedback">
                        {{ $errors->first('terminals') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.terminalShip.fields.terminal_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="arrive_time">{{ trans('cruds.terminalShip.fields.arrive_time') }}</label>
                <input class="form-control datetime {{ $errors->has('arrive_time') ? 'is-invalid' : '' }}" type="text" name="arrive_time" id="arrive_time" value="{{ old('arrive_time', $terminalShip->arrive_time) }}">
                @if($errors->has('arrive_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('arrive_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.terminalShip.fields.arrive_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="departure_time">{{ trans('cruds.terminalShip.fields.departure_time') }}</label>
                <input class="form-control datetime {{ $errors->has('departure_time') ? 'is-invalid' : '' }}" type="text" name="departure_time" id="departure_time" value="{{ old('departure_time', $terminalShip->departure_time) }}">
                @if($errors->has('departure_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('departure_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.terminalShip.fields.departure_time_helper') }}</span>
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