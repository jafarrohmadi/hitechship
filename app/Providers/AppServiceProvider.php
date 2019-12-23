<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        If (env('APP_ENV') !== 'local') {
            $this->app->bind('path.public', function () {
                return __DIR__ . '/../../../public_html';
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        If (env('APP_ENV') !== 'local') {
            $this->app['request']->server->set('HTTPS', true);
        }

        Schema::defaultStringLength(191);

        Validator::extendImplicit('check_password', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            if (!empty($value)) {
                $user = Auth::user();
                if (!Hash::check($value, $user->password)) {
                    $valid = false;
                }
            }
            return $valid;
        });
        Validator::replacer('check_password', function ($message, $attribute, $rule, $parameters) {
            return 'Invalid Password';
        });
    }
}
