<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    public const TYPE_CASH = 1;
    public const TYPE_MOBILE_BANK = 2;
    public const TYPE_BANK = 3;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
