<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryShip extends Model
{
    use SoftDeletes;

    public $table = 'history_ships';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'receive_utc',
        'message_utc',
    ];

    protected $fillable = [
        'sin',
        'min',
        'ship_id',
        'payload',
        'created_at',
        'updated_at',
        'deleted_at',
        'history_ids',
        'region_name',
        'receive_utc',
        'message_utc',
        'ota_message_size',
        'display_to_map'
    ];

    public function getReceiveUtcAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setReceiveUtcAttribute($value)
    {
        $this->attributes['receive_utc'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getMessageUtcAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setMessageUtcAttribute($value)
    {
        $this->attributes['message_utc'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function ship()
    {
        return $this->belongsTo(Ship::class, 'ship_id');
    }
}
