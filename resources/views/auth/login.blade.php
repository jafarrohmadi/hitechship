@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6" style="padding: 40px 40px 100px 40px;">
            <div class="card mx-4" style="background-color: gainsboro">
                <div class="card-body p-4">
                    <h1 style="text-align: center;">{{ trans('panel.site_title') }}</h1>
                    <br>
                    <p class="text-muted"></p>

                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user"></i>
                            </span>
                            </div>
                            <input id="username" name="username" type="text"
                                   class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" required
                                   autocomplete="username" autofocus placeholder="{{ trans('global.login_username') }}"
                                   value="{{ old('username', null) }}">
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>

                            <input id="password" name="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required
                                   placeholder="{{ trans('global.login_password') }}">

                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-4">
                            <div class="form-check checkbox">
                                <input class="form-check-input" name="remember" type="checkbox" id="remember"
                                       style="vertical-align: middle;"/>
                                <label class="form-check-label" for="remember" style="vertical-align: middle; font-weight: 500; color: #000;">
                                    {{ trans('global.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12" style="text-align: center;">
                                <button type="submit" class="btn btn-primary col-4 px-3">
                                    {{ trans('global.login') }}
                                </button>
                            </div>
{{--                            <div class="col-6 text-right">--}}
{{--                                @if(Route::has('password.request'))--}}
{{--                                    <a class="btn btn-link px-0" href="{{ route('password.request') }}">--}}
{{--                                        {{ trans('global.forgot_password') }}--}}
{{--                                    </a><br>--}}
{{--                                @endif--}}

{{--                            </div>--}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
