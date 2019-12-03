@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.user.fields.change_password') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.store-password") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="old_password">{{ trans('cruds.user.fields.old_password') }}</label>
                    <input class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}" type="password" name="old_password" id="old_password" required>
                    @if($errors->has('old_password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('old_password') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="password">{{ trans('cruds.user.fields.new_password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="password_confirmation">{{ trans('cruds.user.fields.confirm_password') }}</label>
                    <input class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" type="password" name="password_confirmation" id="password_confirmation" required>
                    @if($errors->has('password_confirmation'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
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
