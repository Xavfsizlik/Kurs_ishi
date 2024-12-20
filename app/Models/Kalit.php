<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kalit extends Model
{
    protected $fillable=[
        'id','user_id','myuser_id','kalit','cipher',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
