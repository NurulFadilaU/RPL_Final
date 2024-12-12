<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Sistem Monitoring Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="flex flex-col lg:flex-row min-h-screen">
    <div class="flex flex-col lg:grid lg:grid-cols-[250px_1fr] h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 border-2 left-0 bg-white text-orange-500 flex flex-col items-center py-12 px-5 w-64 transform lg:translate-x-0 lg:relative lg:w-64 lg:h-full transition-transform duration-300 ease-in-out">
            <div class="flex flex-col items-center justify-center mb-10">
                <img src="{{ asset('images/bps.png') }}" alt="BPS Sumbawa" class="w-16 sm:w-24 mb-5 lg:mb-10">
                <h2 class="text-center text-lg sm:text-xl text-black font-bold">
                    Badan Pusat Statistik <br> Kabupaten Sumbawa
                </h2>
            </div>
            <nav class="w-full">
                <ul class="text-center space-y-2">
                    <li class="py-2 lg:py-4 cursor-pointer hover:bg-orange-300 transition rounded-lg">Dashboard</li>
                    <li class="py-2 lg:py-4 cursor-pointer bg-orange-500 text-white rounded-lg">
                        <a href="{{ route('pimpinandaftarkegiatan.index') }}" class="block">Daftar Kegiatan</a>
                    </li>
                    <li class="py-2 lg:py-4 cursor-pointer hover:bg-orange-300 transition rounded-lg">Jadwal Kegiatan
                    </li>
                    <a href="{{ route('pimpinanevaluasikegiatan') }}">
                        <li class="py-2 lg:py-4 cursor-pointer hover:bg-orange-300 transition rounded-lg">
                            Evaluasi Kegiatan
                        </li>
                    </a>
                </ul>
            </nav>
            <button class="mt-auto py-2 px-14 bg-red-600 text-white rounded-lg">Log Out</button>
        </aside>


        <!-- Main Content -->
        <div class="flex flex-col overflow-y-auto">
            <header
                class="flex justify-between items-center bg-gradient-to-r from-yellow-400 to-orange-400 px-5 py-2 h-16 text-white">
                <!-- Hamburger Icon -->
                <div id="hamburger" class="z-50 cursor-pointer lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Sistem Monitoring</h1>
                <div class="flex items-center gap-3">
                    <span>Pimpinan</span>
                    <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                    <div class="relative">
                        <i class="fa fa-bell"></i>
                        <span class="absolute top-0 right-0 bg-red-600 text-xs text-white rounded-full px-2">3</span>
                    </div>
                </div>
            </header>


            <main class="px-6 py-4">
                <h2 class="text-4xl font-bold text-orange-500 mb-6">Kegiatan</h2>

                <div class="border-2 border-orange-500 rounded-xl p-6 mb-6">
                    <div class="flex flex-wrap gap-6 items-center justify-between w-full">
                        <!-- Dropdown dan Tombol -->
                        <div class="flex flex-wrap gap-4 items-center">
                            <!-- Dropdown dan Tombol Filter -->
                            <form action="{{ route('pimpinandaftarkegiatan.index') }}" method="GET"
                                class="flex flex-wrap gap-4 items-center">
                                <span class="py-2 px-4 font-bold text-xl text-orange-500">Filter</span>

                                <!-- Dropdown Tim -->
                                <select name="team"
                                    class="py-2 px-4 text-lg border border-gray-300 rounded-md w-full sm:w-48">
                                    <option value="">Pilih Tim</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team }}"
                                            {{ request('team') == $team ? 'selected' : '' }}>
                                            {{ $team }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Dropdown Bulan -->
                                <select name="month"
                                    class="py-2 px-2 text-lg border border-gray-300 rounded-md w-full sm:w-40">
                                    <option value="">Pilih Bulan</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}"
                                            {{ request('month') == $month ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Dropdown Tahun -->
                                <select name="year"
                                    class="py-2 px-2 text-lg border border-gray-300 rounded-md w-full sm:w-40">
                                    <option value="">Pilih Tahun</option>
                                    @foreach (range(date('Y'), date('Y') - 5) as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Tombol Filter -->
                                <button type="submit"
                                    class="py-2 px-4 bg-orange-500 text-white text-lg rounded-md w-full sm:w-40">
                                    Filter
                                </button>
                            </form>
                        </div>

                        <div class="flex flex-wrap gap-6 items-center justify-between w-full">
                            <!-- Tombol Ekspor (Excel, CSV, Print) - Rata Kiri -->
                            <div class="flex gap-4 items-center w-full sm:w-auto mb-4">
                                <!-- Tombol Excel -->
                                <a href="{{ route('pimpinandaftarkegiatan.download', ['format' => 'excel']) }}"
                                    class="flex justify-center items-center py-2 px-4 bg-green-500 text-white text-lg rounded-md w-full sm:w-40">Excel</a>

                                <!-- Tombol CSV -->
                                <a href="{{ route('pimpinandaftarkegiatan.download', ['format' => 'csv']) }}"
                                    class="flex justify-center items-center py-2 px-4 bg-green-500 text-white text-lg rounded-md w-full sm:w-40">CSV</a>

                                <!-- Tombol Print -->
                                <a href="{{ route('pimpinandaftarkegiatan.print') }}" target="_blank"
                                    class="flex justify-center items-center py-2 px-4 bg-green-500 text-white text-lg rounded-md w-full sm:w-40">Print</a>
                            </div>

                            <!-- Pencarian -->
                            <div class="flex gap-4 items-center w-full sm:w-auto mb-4">
                                <span class="font-bold text-xl text-orange-500">Search:</span>
                                <input type="text"
                                    class="py-2 px-4 text-lg border-2 border-orange-500 rounded-md w-full sm:w-48"
                                    placeholder="Pencarian" oninput="handleSearch(event)" />
                            </div>
                        </div>



                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full table-auto border-collapse mt-4">
                            <thead>
                                <tr class="text-center border-b border-orange-500">
                                    <th class="p-2 text-center border border-orange-500">No</th>
                                    <th class="p-2 text-center border border-orange-500">Kegiatan</th>
                                    <th class="p-2 text-center border border-orange-500">Tim Kerja</th>
                                    <th class="p-2 text-center border border-orange-500">Mulai</th>
                                    <th class="p-2 text-center border border-orange-500">Berakhir</th>
                                    <th class="p-2 text-center border border-orange-500">Target</th>
                                    <th class="p-2 text-center border border-orange-500">Realisasi</th>
                                    <th class="p-2 text-center border border-orange-500">Satuan</th>
                                </tr>
                            </thead>
                            <tbody id="kegiatan-table-body">
                                @forelse ($kegiatan as $item)
                                    <tr class="text-center border-b border-orange-500  text-sm">
                                        <td rowspan="2" class="p-2 border border-orange-500">
                                            {{ $kegiatan->firstItem() + $loop->index }}</td>
                                        <td rowspan="2" class="p-2 border border-orange-500">
                                            {{ $item->nama_kegiatan }}</td>
                                        <td rowspan="2" class="p-2 border border-orange-500">
                                            {{ $item->tim->nama_tim ?? 'Tidak Ada Tim' }}</td>
                                        <td class="p-2 border border-orange-500">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('j F Y') }}</td>
                                        <td class="p-2 border border-orange-500">
                                            {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('j F Y') }}</td>
                                        <td class="p-2 border border-orange-500">{{ $item->target }}</td>
                                        <td class="p-2 border border-orange-500">{{ $item->realisasi }}</td>
                                        <td rowspan="2" class="p-2 border border-orange-500">{{ $item->satuan }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- Progress bar untuk durasi -->
                                        <td colspan="2" class="p-2 border-b border-orange-500">
                                            <!-- Tambahkan kelas border-b -->
                                            <div class="w-full bg-gray-200 rounded-full h-4">
                                                <div class="bg-green-500 h-4 rounded-full"
                                                    style="width: {{ $item->duration_progress }}%;"></div>

                                            </div>
                                        </td>
                                        <!-- Progress bar untuk target/realisasi -->
                                        <td colspan="2" class="p-2 border-b border-l border-orange-500">
                                            <!-- Tambahkan kelas border-l dan border-b -->
                                            <div class="w-full bg-gray-200 rounded-full h-4">
                                                <div class="bg-green-500 h-4 rounded-full"
                                                    style="width: {{ $item->target_progress }}%;"></div>

                                            </div>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center p-4 text-gray-500">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>



                        <div class="flex justify-between items-center mt-6">
                            <div id="pagination-text" class="text-gray-700">
                                Showing {{ $kegiatan->firstItem() }} to {{ $kegiatan->lastItem() }} of
                                {{ $kegiatan->total() }} entries
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Tombol Previous -->
                                @if ($kegiatan->onFirstPage())
                                    <button
                                        class="py-2 px-4 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed">Previous</button>
                                @else
                                    <a href="{{ $kegiatan->previousPageUrl() }}"
                                        class="py-2 px-4 bg-orange-500 text-white rounded-md">Previous</a>
                                @endif

                                <!-- Nomor Halaman -->
                                @for ($i = 1; $i <= $kegiatan->lastPage(); $i++)
                                    @if ($i == $kegiatan->currentPage())
                                        <span
                                            class="py-2 px-4 bg-orange-500 text-white rounded-md">{{ $i }}</span>
                                    @else
                                        <a href="{{ $kegiatan->url($i) }}"
                                            class="py-2 px-4 rounded-md hover:bg-orange-500 hover:text-white">{{ $i }}</a>
                                    @endif
                                @endfor

                                <!-- Tombol Next -->
                                @if ($kegiatan->hasMorePages())
                                    <a href="{{ $kegiatan->nextPageUrl() }}"
                                        class="py-2 px-4 bg-orange-500 text-white rounded-md">Next</a>
                                @else
                                    <button
                                        class="py-2 px-4 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed">Next</button>
                                @endif
                            </div>
                        </div>



            </main>

            <footer class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-6 sm:px-6 py-4 mt-auto">
                <div class="max-w-screen-lg mx-auto">
                    <div class="text-sm sm:text-lg font-medium">
                        © 2024 <span class="text-green-500">Tim Pengolahan dan TI</span> Badan Pusat Statistik
                    </div>
                </div>
            </footer>


        </div>
    </div>
    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        function handleSearch(event) {
            const query = event.target.value.trim()
                .toLowerCase(); // Ambil nilai input dan hapus spasi di awal/akhir serta ubah ke huruf kecil
            const rows = document.querySelectorAll('#kegiatan-table-body tr'); // Ambil semua baris di tabel

            // Jika query kosong setelah dipangkas (hanya spasi), kembalikan ke tampilan semula dan keluar dari fungsi
            if (query === '') {
                rows.forEach((row) => {
                    row.style.display = ''; // Tampilkan semua baris
                });
                // Perbarui teks pagination kembali ke kondisi semula
                const paginationText = document.getElementById('pagination-text');
                paginationText.textContent =
                    `Showing {{ $kegiatan->firstItem() }} to {{ $kegiatan->lastItem() }} of {{ $kegiatan->total() }} entries`;
                return;
            }

            let currentNumber = 1; // Inisialisasi nomor urut baru
            let showNextRow = false; // Penanda untuk menampilkan baris selanjutnya (progres bar)
            let visibleCount = 0; // Hitung jumlah baris yang ditampilkan

            rows.forEach((row) => {
                // Kolom yang diperiksa: Nama Kegiatan dan Tim Kerja
                const namaKegiatan = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                const timKerja = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                if (showNextRow) {
                    // Jika baris sebelumnya cocok, tampilkan baris ini (untuk progres bar)
                    row.style.display = '';
                    showNextRow = false; // Reset penanda
                } else if (namaKegiatan.includes(query) || timKerja.includes(query)) {
                    // Jika baris utama cocok, tampilkan dan perbarui nomor urut
                    row.style.display = '';
                    row.querySelector('td:nth-child(1)').textContent = currentNumber++; // Perbarui nomor urut
                    showNextRow = true;
                    visibleCount++; // Tambahkan jumlah baris yang ditampilkan
                } else {
                    // Jika tidak cocok, sembunyikan baris ini
                    row.style.display = 'none';
                }
            });

            // Perbarui teks "Showing X to Y of Z entries"
            const paginationText = document.getElementById('pagination-text');

            if (visibleCount === 0) {
                // Jika tidak ada entri yang ditampilkan
                paginationText.textContent =
                    `Showing 0 to 0 of 0 entries (filtered from {{ $kegiatan->total() }} total entries)`;
            } else {
                // Jika ada entri yang ditampilkan
                paginationText.textContent =
                    `Showing 1 to ${visibleCount} of ${visibleCount} entries (filtered from {{ $kegiatan->total() }} total entries)`;
            }
        }
    </script>

</body>

</html>
