<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_DISABLED = 0;

    public const ROLE_COLLECTOR = 1;
    protected static $employee;

    protected $hidden = ['password'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getEmployee()
    {
        return static::$employee;
    }

    public static function setEmployee($employee_id)
    {
        static::$employee = static::findOrFail($employee_id);
    }
}
