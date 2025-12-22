<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Gigi Pintar</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
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
<body class="bg-gray-50 text-gray-800 antialiased" x-data="dentalApp()">

    <!-- Navbar -->
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

    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-4 py-8 sm:px-6">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cek Kesehatan Gigi Anda</h1>
            <p class="text-gray-500">Jawab pertanyaan berikut untuk mendapatkan rekomendasi spesialis yang tepat.</p>
        </div>

        <!-- FORM UTAMA -->
        <form @submit.prevent="submitDiagnosis" class="space-y-8">
            
            <!-- STEP 1: IDENTITAS -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">1</span>
                    <h2 class="text-lg font-semibold text-gray-900">Identitas Pasien</h2>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="formData.nama" required placeholder="Contoh: Budi Santoso" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-colors">
                </div>
            </div>

            <!-- STEP 2: GEJALA -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">2</span>
                    <h2 class="text-lg font-semibold text-gray-900">Apa yang Anda rasakan?</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <template x-for="item in gejalaOptions" :key="item.value">
                        <label class="relative flex items-start p-4 rounded-xl cursor-pointer border-2 transition-all duration-200"
                            :class="formData.gejala.includes(item.value) ? 'border-medical-500 bg-medical-50' : 'border-gray-100 hover:border-medical-200 hover:bg-gray-50'">
                            <div class="flex items-center h-5">
                                <input type="checkbox" :value="item.value" x-model="formData.gejala" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-900" x-text="item.label"></span>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            <!-- STEP 3: KONDISI & PEMICU -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-medical-100 text-medical-700 font-bold text-sm">3</span>
                    <h2 class="text-lg font-semibold text-gray-900">Pemeriksaan & Pemicu</h2>
                </div>

                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Kondisi Fisik (Apa yang terlihat?)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                    <template x-for="item in kondisiOptions" :key="item.value">
                        <label class="relative flex items-start p-4 rounded-xl cursor-pointer border-2 transition-all duration-200"
                            :class="formData.kondisi.includes(item.value) ? 'border-medical-500 bg-medical-50' : 'border-gray-100 hover:border-medical-200 hover:bg-gray-50'">
                            <div class="flex items-center h-5">
                                <input type="checkbox" :value="item.value" x-model="formData.kondisi" class="w-4 h-4 text-medical-600 border-gray-300 rounded focus:ring-medical-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <span class="font-medium text-gray-900" x-text="item.label"></span>
                            </div>
                        </label>
                    </template>
                </div>

                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Pemicu (Kapan sakit muncul?)</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <template x-for="item in pemicuOptions" :key="item.value">
                        <label class="relative flex items-center p-4 rounded-xl cursor-pointer border-2 transition-all duration-200"
                            :class="formData.pemicu === item.value ? 'border-medical-500 bg-medical-50' : 'border-gray-100 hover:border-medical-200 hover:bg-gray-50'">
                            <input type="radio" name="pemicu" :value="item.value" x-model="formData.pemicu" class="w-4 h-4 text-medical-600 border-gray-300 focus:ring-medical-500">
                            <span class="ml-3 text-sm font-medium text-gray-900" x-text="item.label"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- TOMBOL SUBMIT -->
            <div class="flex justify-end pt-4">
                <button type="submit" 
                    class="inline-flex items-center px-8 py-4 border border-transparent text-base font-medium rounded-xl shadow-lg text-white bg-medical-600 hover:bg-medical-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-medical-500 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isLoading">
                    <span x-show="!isLoading">Analisa Sekarang</span>
                    <span x-show="isLoading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </main>

    <!-- MODAL HASIL DIAGNOSIS -->
    <div x-show="showResult" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <!-- Overlay -->
            <div x-show="showResult" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showResult = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showResult"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <!-- Modal Header -->
                <div class="bg-medical-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-medical-100">
                    <h3 class="text-lg leading-6 font-bold text-medical-800" id="modal-title">Hasil Analisa</h3>
                    <button @click="showResult = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            
                            <!-- Nama Penyakit -->
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 uppercase tracking-wide font-semibold">Diagnosis Awal</p>
                                <h3 class="text-2xl font-extrabold text-medical-700 mt-1" x-text="result.diagnosis"></h3>
                            </div>

                            <!-- Deskripsi -->
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6">
                                <p class="text-gray-600 text-sm leading-relaxed" x-text="result.description"></p>
                            </div>

                            <!-- Rekomendasi Spesialis -->
                            <div class="bg-white border-2 border-medical-100 rounded-xl p-4 flex items-center gap-4 shadow-sm">
                                <div class="bg-medical-100 p-3 rounded-full shrink-0">
                                    <svg class="w-8 h-8 text-medical-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Disarankan Berkonsultasi Ke</p>
                                    <p class="text-lg font-bold text-gray-900" x-text="result.specialist"></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <a :href="result.reference" target="_blank" 
                       class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-medical-600 text-base font-medium text-white hover:bg-medical-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Baca Info Medis
                    </a>
                    <button type="button" @click="showResult = false" 
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIC APLIKASI -->
    <script>
        function dentalApp() {
            return {
                isLoading: false,
                showResult: false,
                
                // DATA FORM INPUT
                formData: {
                    nama: '',
                    gejala: [], // Array untuk checkbox gejala
                    kondisi: [], // Array untuk checkbox kondisi
                    pemicu: '' // String untuk radio button pemicu
                },

                // DATA UNTUK MODAL HASIL (DEFAULT KOSONG)
                result: {
                    diagnosis: '',
                    description: '',
                    specialist: '',
                    reference: '#'
                },

                // OPSI GEJALA (MAPPING KE ONTOLOGY)
                gejalaOptions: [
                    { label: 'Gigi terasa ngilu', value: 'Gejala_Ngilu' },
                    { label: 'Nyeri tajam saat malam hari', value: 'Gejala_NyeriMalamHari' },
                    { label: 'Nyeri tajam saat menggigit', value: 'Gejala_NyeriSaatGigit' },
                    { label: 'Susah membuka mulut (Kaku)', value: 'Gejala_Trismus' },
                    { label: 'Gusi mudah berdarah', value: 'Gejala_GusiBerdarah' },
                    { label: 'Wajah terlihat bengkak', value: 'BengkakWajah' }
                ],

                // OPSI KONDISI
                kondisiOptions: [
                    { label: 'Bau mulut menyengat', value: 'Kondisi_BauMulut' },
                    { label: 'Gigi terasa goyang', value: 'Kondisi_GigiGoyang' },
                    { label: 'Gigi tumbuh miring / sebagian', value: 'Kondisi_Gigi_Erupsi_Sebagian' },
                    { label: 'Ada benjolan pada gusi', value: 'GusiBenjol' }
                ],

                // OPSI PEMICU
                pemicuOptions: [
                    { label: 'Saat ada tekanan / mengunyah', value: 'Tekanan' },
                    { label: 'Saat kena panas / dingin', value: 'StimulusSuhu' },
                    { label: 'Muncul tiba-tiba (Spontan)', value: 'Spontan' }
                ],

                // FUNGSI SUBMIT (SIMULASI)
                submitDiagnosis() {
                    this.isLoading = true;

                    // Simulasi Request ke Backend (2 Detik)
                    setTimeout(() => {
                        console.log("Data dikirim ke Ontology:", this.formData);

                        // --- MOCKUP LOGIC SEDERHANA (HANYA UNTUK DEMO UI) ---
                        // Nanti ini diganti dengan response JSON dari Controller Laravel
                        
                        if (this.formData.gejala.includes('Gejala_Ngilu') && this.formData.gejala.includes('Gejala_NyeriMalamHari')) {
                            // Kasus Pulpitis
                            this.result = {
                                diagnosis: 'Penyakit Pulpitis Irreversible',
                                description: 'Peradangan pada pulpa gigi yang parah dan tidak bisa kembali normal. Biasanya ditandai sakit spontan, nyut-nyutan, dan sakit saat malam hari.',
                                specialist: 'Spesialis Konservasi Gigi (Sp.KG)',
                                reference: 'http://purl.bioontology.org/ontology/MESH/D011671'
                            };
                        } else if (this.formData.gejala.includes('Gejala_GusiBerdarah')) {
                            // Kasus Periodontitis
                            this.result = {
                                diagnosis: 'Penyakit Periodontitis',
                                description: 'Infeksi gusi serius yang merusak jaringan lunak dan dapat menghancurkan tulang penyangga gigi. Gejala meliputi gusi berdarah, bau mulut, dan gigi goyang.',
                                specialist: 'Spesialis Periodonsia (Sp.Perio)',
                                reference: 'http://purl.bioontology.org/ontology/MESH/D010518'
                            };
                        } else {
                            // Default Fallback
                            this.result = {
                                diagnosis: 'Gejala Belum Spesifik',
                                description: 'Berdasarkan data yang Anda masukkan, kami belum dapat menyimpulkan penyakit spesifik. Namun disarankan segera memeriksakan diri.',
                                specialist: 'Dokter Gigi Umum',
                                reference: '#'
                            };
                        }

                        this.isLoading = false;
                        this.showResult = true;
                    }, 1500);
                }
            }
        }
    </script>
</body>
</html>