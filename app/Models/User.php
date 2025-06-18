<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'institution',
        'role', 'coordinator_name', 'is_active'
    ];

    protected $hidden = ['password'];

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class, 'uploaded_by');
    }

    public function verifications()
    {
        return $this->hasMany(ResultVerification::class, 'verified_by');
    }
}
