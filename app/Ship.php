<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ship extends Model
{
    public $table = 'ships';

    public static $searchable = [
        'name',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'last_registration_utc',
    ];

    const TYPE_SELECT = [
        'cargoShip'        => 'Cargo Ship',
        'multyPurposeShip' => 'Multy Purpose Ship',
        'ferryShip'        => 'Ferry Ship',
    ];

    protected $fillable = [
        'name',
        'long',
        'type',
        'call_sign',
        'owner',
        'ship_ids',
        'created_at',
        'updated_at',
        'deleted_at',
        'region_name',
        'last_registration_utc',
    ];

    public function shipHistoryShips()
    {
        return $this->hasMany(HistoryShip::class, 'ship_id', 'id');
    }

    public function shipHistoryShipsLatest()
    {
        return $this->hasOne(HistoryShip::class, 'ship_id', 'id')->whereDate('created_at', '>=', date('Y-m-d', strtotime('-1 year')))->latest();
    }

    public function shipTerminals()
    {
        return $this->belongsToMany(Terminal::class);
    }

    public function getLastRegistrationUtcAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setLastRegistrationUtcAttribute($value)
    {
        $this->attributes['last_registration_utc'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
