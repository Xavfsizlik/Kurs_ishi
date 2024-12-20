<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cipher_user extends Model
{
    protected $table="cipher_user";
    protected $fillable = [
        'user_id','cipher_id',
    ];

    
}
