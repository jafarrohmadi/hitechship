@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.update') }} {{ trans('cruds.setting.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.settings.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>{{ trans('cruds.setting.fields.simple_report') }}</label>
                    <select class="form-control {{ $errors->has('simple_report') ? 'is-invalid' : '' }}" name="simple_report" id="simple_report">
                        <option value disabled {{ old('simple_report', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                        @foreach(App\Setting::SIMPLE_REPORT_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('simple_report', $setting->simple_report ?? '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('simple_report'))
                        <div class="invalid-feedback">
                            {{ $errors->first('simple_report') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.setting.fields.simple_report_helper') }}</span>
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
