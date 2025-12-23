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
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 p-8 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">Hasil Analisa Medis</h1>
            <p class="text-green-100 text-sm opacity-90">Sistem Pakar Gigi Berbasis Ontology Semantic Web</p>
        </div>

        <div class="p-8">
            <!-- Info Pasien -->
            <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-8">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Nama Pasien</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">
                        {{ request('nama') }}
                    </p>
                </div>
                <div class="bg-green-50 text-green-700 px-4 py-2 rounded-lg text-sm font-medium">
                    {{ date('d M Y, H:i') }}
                </div>
            </div>

            <!-- Hasil Penyakit -->
            <div class="mb-10">
                <h2 class="text-sm uppercase tracking-wide text-gray-500 font-bold mb-5 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Dugaan Penyakit & Kondisi
                </h2>
                
                @if(count($hasil['penyakit']) > 0)
                    <div class="space-y-6">
                        @foreach($hasil['penyakit'] as $penyakit)
                            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
                                
                                <div class="flex flex-col md:flex-row gap-5">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">
                                            {{ $penyakit['nama'] }}
                                        </h3>
                                        
                                        <!-- Deskripsi (Comment) -->
                                        @if(!empty($penyakit['deskripsi']))
                                            <div class="text-gray-600 text-sm leading-relaxed mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                {{ $penyakit['deskripsi'] }}
                                            </div>
                                        @else
                                            <p class="text-gray-400 text-sm italic mb-4">
                                                (Belum ada deskripsi detail pada Ontology untuk penyakit ini)
                                            </p>
                                        @endif

                                        <!-- Link (SeeAlso) -->
                                        @if(!empty($penyakit['link']))
                                            <a href="{{ $penyakit['link'] }}" target="_blank" rel="noopener noreferrer" 
                                               class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors group/link">
                                                <span>Baca Referensi Medis</span>
                                                <svg class="w-4 h-4 transition-transform group-hover/link:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 p-8 rounded-2xl text-center border-2 border-dashed border-gray-200">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-medium text-gray-600">Tidak ditemukan penyakit yang cocok.</p>
                        <p class="text-sm text-gray-500 mt-1">Sistem tidak dapat menyimpulkan penyakit berdasarkan gejala yang dimasukkan.</p>
                    </div>
                @endif
            </div>

            <!-- Rujukan Spesialis -->
            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                <h2 class="text-sm uppercase tracking-wide text-blue-800 font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Rekomendasi Spesialis
                </h2>
                
                @if(count($hasil['spesialis']) > 0)
                    <div class="flex flex-wrap gap-3">
                        @foreach($hasil['spesialis'] as $spesialis)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white text-blue-700 shadow-sm border border-blue-200">
                                {{ $spesialis }}
                            </span>
                        @endforeach
                    </div>
                @else
                     <div class="flex items-center gap-3 text-blue-700 bg-white p-3 rounded-lg border border-blue-100">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm">Disarankan konsultasi ke <span class="font-bold">Dokter Gigi Umum</span> untuk pemeriksaan awal.</p>
                     </div>
                @endif
            </div>

            <!-- Footer Action -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                <a href="/" class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Halaman Utama
                </a>
                <button onclick="window.print()" class="text-green-600 hover:text-green-700 font-medium text-sm flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Hasil
                </button>
            </div>
        </div>
    </div>

</body>
</html>