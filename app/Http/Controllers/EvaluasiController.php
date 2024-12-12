<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Tim;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EvaluasiController extends Controller
{
    // Menampilkan Daftar Kegiatan dengan Pagination
    public function evaluasiKegiatan()
    {
        // Ambil data kegiatan yang sudah dievaluasi
        $sudahDievaluasi = Kegiatan::where('status', 'tidak aktif')
            ->whereHas('evaluasis') // Memastikan kegiatan ini memiliki evaluasi
            ->get();

        // Ambil data kegiatan yang belum dievaluasi
        $belumDievaluasi = Kegiatan::where('status', 'tidak aktif')
            ->whereDoesntHave('evaluasis') // Memastikan kegiatan ini belum memiliki evaluasi
            ->get();

        return view('pimpinan.evaluasikegiatan', compact('sudahDievaluasi', 'belumDievaluasi'));
    }


    public function storeEvaluasi(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'evaluasi' => 'required|string|max:255',
            'id_kegiatan' => 'required|exists:kegiatans,id_kegiatan', // Pastikan kegiatan ada
        ]);

        // Cek apakah id_kegiatan ada di tabel kegiatans
        $kegiatan = \App\Models\Kegiatan::find($validated['id_kegiatan']);

        if (!$kegiatan) {
            return redirect()->route('pimpinan.evaluasikegiatan')->with('error', 'Kegiatan tidak ditemukan!');
        }

        // Jika id_kegiatan ditemukan, simpan evaluasi ke dalam tabel evaluasis
        $evaluasi = new \App\Models\Evaluasi();
        $evaluasi->evaluasi = $validated['evaluasi'];
        $evaluasi->id_kegiatan = $validated['id_kegiatan'];
        $evaluasi->save(); // Simpan data

        // Redirect kembali ke halaman evaluasi kegiatan dengan pesan sukses
        return redirect()->route('pimpinan.evaluasikegiatan')->with('success', 'Evaluasi berhasil disimpan!');
    }

    public function edit(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'evaluasi' => 'required|string|max:255',
            'id_kegiatan' => 'required|exists:kegiatans,id_kegiatan', // Pastikan kegiatan ada
        ]);

        // Cek apakah id_kegiatan ada di tabel kegiatans
        $kegiatan = \App\Models\Kegiatan::find($validated['id_kegiatan']);

        if (!$kegiatan) {
            return redirect()->route('pimpinan.evaluasikegiatan')->with('error', 'Kegiatan tidak ditemukan!');
        }

        // Cari evaluasi berdasarkan id_kegiatan
        $evaluasi = \App\Models\Evaluasi::where('id_kegiatan', $validated['id_kegiatan'])->first();

        if ($evaluasi) {
            $evaluasi->evaluasi = $validated['evaluasi'];
            $evaluasi->save(); // Simpan data
        }

        // Redirect kembali ke halaman evaluasi kegiatan dengan pesan sukses
        return redirect()->route('pimpinan.evaluasikegiatan')->with('success', 'Evaluasi berhasil diperbarui!');
    }
}
