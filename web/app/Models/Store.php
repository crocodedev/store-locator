<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'slug',
        'status',
        'address_1',
        'address_2',
        'city',
        'postcode',
        'state',
        'country',
        'latitude',
        'longitude',
        'phone',
        'fax',
        'site',
        'social_instagram',
        'social_twitter',
        'social_facebook',
        'social_tiktok',
    ];
}
