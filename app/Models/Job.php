<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    public const STATUS_DONE = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_CANCELLED = 0;
}
