@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.ship.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ships.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.ship.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.ship.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Ship::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="long">{{ trans('cruds.ship.fields.long') }}</label>
                <input class="form-control {{ $errors->has('long') ? 'is-invalid' : '' }}" type="text" name="long" id="long" value="{{ old('long', '') }}">
                @if($errors->has('long'))
                    <div class="invalid-feedback">
                        {{ $errors->first('long') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.long_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="owner">{{ trans('cruds.ship.fields.owner') }}</label>
                <input class="form-control {{ $errors->has('owner') ? 'is-invalid' : '' }}" type="text" name="owner" id="owner" value="{{ old('owner', '') }}">
                @if($errors->has('owner'))
                    <div class="invalid-feedback">
                        {{ $errors->first('owner') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ship.fields.owner_helper') }}</span>
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