<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TerminalShip extends Model
{
    use SoftDeletes;

    public $table = 'terminal_ships';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'arrive_time',
        'departure_time',
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'deleted_at',
        'arrive_time',
        'departure_time',
    ];

    public function ships()
    {
        return $this->belongsToMany(Ship::class);
    }

    public function terminals()
    {
        return $this->belongsToMany(Terminal::class);
    }

    public function getArriveTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setArriveTimeAttribute($value)
    {
        $this->attributes['arrive_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDepartureTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDepartureTimeAttribute($value)
    {
        $this->attributes['departure_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
