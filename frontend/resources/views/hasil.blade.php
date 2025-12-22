<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden">
        <div class="bg-green-600 p-6 text-center">
            <h1 class="text-2xl font-bold text-white">Hasil Analisa Ontology</h1>
            <p class="text-green-100 mt-1">Berdasarkan Ontologi Semantic Web</p>
        </div>

        <div class="p-8">
            <div class="border-b border-gray-100 pb-6 mb-6">
                <p class="text-sm text-gray-500">Nama Pasien</p>
                <p class="text-xl font-semibold text-gray-900">{{ $hasil['penyakit'] ? 'Sdr/i ' : '' }} {{ request('nama') }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-3">Dugaan Penyakit</h2>
                
                @if(count($hasil['penyakit']) > 0)
                    @foreach($hasil['penyakit'] as $penyakit)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-3 rounded-r-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span class="text-lg font-bold text-red-700">
                                    {{ str_replace('_', ' ', str_replace('Penyakit_', '', $penyakit)) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-gray-100 p-4 rounded-lg text-center text-gray-500">
                        Tidak ditemukan kecocokan penyakit dengan gejala yang diberikan.
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-semibold mb-3">Rujukan Spesialis</h2>
                @if(count($hasil['spesialis']) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($hasil['spesialis'] as $spesialis)
                            <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-medium text-sm">
                                {{ str_replace('_', ' ', $spesialis) }}
                            </span>
                        @endforeach
                    </div>
                @else
                     <p class="text-gray-600 italic">Silakan konsultasi ke Dokter Gigi Umum terlebih dahulu.</p>
                @endif
            </div>

            <div class="mt-8 text-center">
                <a href="/" class="text-green-600 font-semibold hover:text-green-700">← Cek Ulang</a>
            </div>
        </div>
    </div>

</body>
</html>