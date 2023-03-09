<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BillingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'interval',
    ];

//    public function plan(): HasOne {
//        return $this->hasOne(BillingPlan::class);
//    }
}
