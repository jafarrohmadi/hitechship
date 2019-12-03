<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ship extends Model
{
    use SoftDeletes;

    public $table = 'ships';

    public static $searchable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'type',
        'long',
        'owner',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    const TYPE_SELECT = [
        'cargoShip'        => 'Cargo Ship',
        'multyPurposeShip' => 'Multy Purpose Ship',
        'ferryShip'        => 'Ferry Ship',
    ];

    public function historyShips()
    {
        return $this->belongsToMany(HistoryShip::class);
    }

    public function terminalShips()
    {
        return $this->belongsToMany(TerminalShip::class);
    }
}
