<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_DISABLED = 0;

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function connection_point()
    {
        return $this->belongsTo(ConnectionPoint::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
