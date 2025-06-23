<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampelMaterial extends Model
{
    use HasFactory;

    protected $table = 'sampel_materials'; // Pastikan nama tabelnya benar
    protected $fillable = [
        'nama_sampel_material',
        'jumlah_sampel',
        'tanggal_penerimaan',
        'tanggal_pengembalian',
        'status_data'
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'date',
        'tanggal_pengembalian' => 'date',
        'status_data' => 'boolean',
    ];
}