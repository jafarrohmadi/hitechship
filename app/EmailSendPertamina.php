<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSendPertamina extends Model
{
    protected $fillable
        = [
            'ship_id',
            'history_ship_id',
            'last_seen_time',
            'last_sent_destination',
            'last_sent_status',
            'subject',
            'filename_chr',
            'content'
        ];
}
