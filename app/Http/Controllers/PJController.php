<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Tim;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PJController extends Controller
{
    // Menampilkan Daftar Kegiatan dengan Pagination
    public function index(Request $request)
    {
        // Ambil data filter dari query string
        $teamName = $request->get('team');
        $month = $request->get('month');
        $year = $request->get('year');

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

        // Eager load relasi tim
        $kegiatan = $query->with('tim')->paginate(30);

        // Mengambil nama tim dari tabel tims
        $teams = Tim::pluck('nama_tim'); // Mengambil nama_tim saja

        // Menghitung progres target/realisasi dan durasi untuk setiap kegiatan
        foreach ($kegiatan as $index => $item) {
            // Hitung progres target/realisasi
            $targetProgress = ($item->realisasi / $item->target) * 100;

            // Hitung durasi progres
            $mulai = \Carbon\Carbon::parse($item->tanggal_mulai)->setTimezone('Asia/Jakarta');
            $berakhir = \Carbon\Carbon::parse($item->tanggal_berakhir)->setTimezone('Asia/Jakarta');

            $hariIni = \Carbon\Carbon::now();  // Tanggal saat ini


            // Hitung total durasi dari mulai hingga berakhir
            $durasiTotal = $mulai->diffInDays($berakhir) ?: 1;  // Menghindari hasil 0 hari

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


        // Tampilkan ke view 'daftarkegiatan' dengan mengirimkan variabel $kegiatan dan $teams
        return view('PJ.daftarkegiatan', ['kegiatan' => $kegiatan, 'teams' => $teams]);
    }


    // Menampilkan Halaman Tambah Kegiatan
    public function create()
    {
        // Tampilkan halaman tambah kegiatan
        return view('pj.tambahkegiatan');
    }

    // Menyimpan Kegiatan Baru ke Databaseuse App\Models\Kegiatan;
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'nama_tim' => 'required|string|max:255', // Validasi untuk nama tim
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:mulai',
            'target' => 'required|numeric|min:0',
            'realisasi' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Cari tim berdasarkan nama tim atau buat tim baru jika tidak ditemukan
        $tim = Tim::firstOrCreate(
            ['nama_tim' => $request->nama_tim], // Cari berdasarkan nama tim
            ['nama_tim' => $request->nama_tim]  // Data yang digunakan jika membuat tim baru
        );

        // Simpan data kegiatan ke dalam database dengan id_tim dari tim yang ditemukan atau baru dibuat
        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'id_tim' => $tim->id_tim, // ID tim yang sudah ada atau baru dibuat
            'tanggal_mulai' => $request->mulai,
            'tanggal_berakhir' => $request->berakhir,
            'target' => $request->target,
            'realisasi' => $request->realisasi,
            'satuan' => $request->satuan,
            'status' => $request->status,
        ]);

        // Redirect ke halaman daftar kegiatan dengan pesan sukses
        return redirect()->route('pjdaftarkegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function edit($id_kegiatan)
    {
        $kegiatan = Kegiatan::findOrFail($id_kegiatan); // Mencari kegiatan berdasarkan id_kegiatan
        return view('pj.editkegiatan', compact('kegiatan')); // Kirim data kegiatan ke view
    }




    // Mengupdate Kegiatan di Database
    public function update(Request $request, $id_kegiatan)
    {
        $validatedData = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'nama_tim' => 'required|string|max:255',
            'mulai' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:mulai',
            'target' => 'required|numeric|min:1',
            'realisasi' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $kegiatan = Kegiatan::findOrFail($id_kegiatan);

        $tim = Tim::firstOrCreate(
            ['nama_tim' => $request->nama_tim],
            ['nama_tim' => $request->nama_tim]
        );

        $kegiatan->update(array_merge($validatedData, [
            'id_tim' => $tim->id_tim,
            'tanggal_mulai' => $validatedData['mulai'], // Menambahkan update untuk tanggal_mulai
            'tanggal_berakhir' => $validatedData['berakhir'], // Menambahkan update untuk tanggal_berakhir
        ]));

        return redirect()->route('pjdaftarkegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    // Menghapus Kegiatan dari Database
    public function destroy($id_kegiatan)
    {
        // Temukan kegiatan berdasarkan ID
        $kegiatan = Kegiatan::findOrFail($id_kegiatan);

        // Temukan tim terkait berdasarkan id_tim dari kegiatan
        $tim = Tim::find($kegiatan->id_tim);

        // Hapus kegiatan terlebih dahulu
        $kegiatan->delete();

        // Jika tidak ada kegiatan lain yang terkait dengan tim tersebut, hapus tim
        if ($tim && $tim->kegiatans()->count() == 0) {
            $tim->delete();
        }

        // Redirect dengan pesan sukses
        return redirect()->route('pjdaftarkegiatan.index')->with('success', 'Kegiatan dan tim terkait berhasil dihapus.');
    }


    // Fungsi untuk download file Excel atau CSV
    public function download($format)
    {
        // Menyiapkan data untuk ekspor (menggunakan filter yang sama seperti di index)
        $data = Kegiatan::with('tim')->get();  // Ambil semua data dengan relasi tim

        // Menggunakan KegiatanExport untuk mengekspor data
        $kegiatanExport = new KegiatanExport($data);

        // Cek format yang diminta (excel atau csv)
        if ($format == 'excel') {
            return Excel::download($kegiatanExport, 'kegiatan.xlsx');
        } elseif ($format == 'csv') {
            return Excel::download($kegiatanExport, 'kegiatan.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        // Jika format tidak valid
        return response()->json(['error' => 'Format tidak valid'], 400);
    }


    public function printPage()
    {
        $kegiatan = Kegiatan::all();
        return view('pj.printkegiatan', compact('kegiatan'));
    }
}

class KegiatanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    // Constructor untuk menerima data kegiatan yang sudah difilter
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Mengambil data kegiatan
    public function collection()
    {
        return $this->data; // Menggunakan data yang diterima melalui constructor
    }

    // Menambahkan header kolom
    public function headings(): array
    {
        return ['No', 'Nama Kegiatan', 'Tim Kerja', 'Mulai', 'Berakhir', 'Target', 'Realisasi', 'Satuan', 'Status'];
    }

    // Mapping data untuk setiap baris
    public function map($kegiatan): array
    {
        static $no = 1;

        // Pastikan tanggal_mulai dan tanggal_berakhir adalah objek Carbon
        $tanggalMulai = \Carbon\Carbon::parse($kegiatan->tanggal_mulai);  // Konversi ke Carbon jika belum
        $tanggalBerakhir = \Carbon\Carbon::parse($kegiatan->tanggal_berakhir);  // Konversi ke Carbon jika belum

        return [
            $no++, // Nomor urut
            $kegiatan->nama_kegiatan,
            $kegiatan->tim ? $kegiatan->tim->nama_tim : 'Tidak ada tim', // Pastikan tim ada
            $tanggalMulai->format('Y-m-d'), // Gunakan format tanggal yang benar
            $tanggalBerakhir->format('Y-m-d'), // Gunakan format tanggal yang benar
            $kegiatan->target,
            // Cek apakah realisasi kosong atau null, jika ya, beri nilai 0
            $kegiatan->realisasi ?: 0,  // Jika realisasi kosong atau null, set jadi 0
            $kegiatan->satuan,
            $kegiatan->status ? 'Aktif' : 'Tidak Aktif',
        ];
    }
}
