<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'evaluasis';

    // Primary key
    protected $primaryKey = 'id_evaluasi';

    // Menonaktifkan timestamps
    public $timestamps = false;

    // Kolom yang dapat diisi
    protected $fillable = [
        'evaluasi',
        'id_kegiatan',
    ];

    // Relasi ke model Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }
}
