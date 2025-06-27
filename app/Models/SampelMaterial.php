<?php

// File: app/Models/SampelMaterial.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SampelMaterial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sampel_materials';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'test_id',
        'status',
        'nama_sampel_material',
        'jumlah_sampel',
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
    ];

    /**
     * Mendapatkan data order pengujian (test) yang memiliki sampel ini.
     */
    public function test(): BelongsTo
    {
        // Relasi ini menghubungkan kolom 'test_id' di tabel ini
        // dengan kolom 'id' di tabel 'tests'.
        return $this->belongsTo(Test::class, 'test_id');
    }
}
