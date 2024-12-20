<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prime extends Model
{
    protected $fillable=[
        'user_id','prime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
