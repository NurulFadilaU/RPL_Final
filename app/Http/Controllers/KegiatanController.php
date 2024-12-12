<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Tim;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    // Menampilkan Daftar Kegiatan dengan Pagination
    public function index(Request $request, $role)
    {
        // Ambil data filter dari query string
        $teamName = $request->get('team');
        $month = $request->get('month');
        $year = $request->get('year');

        // Ambil kegiatan berdasarkan filter
        $kegiatan = $this->getFilteredKegiatan($teamName, $month, $year);

        // Mengambil nama tim dari tabel tims
        $teams = Tim::pluck('nama_tim');

        // Hitung progres dan durasi untuk setiap kegiatan
        $kegiatan = $this->calculateProgress($kegiatan);

        // Tentukan view berdasarkan role
        $view = match ($role) {
            'anggota' => 'anggota.daftarkegiatan',
            'pimpinan' => 'pimpinan.daftarkegiatan',
            'pj' => 'pj.daftarkegiatan',
            default => abort(404),
        };

        // Tampilkan ke view dengan mengirimkan variabel $kegiatan dan $teams
        return view($view, ['kegiatan' => $kegiatan, 'teams' => $teams]);
    }

    // Mendapatkan daftar kegiatan berdasarkan filter
    private function getFilteredKegiatan($teamName, $month, $year)
    {
        $query = Kegiatan::query();

        // Menambahkan kondisi filter jika ada
        if ($teamName) {
            $query->whereHas('tim', function ($q) use ($teamName) {
                $q->where('nama_tim', 'LIKE', "%{$teamName}%");
            });
        }

        if ($month) {
            $query->whereMonth('tanggal_mulai', $month);
        }

        if ($year) {
            $query->whereYear('tanggal_mulai', $year);
        }

        // Eager load relasi tim dan paginasi
        return $query->with('tim')->paginate(30);
    }

    // Menghitung progres target/realisasi dan durasi untuk setiap kegiatan
    private function calculateProgress($kegiatan)
    {
        foreach ($kegiatan as $index => $item) {
            // Hitung progres target/realisasi
            $targetProgress = ($item->realisasi / $item->target) * 100;

            // Hitung durasi progres
            $mulai = Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
            $berakhir = Carbon::parse($item->tanggal_berakhir)->setTimezone('Asia/Jakarta');

            $hariIni = Carbon::now(); // Tanggal saat ini

            // Hitung total durasi dari mulai hingga berakhir
            $durasiTotal = $mulai->diffInDays($berakhir) ?: 1; // Menghindari hasil 0 hari

            // Jika tanggal mulai lebih besar dari hari ini, progres durasi harus 0
            if ($hariIni < $mulai) {
                $durasiTerpakai = 0;
                $durationProgress = 0;
            } else {
                // Hitung durasi terpakai: antara mulai hingga hari ini atau berakhir (mana yang lebih dulu)
                $durasiTerpakai = $mulai->diffInDays(min($hariIni, $berakhir));

                // Hitung progress durasi
                $durationProgress = ($durasiTerpakai / $durasiTotal) * 100;

                // Jika hari ini sudah lewat tanggal berakhir, durasi progres 100%
                if ($hariIni >= $berakhir) {
                    $durationProgress = 100;
                }
            }

            // Menyimpan data dalam objek untuk dikirim ke view
            $item->no = $index + 1;
            $item->target_progress = round($targetProgress, 2);
            $item->duration_progress = round($durationProgress, 2);
        }

        return $kegiatan;
    }
}
