<?php

namespace App;

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
    ];

    protected $fillable = [
        'latitute',
        'logitude',
        'time_ship',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ships()
    {
        return $this->belongsToMany(Ship::class);
    }
}
