<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Tambah Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="grid grid-cols-[250px_1fr] h-screen">
        <!-- Sidebar -->
        <aside class="bg-white text-orange-500 border-2 flex flex-col items-center py-12 px-5 h-full">
            <img src="{{ asset('images/bps.png') }}" alt="BPS Sumbawa" class="w-24 mb-10">
            <h2 class="text-center text-xl text-black">Badan Pusat Statistik <br> Kabupaten Sumbawa</h2>
            <nav class="w-full">
                <ul class="text-center">
                    <li class="py-4 cursor-pointer">Dashboard</li>
                    <li class="py-4 cursor-pointer bg-orange-600 text-white">Daftar Kegiatan</li>
                    <li class="py-4 cursor-pointer">Jadwal Kegiatan</li>
                    <li class="py-4 cursor-pointer">Evaluasi Kegiatan</li>
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
                    <span>Penanggung Jawab</span>
                    <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                    <div class="relative">
                        <i class="fa fa-bell"></i>
                        <span class="absolute top-0 right-0 bg-red-600 text-xs text-white rounded-full px-2">3</span>
                    </div>
                </div>
            </header>

            <main class="px-6 py-4">
                <h2 class="text-4xl font-bold text-orange-500 mb-6">Tambah Kegiatan</h2>

                <div class="border-2 border-orange-500 rounded-xl p-6 mb-6">

                    <!-- Menampilkan pesan sukses -->
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 mb-4 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('kegiatan.store') }}" method="POST">
                        @csrf

                        <!-- Input Nama Kegiatan -->
                        <div class="mb-4">
                            <label for="nama_kegiatan" class="block text-sm font-medium">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="border p-2 w-full"
                                required>
                        </div>

                        <!-- Input Nama Tim -->
                        <div class="mb-4">
                            <label for="nama_tim" class="block text-sm font-medium">Nama Tim</label>
                            <input type="text" name="nama_tim" id="nama_tim" class="border p-2 w-full" required>
                        </div>

                        <!-- Input Tanggal Mulai -->
                        <div class="mb-4">
                            <label for="mulai" class="block text-sm font-medium">Tanggal Mulai</label>
                            <input type="date" name="mulai" id="mulai" class="border p-2 w-full" required>
                        </div>

                        <!-- Input Tanggal Berakhir -->
                        <div class="mb-4">
                            <label for="berakhir" class="block text-sm font-medium">Tanggal Berakhir</label>
                            <input type="date" name="berakhir" id="berakhir" class="border p-2 w-full" required>
                        </div>

                        <!-- Input Target -->
                        <div class="mb-4">
                            <label for="target" class="block text-sm font-medium">Target</label>
                            <input type="number" name="target" id="target" class="border p-2 w-full" required>
                        </div>

                        <!-- Input Realisasi -->
                        <div class="mb-4">
                            <label for="realisasi" class="block text-sm font-medium">Realisasi</label>
                            <input type="number" name="realisasi" id="realisasi" class="border p-2 w-full" required>
                        </div>

                        <!-- Input Satuan -->
                        <div class="mb-4">
                            <label for="satuan" class="block text-sm font-medium">Satuan</label>
                            <input type="text" name="satuan" id="satuan" class="border p-2 w-full" required>
                        </div>

                        <!-- Input Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium">Status</label>
                            <select name="status" id="status" class="border p-2 w-full" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan
                            Kegiatan</button>
                    </form>




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
</body>

</html>
