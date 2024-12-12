<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;

    // Tentukan kolom primary key
    protected $primaryKey = 'id_tim';

    // Nama tabel (jika berbeda dengan nama model, gunakan ini)
    protected $table = 'tims';

    // Menonaktifkan timestamps
    public $timestamps = false;

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'nama_tim',
    ];

    // Relasi dengan model Kegiatan (satu Tim bisa memiliki banyak Kegiatan)
    // Model Tim (optional, jika diperlukan)
    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'id_tim'); // id_tim adalah foreign key di tabel kegiatan
    }
}
