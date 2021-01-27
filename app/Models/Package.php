<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    public const TYPE_BROADBAND = 1;
    public const TYPE_CABLE_TV = 2;

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
