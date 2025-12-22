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
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">1</span>
                    <h2 class="text-lg font-semibold text-gray-900">Identitas Pasien</h2>
                </div>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" required placeholder="Contoh: Budi Santoso" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-colors">
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">2</span>
                    <h2 class="text-lg font-semibold text-gray-900">Gejala yang dirasakan</h2>
                    <span class="text-xs text-gray-400 ml-auto">(Boleh pilih lebih dari satu)</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="Ngilu" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Gigi terasa ngilu</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="NyeriMalamHari" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Nyeri tajam saat malam hari</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="NyeriSaatMenggigit" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Nyeri tajam saat menggigit</span>
                        </div>
                    </label>
                    
                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="NyeriSpontan" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Nyeri Spontan (Tiba-tiba)</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="Trismus" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Susah membuka mulut (Kaku)</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="GusiBerdarah" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Gusi mudah berdarah</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="BengkakWajah" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Wajah terlihat bengkak</span>
                        </div>
                    </label>
                    
                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gejala[]" value="ResponGigiNegatif" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Gigi Mati Rasa (Tes Vitalitas Negatif)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">3</span>
                    <h2 class="text-lg font-semibold text-gray-900">Pemeriksaan & Pemicu</h2>
                </div>
                
                <div class="mb-8 p-5 bg-blue-50 rounded-xl border border-blue-100">
                    <label for="durasi" class="block text-sm font-semibold text-blue-900 mb-2">
                        Durasi Nyeri (Wajib diisi)
                    </label>
                    <p class="text-xs text-blue-600 mb-2">Jika Anda merasa nyeri/ngilu setelah terkena pemicu (misal minum es), berapa detik rasa sakit itu bertahan?</p>
                    <input type="number" name="durasi" id="durasi" placeholder="Contoh: 5 (untuk 5 detik) atau 45 (untuk 45 detik)" 
                        class="w-full px-4 py-2 rounded-lg border border-white-200 focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-500 mt-2 italic">*kosongkan jika tidak ada nyeri yang dipicu.</p>
                </div>

                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Kondisi Fisik (Apa yang terlihat?)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="kondisi[]" value="BauMulut" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Bau mulut menyengat</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="kondisi[]" value="GigiGoyang" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Gigi terasa goyang</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="kondisi[]" value="GigiErupsiSebagian" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Gigi tumbuh miring / sebagian</span>
                        </div>
                    </label>

                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="kondisi[]" value="GusiBenjol" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Ada benjolan pada gusi</span>
                        </div>
                    </label>
                    
                    <label class="relative flex items-start p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="kondisi[]" value="OperkulumBengkak" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">Gusi bengkak di atas gigi geraham (Operkulum)</span>
                        </div>
                    </label>
                </div>

                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Pemicu Utama (Kapan sakit muncul?)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="relative flex items-center p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <input type="radio" name="pemicu" value="TekananSaatMenggigit" class="w-4 h-4 text-medical-600 border-gray-300 focus:ring-medical-500">
                        <span class="ml-3 text-sm font-medium text-gray-900">Tekanan / Saat Mengunyah</span>
                    </label>

                    <label class="relative flex items-center p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <input type="radio" name="pemicu" value="StimulusDingin" class="w-4 h-4 text-medical-600 border-gray-300 focus:ring-medical-500">
                        <span class="ml-3 text-sm font-medium text-gray-900">Minuman Dingin</span>
                    </label>

                    <label class="relative flex items-center p-4 rounded-xl cursor-pointer border border-gray-100 hover:border-medical-200 hover:bg-gray-50 transition-all duration-200">
                        <input type="radio" name="pemicu" value="StimulusPanas" class="w-4 h-4 text-medical-600 border-gray-300 focus:ring-medical-500">
                        <span class="ml-3 text-sm font-medium text-gray-900">Minuman Panas</span>
                    </label>
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