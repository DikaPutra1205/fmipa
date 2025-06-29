<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'modules';
    protected $fillable = ['code', 'name', 'description','details'];

    public function testPackages()
    {
        return $this->hasMany(TestPackage::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
    public function services()
    {
        return $this->hasMany(TestPackage::class);
    }

    protected $casts = [
        'details' => 'array',
    ];
}
