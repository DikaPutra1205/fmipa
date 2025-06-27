<?php

// File: app/Models/Test.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Test extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'module_id',
        'test_package_id',
        'quantity',
        'final_price',
        'assigned_teknisi_id',
        'verified_by_ahli_id',
        'note',
        'status',
        'result_file_path',
        'rejection_notes',
    ];

    /**
     * Mendapatkan data Mitra (user) yang mengajukan pengujian ini.
     */
    public function mitra(): BelongsTo
    {
        // Relasi ini menghubungkan kolom 'user_id' di tabel 'tests'
        // dengan kolom 'id' di tabel 'users'.
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan data Teknisi yang ditugaskan untuk pengujian ini.
     */
    public function technician(): BelongsTo
    {
        // Relasi ini menghubungkan kolom 'assigned_teknisi_id' di tabel 'tests'
        // dengan kolom 'id' di tabel 'users'.
        return $this->belongsTo(User::class, 'assigned_teknisi_id');
    }

    /**
     * Mendapatkan data Tenaga Ahli yang memverifikasi pengujian ini.
     */
    public function expert(): BelongsTo
    {
        // Relasi ini menghubungkan kolom 'verified_by_ahli_id' di tabel 'tests'
        // dengan kolom 'id' di tabel 'users'.
        return $this->belongsTo(User::class, 'verified_by_ahli_id');
    }

    /**
     * Mendapatkan data paket pengujian yang dipilih.
     */
    public function testPackage(): BelongsTo
    {
        return $this->belongsTo(TestPackage::class, 'test_package_id');
    }
    
    /**
     * Mendapatkan data modul pengujian.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /**
     * Mendapatkan data sampel yang terkait dengan pengujian ini.
     */
    public function sample(): HasOne
    {
        // Relasi ini menghubungkan tabel 'tests' dengan 'sampel_materials'
        // melalui kolom 'test_id' di tabel 'sampel_materials'.
        return $this->hasOne(SampelMaterial::class, 'test_id');
    }
}