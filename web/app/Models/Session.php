<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public function stories()
    {
        return $this->hasMany(Store::class, 'id', 'session_id');
    }
}
