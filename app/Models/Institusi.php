<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institusi extends Model
{
    protected $table = 'institusi';
    protected $fillable = ['nama_institusi', 'nama_koordinator', 'telp_wa', 'status'];
}
