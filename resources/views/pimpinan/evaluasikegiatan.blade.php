<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="flex flex-row h-screen">
    <!-- Sidebar -->
    <aside class="bg-white border-2 text-orange-500 flex flex-col items-center py-12 px-5 w-64 h-full">
        <div class="flex flex-col items-center justify-center mb-10">
            <img src="{{ asset('images/bps.png') }}" alt="BPS Sumbawa" class="w-16 sm:w-24 mb-5 lg:mb-10">
            <h2 class="text-center text-lg sm:text-xl text-black font-bold">
                Badan Pusat Statistik <br> Kabupaten Sumbawa
            </h2>
        </div>

        <nav class="w-full">
            <ul class="text-center space-y-2">
                <li class="py-2 lg:py-4 cursor-pointer hover:bg-orange-300 transition rounded-lg">Dashboard</li>
                <li class="py-2 lg:py-4 cursor-pointer hover:bg-orange-300 transition rounded-lg">
                    <a href="{{ route('pimpinandaftarkegiatan.index') }}" class="block">Daftar Kegiatan</a>
                </li>
                <li class="py-2 lg:py-4 cursor-pointer hover:bg-orange-300 transition rounded-lg">Jadwal Kegiatan</li>
                <a href="{{ route('pimpinan.evaluasikegiatan') }}">
                    <li class="py-2 lg:py-4 cursor-pointer bg-orange-500 text-white rounded-lg">
                        Evaluasi Kegiatan
                    </li>
                </a>
            </ul>
        </nav>
        <button class="mt-auto py-2 px-14 bg-red-600 text-white rounded-lg">Log Out</button>
    </aside>

    <!-- Konten Utama -->
    <div class="flex flex-col flex-grow h-full">
        <header
            class="flex justify-between items-center bg-gradient-to-r from-yellow-400 to-orange-400 px-5 py-2 h-16 text-white">
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

        <main class="flex-grow p-6 overflow-y-auto">
            <h2 class="text-4xl font-bold mb-4 flex justify-between items-center">
                Evaluasi Kegiatan
                <div class="flex gap-4">
                    <a href="#belum-dievaluasi">
                        <button class="border-2 px-3 py-2 bg-gray-200 rounded-xl text-xl font-normal">Belum
                            Dievaluasi</button>
                    </a>
                    <a href="#sudah-dievaluasi">
                        <button class="border-2 px-3 py-2 rounded-xl text-xl font-normal">Sudah Dievaluasi</button>
                    </a>
                </div>
            </h2>

            <!-- Daftar Kegiatan Belum Dievaluasi -->
            <section id="belum-dievaluasi" class="mb-6">
                <h3 class="text-2xl font-bold mb-4">Belum Dievaluasi</h3>
                <div class="border-2 rounded-xl p-6 space-y-4">
                    @foreach ($belumDievaluasi as $item)
                        <div class="bg-gray-100 p-4 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <p class="text-lg">{{ $item->nama_kegiatan }}</p>
                                <button onclick="toggleDetail({{ $item->id_kegiatan }})" class="text-gray-500 text-xl">
                                    <img id="toggle-icon-{{ $item->id_kegiatan }}" src="{{ asset('images/add.png') }}"
                                        alt="Toggle Detail" class="w-6 h-6" />
                                </button>
                            </div>
                            <div id="detail-{{ $item->id_kegiatan }}" style="display: none;">
                                <p>Tanggal Mulai: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</p>
                                <p>Tanggal Berakhir:
                                    {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</p>
                                <form action="{{ route('pimpinanevaluasikegiatan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_kegiatan" value="{{ $item->id_kegiatan }}">
                                    <textarea name="evaluasi" class="w-full border border-gray-300 rounded-lg mt-2 p-2"
                                        placeholder="Tambahkan Evaluasi Kegiatan..."></textarea>
                                    <div class="flex justify-end gap-2 mt-3">
                                        <button type="reset"
                                            class="py-2 px-4 bg-red-500 text-white rounded-lg">Batal</button>
                                        <button type="submit"
                                            class="py-2 px-4 bg-green-500 text-white rounded-lg">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Daftar Kegiatan Sudah Dievaluasi -->
            <section id="sudah-dievaluasi" class="mb-6">
                <h3 class="text-2xl font-bold mb-4">Sudah Dievaluasi</h3>
                <div class="border-2 rounded-xl p-6 space-y-4">
                    @foreach ($sudahDievaluasi as $item)
                        @if ($item->evaluasis->isNotEmpty())
                            <!-- Cek apakah ada evaluasi terkait -->
                            <div class="bg-gray-100 p-4 rounded-lg shadow">
                                <div class="flex justify-between items-center">
                                    <p class="text-lg">{{ $item->nama_kegiatan }}</p>
                                    <button onclick="toggleDetail({{ $item->id_kegiatan }})"
                                        class="text-gray-500 text-xl">
                                        <img id="toggle-icon-{{ $item->id_kegiatan }}"
                                            src="{{ asset('images/add.png') }}" alt="Toggle Detail" class="w-6 h-6" />
                                    </button>
                                </div>
                                <div id="detail-{{ $item->id_kegiatan }}" style="display: none;">
                                    <p>Tanggal Mulai:
                                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</p>
                                    <p>Tanggal Berakhir:
                                        {{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</p>

                                    <form action="{{ route('pimpinanevaluasikegiatan.edit') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Tambahkan ini untuk menunjukkan bahwa permintaan adalah untuk mengupdate -->
                                        <input type="hidden" name="id_kegiatan" value="{{ $item->id_kegiatan }}">

                                        <textarea name="evaluasi" class="w-full border border-gray-300 rounded-lg mt-2 p-2 text-left"
                                            placeholder="Tambahkan Evaluasi Kegiatan...">{{ $item->evaluasis->first()->evaluasi ?? '' }}</textarea>

                                        <div class="flex justify-end gap-2 mt-3">
                                            <button type="submit"
                                                class="py-2 px-4 bg-blue-500 text-white rounded-lg">Edit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        @endif
                    @endforeach




                </div>
            </section>
        </main>

        <footer class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-6 py-4">
            <div class="text-sm sm:text-lg font-medium">
                © 2024 <span class="text-green-500">Tim Pengolahan dan TI</span> Badan Pusat Statistik
            </div>
        </footer>
    </div>

    <script>
        function toggleDetail(id) {
            const detailElement = document.getElementById(`detail-${id}`);
            const iconElement = document.getElementById(`toggle-icon-${id}`);

            if (detailElement.style.display === "none" || detailElement.style.display === "") {
                detailElement.style.display = "block";
                iconElement.src = "{{ asset('images/minus.png') }}";
            } else {
                detailElement.style.display = "none";
                iconElement.src = "{{ asset('images/add.png') }}";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Mengaktifkan tab yang sesuai dengan URL hash
            function toggleSection() {
                const hash = window.location.hash;
                document.querySelectorAll('section').forEach(section => {
                    section.style.display = 'none'; // sembunyikan semua section
                    if (section.id === hash.replace('#', '')) {
                        section.style.display = 'block'; // tampilkan yang sesuai
                    }
                });
            }

            // Memanggil fungsi ketika halaman dimuat atau hash diubah
            toggleSection();
            window.addEventListener('hashchange', toggleSection);
        });
    </script>
</body>

</html>
