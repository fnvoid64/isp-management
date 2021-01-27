<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    public const TYPE_CASH = 1;
    public const TYPE_MOBILE_BANK = 2;
    public const TYPE_BANK = 3;
}
