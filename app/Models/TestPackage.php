<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPackage extends Model
{
    use HasFactory;

    protected $table = 'test_packages';
    protected $fillable = ['module_id', 'name', 'price'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
