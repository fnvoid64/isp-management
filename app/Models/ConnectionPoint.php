<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConnectionPoint extends Model
{
    use HasFactory;

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
