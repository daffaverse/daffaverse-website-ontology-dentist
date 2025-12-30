<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiagnosaController
{
 public function index()
    {
        // Default data kosong (jaga-jaga kalau python mati)
        $masterData = [
            'gejala' => [],
            'kondisi' => []
        ];

        try {
            // Tembak API Python untuk minta list checkbox
            $response = Http::get('http://127.0.0.1:5000/api/master-data');
            
            if ($response->successful()) {
                $masterData = $response->json()['data'];
            }
        } catch (\Exception $e) {
            // Kalau python mati, biarkan kosong atau handle error
            // Log::error($e->getMessage());
        }

        // Kirim data ke View Welcome
        return view('welcome', compact('masterData'));
    }

    public function submit(Request $request)
    {
        // 1. Ambil Data Mentah dari Form
        $nama = $request->input('nama');
        $gejalaSelected = $request->input('gejala', []); // Array
        $kondisiSelected = $request->input('kondisi', []); // Array
        $durasi = $request->input('durasi');
        $pemicu = $request->input('pemicu');
        $lamaKeluhan = (int) $request->input('lama_keluhan');

        // dd($nama, $gejalaSelected, $kondisiSelected, $durasi, $pemicu, $lamaHari);
        // dd($request->all());

        // 2. Susun Struktur 'details' untuk API Python
        // PERBAIKAN: Ambil dulu input 'details' dari form (isi lama_hari ada disini)
        $details = $request->input('details', []); 

        // Jika user mencentang 'Ngilu' atau 'Nyeri', kita tambahkan data durasi & pemicu ke $details
        foreach ($gejalaSelected as $g) {
            if (str_contains($g, 'Ngilu') || str_contains($g, 'Nyeri')) {
                // Pastikan array key ada
                if (!isset($details[$g])) {
                    $details[$g] = [];
                }
                
                if ($durasi) {
                    $details[$g]['durasi'] = (int)$durasi;
                }
                if ($pemicu) {
                    $details[$g]['pemicu'] = $pemicu;
                }
            }
        }

        // 3. Kirim Request ke Python API
        try {
            $response = Http::post('http://127.0.0.1:5000/api/diagnosa', [
                'nama' => $nama,
                'gejala' => $gejalaSelected,
                'kondisi' => $kondisiSelected,
                'details' => $details,
                'lama_keluhan' => $lamaKeluhan,
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