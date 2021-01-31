<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'f_name',
        'm_name',
        'nid',
        'mobile',
        'address',
        'photo',
        'pin',
        'company_name',
        'company_short'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function connection_points()
    {
        return $this->hasMany(ConnectionPoint::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
