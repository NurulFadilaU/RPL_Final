<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatans';

    protected $primaryKey = 'id_kegiatan'; // Pastikan ini sesuai dengan nama kolom primary key

    // Menonaktifkan timestamps
    public $timestamps = false;

    protected $fillable = [
        'nama_kegiatan',
        'id_tim',  // Mengganti penggunaan tim_kerja
        'tanggal_mulai',
        'tanggal_berakhir',
        'target',
        'realisasi',
        'satuan',
        'status',
    ];

    public function tim()
    {
        return $this->belongsTo(Tim::class, 'id_tim'); // id_tim adalah kolom foreign key di tabel kegiatan
    }
    public function evaluasis()
    {
        return $this->hasMany(Evaluasi::class, 'id_kegiatan', 'id_kegiatan');
    }
}
