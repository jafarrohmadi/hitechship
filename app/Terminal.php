<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terminal extends Model
{
    public $table = 'terminals';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'power_main',
        'created_at',
        'updated_at',
        'deleted_at',
        'battery_low',
        'geofence_in',
        'power_backup',
        'speeding_end',
        'geofence_out',
        'sleep_schedule',
        'speeding_start',
        'air_comm_blocked',
        'modem_registration',
        'email_destination'
    ];

    public function ships()
    {
        return $this->belongsToMany(Ship::class);
    }

    public function shipps()
    {
        return $this->belongsToMany(Shipp::class);
    }

    public function terminalUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public function email ()
    {
        return $this->hasMany(EmailTerminal::class);
    }

    public function alertEmail()
    {
        return $this->hasMany(EmailAlertTerminal::class);
    }

}
