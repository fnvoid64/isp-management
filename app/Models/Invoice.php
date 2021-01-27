<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    public const STATUS_PAID = 1;
    public const STATUS_UNPAID = 2;
    public const STATUS_PARTIAL_PAID = 3;
    public const STATUS_CANCELLED = 0;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class);
    }
}
