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
        'additional_email_ship',
        'send_to_pertamina',
    ];

    public function shipHistoryShips()
    {
        return $this->hasMany(HistoryShip::class, 'ship_id', 'id')->where('display_to_map', 1)->where('sin', 19)->where('min', 1);
    }

    public function shipHistoryShipsLatest()
    {
        return $this->hasOne(HistoryShip::class, 'ship_id', 'id')->whereDate('created_at', '>=', date('Y-m-d', strtotime('-1 year')))->where('display_to_map', 1)->where('sin', 19)->where('min', 1)->latest();
    }

    public function shipTerminals()
    {
        return $this->belongsToMany(Terminal::class);
    }

    public function shipUsers()
    {
        return $this->belongsToMany(User::class);
    }

    public function getLastRegistrationUtcAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setLastRegistrationUtcAttribute($value)
    {
        $this->attributes['last_registration_utc'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function emailSendPertamina()
    {
        return $this->hasMany(EmailSendPertamina::class, 'ship_id', 'id');
    }

    public function emailSendPertaminaLast()
    {
        return $this->emailSendPertamina->sortByDesc('id')->first() ?? '';
    }
}
