<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_DISABLED = 0;

    protected $guarded = ['password'];
    protected $hidden = ['password'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
