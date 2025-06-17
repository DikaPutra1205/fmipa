<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultVerification extends Model
{
    use HasFactory;

    protected $fillable = ['test_result_id', 'verified_by', 'status', 'reason'];

    public function testResult()
    {
        return $this->belongsTo(TestResult::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
