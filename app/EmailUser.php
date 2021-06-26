<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailUser extends Model
{
    public $table = 'email_user';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'email',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
