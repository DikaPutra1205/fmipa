<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'module_id', 'test_package_id',
        'note', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function testPackage()
    {
        return $this->belongsTo(TestPackage::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
