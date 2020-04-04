<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type', 'sender_id', 'recipient_id', 'amount'
    ];

    public $timestamps = true;
}
