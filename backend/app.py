from flask import Flask, request, jsonify
from owlready2 import *
import sys
import uuid  # <--- 1. WAJIB TAMBAHKAN INI

# ================= CONFIGURATION =================
app = Flask(__name__)
ONTOLOGY_FILE = "project_sw.owl"
BASE_IRI = "http://www.semanticweb.org/iban/ontologies/2025/10/spesialis-gigi-recommender/"

# ================= 1. LOAD ONTOLOGY =================
print(f"[INIT] Memuat ontologi {ONTOLOGY_FILE}...")
try:
    onto = get_ontology(ONTOLOGY_FILE).load()
    base = onto.get_namespace(BASE_IRI)
    
    with onto:
        sync_reasoner_pellet(infer_property_values=True)
    
    print("[SUCCESS] Ontologi & Reasoner Siap!")
except Exception as e:
    print(f"[FATAL] Gagal memuat ontologi: {e}")
    sys.exit(1)

# ================= 2. CORE LOGIC =================
def run_diagnosis(nama_pasien, gejala_list, kondisi_list, details=None):
    result = {
        "penyakit": [],
        "spesialis": [],
        "pesan_error": None
    }
    
    with onto:
        # --- PERBAIKAN BUG ---
        # Generate ID Acak agar ontologi menganggap ini pasien yang benar-benar baru
        # Walaupun input namanya sama ("Budi"), di ontologi akan jadi "Pasien_Budi_a1b2c3"
        unique_id = uuid.uuid4().hex[:8] 
        pasien_iri = f"Pasien_{nama_pasien.replace(' ', '_')}_{unique_id}"
        
        pasien = base.Pasien(pasien_iri)
        # ---------------------
        
        # B. Masukkan Gejala
        for g_str in gejala_list:
            cls_gejala = getattr(base, g_str, None)
            if cls_gejala:
                # Tambahkan unique ID juga ke gejala biar tidak bentrok
                inst_gejala = cls_gejala(f"Gejala_{g_str}_{unique_id}")
                
                if details and g_str in details:
                    if 'durasi' in details[g_str]:
                        try:
                            durasi_val = int(details[g_str]['durasi'])
                            inst_gejala.durasiNyeri = [durasi_val]
                        except:
                            pass 
                    
                    if 'pemicu' in details[g_str]:
                        pemicu_name = details[g_str]['pemicu']
                        pemicu_cls = getattr(base, pemicu_name, None)
                        if pemicu_cls:
                            # Tambahkan unique ID ke pemicu
                            inst_pemicu = pemicu_cls(f"Pemicu_{pemicu_name}_{unique_id}")
                            inst_gejala.dipicuOleh.append(inst_pemicu)
                
                pasien.mengalamiGejala.append(inst_gejala)
        
        # C. Masukkan Kondisi
        for k_str in kondisi_list:
            cls_kondisi = getattr(base, k_str, None)
            if cls_kondisi:
                # Tambahkan unique ID ke kondisi
                inst_kondisi = cls_kondisi(f"Kondisi_{k_str}_{unique_id}")
                pasien.mengalamiKondisi.append(inst_kondisi)

        # D. Jalankan Reasoner
        try:
            # infer_property_values=True penting
            sync_reasoner_pellet(infer_property_values=True, infer_data_property_values=True)
            
            # Ambil Hasil
            result["penyakit"] = [d.name for d in pasien.didugaMenderita]
            result["spesialis"] = [s.name for s in pasien.dirujukKe]
            
        except Exception as e:
            result["pesan_error"] = str(e)
            print(f"[ERROR REASONING] {e}")
            
        # E. Cleanup (PENTING: Hancurkan entitas unik ini setelah selesai)
        # Ini mencegah memori membengkak karena setiap klik nambah entity baru
        destroy_entity(pasien)
        
        # Opsional: Hancurkan juga gejala/kondisi yang tadi dibuat (Advanced Cleanup)
        # Tapi destroy pasien biasanya sudah cukup memutus relasi utama
        
    return result

# ================= 3. API ROUTE =================
@app.route('/api/diagnosa', methods=['POST'])
def api_diagnosa():
    data = request.json
    nama = data.get('nama', 'Anonim')
    gejala = data.get('gejala', [])
    kondisi = data.get('kondisi', [])
    details = data.get('details', {})
    
    print(f"\n[REQUEST] Pasien: {nama}")
    
    output = run_diagnosis(nama, gejala, kondisi, details)
    
    return jsonify({"status": "success", "data": output})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)