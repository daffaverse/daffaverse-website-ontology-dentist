<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiagnosaController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function submit(Request $request)
    {
        // 1. Ambil Data Mentah dari Form
        $nama = $request->input('nama');
        $gejalaSelected = $request->input('gejala', []); // Array
        $kondisiSelected = $request->input('kondisi', []); // Array
        $durasi = $request->input('durasi');
        $pemicu = $request->input('pemicu');

        // 2. Susun Struktur 'details' untuk API Python
        // API Python butuh struktur: details: { "Gejala_X": { "durasi": 10, "pemicu": "..." } }
        $details = [];

        // Jika user mencentang 'Ngilu' atau 'Nyeri', kita masukkan data durasi & pemicu
        // Cek apakah ada gejala yang berhubungan dengan nyeri di input
        foreach ($gejalaSelected as $g) {
            if (str_contains($g, 'Ngilu') || str_contains($g, 'Nyeri')) {
                $details[$g] = [];
                
                if ($durasi) {
                    $details[$g]['durasi'] = (int)$durasi;
                }
                if ($pemicu) {
                    $details[$g]['pemicu'] = $pemicu;
                }
            }
        }

        // 3. Kirim Request ke Python API
        // Pastikan app.py sudah jalan (python app.py)
        try {
            $response = Http::post('http://127.0.0.1:5000/api/diagnosa', [
                'nama' => $nama,
                'gejala' => $gejalaSelected,
                'kondisi' => $kondisiSelected,
                'details' => $details
            ]);

            if ($response->successful()) {
                $hasil = $response->json()['data'];
                return view('hasil', compact('hasil'));
            } else {
                return back()->with('error', 'Gagal terhubung ke AI System.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Server AI mati. Pastikan python app.py sudah dijalankan.');
        }
    }
}