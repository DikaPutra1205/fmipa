<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPackage extends Model
{
    use HasFactory;

    protected $table = 'test_packages';
    protected $fillable = ['module_id', 'name', 'price'];

<<<<<<< HEAD
    protected $casts = [
        'price' => 'float',
    ];
=======
>>>>>>> 54fc28c97a01a4fe81a73442c202a03518b42b17
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
