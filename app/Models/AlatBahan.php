<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatBahan extends Model
{
    use HasFactory;

    protected $table = 'alat_bahans'; // Pastikan nama tabelnya benar
    protected $fillable = [
        'nama_alat_bahan',
        'kondisi_alat',
        'jumlah_alat',
        'status_data'
    ];
}
