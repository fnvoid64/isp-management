<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    public function connection_points()
    {
        return $this->hasMany(ConnectionPoint::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
