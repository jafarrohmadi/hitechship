<?php
namespace App;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasApiTokens;

    public $table = 'users';

    public static $searchable = [
        'name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'username',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
    ];

    public function managers ()
    {
        return $this->belongsToMany(Manager::class);
    }


    public function setPasswordAttribute ($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function roles ()
    {
        return $this->belongsToMany(Role::class);
    }

}
