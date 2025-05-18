<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //relation with user one to many
    public function user(){
        return $this->belongsTo(User::class);
    }
}
