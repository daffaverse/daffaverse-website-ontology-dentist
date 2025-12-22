from flask import Flask, request, jsonify
from owlready2 import *
import sys
import uuid
import re  # <--- Wajib ada untuk merapikan teks

# ================= CONFIGURATION =================
# 1. Inisialisasi 'app' harus di ATAS sebelum dipakai di @app.route
app = Flask(__name__)

ONTOLOGY_FILE = "project_sw.owl"
BASE_IRI = "http://www.semanticweb.org/iban/ontologies/2025/10/spesialis-gigi-recommender/"

# ================= 2. LOAD ONTOLOGY =================
print(f"[INIT] Memuat ontologi {ONTOLOGY_FILE}...")
try:
    onto = get_ontology(ONTOLOGY_FILE).load()
    base = onto.get_namespace(BASE_IRI)
    
    # Pre-load Pellet Reasoner
    with onto:
        sync_reasoner_pellet(infer_property_values=True)
    
    print("[SUCCESS] Ontologi & Reasoner Siap!")
except Exception as e:
    print(f"[FATAL] Gagal memuat ontologi: {e}")
    sys.exit(1)

# ================= 3. HELPER FUNCTIONS =================

def format_label(name):
    """Mengubah CamelCase menjadi Spasi (Misal: GusiBerdarah -> Gusi Berdarah)"""
    s1 = re.sub('(.)([A-Z][a-z]+)', r'\1 \2', name)
    return re.sub('([a-z0-9])([A-Z])', r'\1 \2', s1)

# ================= 4. CORE LOGIC (DIAGNOSA) =================
def run_diagnosis(nama_pasien, gejala_list, kondisi_list, details=None):
    result = {
        "penyakit": [],
        "spesialis": [],
        "pesan_error": None
    }
    
    with onto:
        # Generate ID Acak
        unique_id = uuid.uuid4().hex[:8] 
        pasien_iri = f"Pasien_{nama_pasien.replace(' ', '_')}_{unique_id}"
        
        pasien = base.Pasien(pasien_iri)
        
        # Mapping Gejala
        for g_str in gejala_list:
            cls_gejala = getattr(base, g_str, None)
            if cls_gejala:
                inst_gejala = cls_gejala(f"Gejala_{g_str}_{unique_id}")
                
                # Cek Detail (Durasi/Pemicu)
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
                            inst_pemicu = pemicu_cls(f"Pemicu_{pemicu_name}_{unique_id}")
                            inst_gejala.dipicuOleh.append(inst_pemicu)
                
                pasien.mengalamiGejala.append(inst_gejala)
        
        # Mapping Kondisi
        for k_str in kondisi_list:
            cls_kondisi = getattr(base, k_str, None)
            if cls_kondisi:
                inst_kondisi = cls_kondisi(f"Kondisi_{k_str}_{unique_id}")
                pasien.mengalamiKondisi.append(inst_kondisi)

        # Jalankan Reasoner
        try:
            sync_reasoner_pellet(infer_property_values=True, infer_data_property_values=True)
            
            result["penyakit"] = [d.name for d in pasien.didugaMenderita]
            result["spesialis"] = [s.name for s in pasien.dirujukKe]
            
        except Exception as e:
            result["pesan_error"] = str(e)
            print(f"[ERROR REASONING] {e}")
            
        # Cleanup
        destroy_entity(pasien)
        
    return result

# ================= 5. API ROUTES =================

# --- Endpoint 1: Ambil List Checkbox Dinamis ---
@app.route('/api/master-data', methods=['GET'])
def get_master_data():
    data = {
        "gejala": [],
        "kondisi": []
    }
    
    with onto:
        # A. Ambil Data Gejala
        cls_gejala = getattr(base, "Gejala", None)
        if cls_gejala:
            for cls in cls_gejala.subclasses():
                if isinstance(cls, ThingClass): 
                    data["gejala"].append({
                        "value": cls.name,              
                        "label": format_label(cls.name) 
                    })
        
        # B. Ambil Data Kondisi (KondisiMulut dan turunannya)
        cls_kondisi_root = getattr(base, "KondisiMulut", None)
        if cls_kondisi_root:
            for cls in cls_kondisi_root.descendants():
                # Filter: Hanya ambil class yang tidak punya anak (leaf node)
                # agar class abstrak seperti 'KondisiGigi' tidak muncul
                if isinstance(cls, ThingClass) and cls != cls_kondisi_root:
                    if not list(cls.subclasses()):
                        data["kondisi"].append({
                            "value": cls.name,
                            "label": format_label(cls.name)
                        })

    return jsonify({"status": "success", "data": data})

# --- Endpoint 2: Proses Diagnosa ---
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
    # Jalankan server
    app.run(host='0.0.0.0', port=5000, debug=True)