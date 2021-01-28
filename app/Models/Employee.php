<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_DISABLED = 0;

    public const ROLE_COLLECTOR = 1;

    protected $guarded = [];
    protected $hidden = ['password'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
