<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Gigi Pintar</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-medical-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-medical-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">Dental<span class="text-medical-600">Smart</span></span>
                </div>
                <div class="text-sm text-gray-500 hidden sm:block">Sistem Rekomendasi Spesialis Gigi</div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-8 sm:px-6">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cek Kesehatan Gigi Anda</h1>
            <p class="text-gray-500">Isi formulir di bawah ini untuk analisa sistem pakar (Semantic Web).</p>
        </div>

        <form action="/diagnosa/submit" method="POST" class="space-y-8">
            
            @csrf
            
            <!-- STEP 1 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">1</span>
                    <h2 class="text-lg font-semibold text-gray-900">Identitas Pasien</h2>
                </div>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" required 
                        placeholder="Contoh: Budi Santoso"
                        value="{{ old('nama') }}"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-colors">
                </div>
            </div>

            <!-- STEP 2 (SEARCH BOX GEJALA) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">2</span>
                    <h2 class="text-lg font-semibold text-gray-900">Keluhan Utama</h2>
                </div>

                <div x-data="{ 
                    search: '', 
                    expandNyeri: false,
                    isNyeri(text) { return text.toLowerCase().includes('nyeri') || text.toLowerCase().includes('ngilu'); }
                }" class="space-y-4">

                    <!-- Search Box -->
                    <div class="relative">
                        <input type="text" x-model="search" placeholder="Ketik keluhan Anda... (contoh: bengkak, berdarah, ngilu)" 
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-medical-500 outline-none shadow-sm text-gray-700 placeholder-gray-400">
                        <div class="absolute left-3.5 top-3.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($masterData['gejala'] as $gejala)
                            <div x-show="
                                    (search !== '' && $el.textContent.toLowerCase().includes(search.toLowerCase())) || 
                                    (search === '' && (!isNyeri('{{ $gejala['value'] }}') || expandNyeri))
                                "
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 group">
                                
                                <div class="flex items-center h-5 mt-0.5">
                                    <input type="checkbox" name="gejala[]" value="{{ $gejala['value'] }}" 
                                        {{ (is_array(old('gejala')) && in_array($gejala['value'], old('gejala'))) ? 'checked' : '' }}
                                        class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm cursor-pointer select-none flex-1">
                                    <span class="font-medium text-gray-700 group-hover:text-gray-900 transition-colors">{{ $gejala['label'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic col-span-2 text-center py-4">Data gejala belum dimuat. Pastikan backend Python aktif.</p>
                        @endforelse
                    </div>

                    <div x-show="search === ''" class="text-center pt-2">
                        <button type="button" @click="expandNyeri = !expandNyeri" 
                            class="text-sm font-semibold text-medical-600 bg-medical-50 hover:bg-medical-100 px-6 py-2 rounded-full border border-medical-200 transition-all flex items-center justify-center gap-2 mx-auto">
                            <span x-text="expandNyeri ? 'Sembunyikan Opsi Nyeri Detail' : 'Tampilkan Pilihan Nyeri & Ngilu'"></span>
                            <svg x-show="!expandNyeri" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            <svg x-show="expandNyeri" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">3</span>
                    <h2 class="text-lg font-semibold text-gray-900">Pemeriksaan & Pemicu</h2>
                </div>
                
                <div class="mb-8 p-5 bg-blue-50 rounded-xl border border-blue-100">
                    <label for="durasi" class="block text-sm font-semibold text-blue-900 mb-2">
                        Durasi Nyeri (Opsional)
                    </label>
                    <p class="text-xs text-blue-600 mb-2">Jika Anda merasa nyeri/ngilu setelah terkena pemicu (misal minum es), berapa detik rasa sakit itu bertahan?</p>
                    <input type="number" name="durasi" id="durasi" 
                        value="{{ old('durasi') }}"
                        placeholder="Contoh: 5 (detik)" 
                        class="w-full px-4 py-2 rounded-lg border border-white-200 focus:ring-2 focus:ring-blue-500 outline-none placeholder-gray-400">
                    <p class="text-xs text-gray-500 mt-2 italic">*kosongkan jika tidak ada nyeri yang dipicu.</p>
                </div>

                <div>
                     <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                        <label class="block text-sm font-semibold text-yellow-900 mb-2">Lama Keluhan (Hari)</label>
                        <p class="text-xs text-yellow-600 mb-2">Sudah berapa hari Anda merasakan gangguan ini?</p>
                        <input type="number" name="details[lama_hari]" 
                            value="{{ old('details.lama_hari') }}"
                            placeholder="Contoh: 3" 
                            class="w-full px-3 py-2 rounded-lg border border-yellow-200 focus:ring-2 focus:ring-yellow-500 outline-none placeholder-gray-400">
                        <p class="text-xs text-gray-500 mt-2 italic">*kosongkan jika tidak tahu.</p>
                    </div>
                </div>

                <!-- Bagian Kondisi Fisik dengan Fitur Search -->
                <div x-data="{ searchKondisi: '' }" class="mt-10">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-2 mb-3">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Kondisi Fisik (Apa yang terlihat?)</h3>
                        
                        <!-- Search Box Kondisi (Kecil) -->
                        <div class="relative w-full sm:w-64">
                             <input type="text" x-model="searchKondisi" placeholder="Cari kondisi... (misal: lubang)" 
                                class="w-full pl-9 pr-3 py-1.5 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-medical-500 outline-none placeholder-gray-400">
                             <div class="absolute left-3 top-2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @forelse($masterData['kondisi'] as $kondisi)
                            <label x-show="searchKondisi === '' || $el.textContent.toLowerCase().includes(searchKondisi.toLowerCase())"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100" 
                                class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input type="checkbox" name="kondisi[]" value="{{ $kondisi['value'] }}" 
                                        {{ (is_array(old('kondisi')) && in_array($kondisi['value'], old('kondisi'))) ? 'checked' : '' }}
                                        class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm cursor-pointer select-none flex-1">
                                    <span class="font-medium text-gray-700 hover:text-gray-900 transition-colors">{{ $kondisi['label'] }}</span>
                                </div>
                            </label>
                        @empty
                            <p class="text-gray-400 text-sm col-span-2">Tidak ada data kondisi.</p>
                        @endforelse
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Pemicu Utama (Kapan sakit muncul?)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if(isset($masterData['pemicu']) && count($masterData['pemicu']) > 0)
                        @foreach($masterData['pemicu'] as $pemicuItem)
                        <label class="relative flex items-center p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200 {{ old('pemicu') == $pemicuItem['value'] ? 'bg-medical-50 border-medical-200' : '' }}">
                            <input type="radio" name="pemicu" value="{{ $pemicuItem['value'] }}" 
                                {{ old('pemicu') == $pemicuItem['value'] ? 'checked' : '' }} 
                                class="w-4 h-4 text-medical-600 border-gray-300 focus:ring-medical-500">
                            <span class="ml-3 text-sm font-medium text-gray-900">{{ $pemicuItem['label'] }}</span>
                        </label>
                        @endforeach
                    @else
                        <!-- Fallback jika data pemicu belum ada di ontology -->
                        <p class="text-sm text-gray-400 col-span-2">Belum ada data pemicu yang dimuat dari Ontology.</p>
                    @endif
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" 
                    class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-medical-600 hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 transition-all transform hover:scale-105">
                    Analisa Sekarang
                </button>
            </div>
        </form>
    </main>

    <footer class="text-center py-8 text-gray-400 text-sm">
        &copy; 2025 DentalSmart - Semantic Web Project (Skripsi)
    </footer>

</body>
</html>