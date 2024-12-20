<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cipher extends Model
{
    protected $fillable = [
        'name','daraja',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'cipher_user');
    }

}
