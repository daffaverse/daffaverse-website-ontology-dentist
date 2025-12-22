<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl max-w-3xl w-full overflow-hidden">
        <div class="bg-green-600 p-6 text-center">
            <h1 class="text-2xl font-bold text-white">Hasil Analisa Ontology</h1>
            <p class="text-green-100 mt-1">Berdasarkan Ontologi Semantic Web</p>
        </div>

        <div class="p-8">
            <div class="border-b border-gray-100 pb-6 mb-6">
                <p class="text-sm text-gray-500">Nama Pasien</p>
                <p class="text-xl font-semibold text-gray-900">
                    {{ count($hasil['penyakit']) > 0 ? 'Sdr/i ' : '' }} {{ request('nama') }}
                </p>
            </div>

            <div class="mb-8">
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-4">Dugaan Penyakit</h2>
                
                @if(count($hasil['penyakit']) > 0)
                    <div class="space-y-4">
                        @foreach($hasil['penyakit'] as $penyakit)
                            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-start gap-4">
                                    <div class="bg-red-100 p-2 rounded-lg mt-1">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                                            {{ $penyakit['nama'] }}
                                        </h3>
                                        
                                        @if($penyakit['deskripsi'])
                                            <p class="text-gray-600 text-sm leading-relaxed mb-3">
                                                {{ $penyakit['deskripsi'] }}
                                            </p>
                                        @else
                                            <p class="text-gray-400 text-sm italic mb-3">
                                                Tidak ada deskripsi tersedia untuk penyakit ini.
                                            </p>
                                        @endif

                                        @if($penyakit['link'])
                                            <a href="{{ $penyakit['link'] }}" target="_blank" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                                Baca Referensi Medis
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-100 p-6 rounded-xl text-center text-gray-500 border border-dashed border-gray-300">
                        <p class="font-medium">Tidak ditemukan kecocokan penyakit dengan gejala yang diberikan.</p>
                        <p class="text-sm mt-1">Coba tambahkan detail gejala lainnya.</p>
                    </div>
                @endif
            </div>

            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                <h2 class="text-sm uppercase tracking-wide text-blue-800 font-semibold mb-3">Rujukan Spesialis</h2>
                @if(count($hasil['spesialis']) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($hasil['spesialis'] as $spesialis)
                            <span class="bg-white text-blue-700 border border-blue-200 px-4 py-2 rounded-lg font-medium text-sm shadow-sm">
                                {{ $spesialis }}
                            </span>
                        @endforeach
                    </div>
                @else
                     <p class="text-blue-600 text-sm">Silakan konsultasi ke <span class="font-semibold">Dokter Gigi Umum</span> terlebih dahulu untuk pemeriksaan awal.</p>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="/" class="inline-block px-6 py-2.5 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    ← Cek Ulang Pasien Lain
                </a>
            </div>
        </div>
    </div>

</body>
</html>
