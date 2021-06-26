<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAlertTerminal extends Model
{
    public $table = 'email_alert_terminal';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'email',
        'terminal_id',
    ];

    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }
}
