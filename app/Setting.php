<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes;

    public $table = 'settings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'updated_at',
        'created_at',
        'deleted_at',
        'simple_report',
    ];

    const SIMPLE_REPORT_SELECT = [
        '1'  => 'every data entry',
        '3'  => 'every 3 data',
        '6'  => 'every 6 data',
        '12' => 'every 12 data',
    ];
}
