@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-3" style="padding: 0 0 40vh;">
            <div class="card mx-4" style="background: rgba(220,220,220,0.7)">
                <div class="card-body p-2">
                    <h1 style="text-align: center; margin-bottom: 0">{{ trans('panel.site_title') }}</h1>
                    <h5 style="text-align: center; font-weight: lighter; margin-bottom: 15px">{{ trans('panel.site_subtitle') }}</h5>
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fa fa-user" style="width:  9px"></i>
                            </span>
                            </div>
                            <input id="username" name="username" type="text"
                                   class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" required
                                   autocomplete="username" autofocus placeholder="{{ trans('global.login_username') }}"
                                   value =""> <!--value="{{ old('username', null) }}"-->
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-2">
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

                        <div class="input-group mb-2">
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
