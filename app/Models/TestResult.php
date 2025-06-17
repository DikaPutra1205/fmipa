<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = ['test_id', 'file_path', 'uploaded_by'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verification()
    {
        return $this->hasOne(ResultVerification::class);
    }
}
