from flask import Flask, request, jsonify
from owlready2 import *
import sys
import uuid
import re

# ================= CONFIGURATION =================
app = Flask(__name__)

ONTOLOGY_FILE = "project_sw.owl"
BASE_IRI = "http://www.semanticweb.org/iban/ontologies/2025/10/spesialis-gigi-recommender/"

# ================= 2. LOAD ONTOLOGY =================
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

# ================= 3. HELPER FUNCTIONS =================

def format_label(name):
    """Mengubah CamelCase menjadi Spasi"""
    s1 = re.sub('(.)([A-Z][a-z]+)', r'\1 \2', name)
    return re.sub('([a-z0-9])([A-Z])', r'\1 \2', s1)

def get_annotation_value(entity, prop_name):
    """
    Helper KUAT untuk mengambil annotation (comment/seeAlso).
    Menangani: List, Locstr (string berbahasa), dan None.
    """
    if not entity:
        return ""

    # Coba akses properti dinamis (misal entity.comment atau entity.seeAlso)
    vals = getattr(entity, prop_name, [])
    
    # Jika kosong, coba akses properti RDF standar (rdfs_comment / rdfs_seeAlso)
    # Owlready kadang menggunakan naming convention berbeda tergantung versi
    if not vals:
        if prop_name == "comment":
            vals = getattr(entity, "rdfs_comment", []) or getattr(entity, "comment", [])
        elif prop_name == "seeAlso":
            vals = getattr(entity, "rdfs_seeAlso", []) or getattr(entity, "seeAlso", [])

    if vals:
        # Ambil item pertama
        val = vals[0]
        # Jika tipe datanya Locstr (string dengan bahasa, misal: "Halo"@id), konversi ke string
        if hasattr(val, "msg"): 
            return str(val)
        return str(val)
    
    return ""

# ================= 4. CORE LOGIC (DIAGNOSA) =================
def run_diagnosis(nama_pasien, gejala_list, kondisi_list, details=None):
    result = {
        "penyakit": [], 
        "spesialis": [],
        "pesan_error": None
    }
    
    with onto:
        unique_id = uuid.uuid4().hex[:8] 
        pasien_iri = f"Pasien_{nama_pasien.replace(' ', '_')}_{unique_id}"
        
        pasien = base.Pasien(pasien_iri)
        
        # --- INPUT DATA PROPERTY (Lama Hari) ---
        if details and 'lama_hari' in details:
            raw_hari = details['lama_hari']
            if raw_hari is not None and str(raw_hari).strip() != "":
                try:
                    hari_val = int(raw_hari)
                    if hasattr(pasien, 'lamaHari'): 
                        pasien.lamaHari = [hari_val]
                except ValueError:
                    pass 

        # --- MAPPING GEJALA ---
        for g_str in gejala_list:
            cls_gejala = getattr(base, g_str, None)
            if cls_gejala:
                inst_gejala = cls_gejala(f"Gejala_{g_str}_{unique_id}")
                
                # Detail Gejala (Durasi & Pemicu)
                if details and g_str in details:
                    if 'durasi' in details[g_str]:
                        try:
                            d_val = details[g_str]['durasi']
                            if d_val is not None and str(d_val).strip() != "":
                                inst_gejala.durasiNyeri = [int(d_val)]
                        except: pass 
                    
                    if 'pemicu' in details[g_str]:
                        pemicu_name = details[g_str]['pemicu']
                        if pemicu_name:
                            pemicu_cls = getattr(base, pemicu_name, None)
                            if pemicu_cls:
                                inst_pemicu = pemicu_cls(f"Pemicu_{pemicu_name}_{unique_id}")
                                inst_gejala.dipicuOleh.append(inst_pemicu)
                
                pasien.mengalamiGejala.append(inst_gejala)
        
        # --- MAPPING KONDISI ---
        for k_str in kondisi_list:
            cls_kondisi = getattr(base, k_str, None)
            if cls_kondisi:
                inst_kondisi = cls_kondisi(f"Kondisi_{k_str}_{unique_id}")
                pasien.mengalamiKondisi.append(inst_kondisi)

        # --- REASONING ---
        try:
            sync_reasoner_pellet(infer_property_values=True, infer_data_property_values=True)
            
            # --- EKSTRAKSI HASIL ---
            for d in pasien.didugaMenderita:
                # 1. Coba ambil deskripsi langsung dari entity hasil diagnosa
                desc = get_annotation_value(d, "comment")
                link = get_annotation_value(d, "seeAlso")
                
                # 2. FALLBACK MEKANISME:
                # Jika 'd' tidak punya deskripsi (mungkin dia Instance),
                # Kita cari Class aslinya di Ontology yang namanya sama.
                if not desc:
                    # Cari Class di base yang namanya sama dengan d.name
                    # Contoh: d.name = "Pulpitis_Irreversible" -> Cari Class Pulpitis_Irreversible
                    original_class = getattr(base, d.name, None)
                    
                    if original_class:
                        desc = get_annotation_value(original_class, "comment")
                        # Jika link masih kosong, ambil juga dari class
                        if not link:
                            link = get_annotation_value(original_class, "seeAlso")

                penyakit_obj = {
                    "kode": d.name,
                    "nama": format_label(d.name.replace('Penyakit_', '')),
                    "deskripsi": desc,
                    "link": link
                }
                result["penyakit"].append(penyakit_obj)
            
            # Untuk spesialis
            result["spesialis"] = [format_label(s.name) for s in pasien.dirujukKe]
            
        except Exception as e:
            result["pesan_error"] = str(e)
            print(f"[ERROR REASONING] {e}")
            
        # Cleanup
        destroy_entity(pasien)
        
    return result

# --- Endpoint 1: Master Data (A-Z Sorted) ---
@app.route('/api/master-data', methods=['GET'])
def get_master_data():
    data = { "gejala": [], "kondisi": [], "pemicu": [] }
    
    with onto:
        # Gejala
        cls_gejala = getattr(base, "Gejala", None)
        if cls_gejala:
            for cls in cls_gejala.descendants():
                if isinstance(cls, ThingClass) and cls != cls_gejala and not list(cls.subclasses()):
                    data["gejala"].append({ "value": cls.name, "label": format_label(cls.name) })
        
        # Kondisi
        cls_kondisi_root = getattr(base, "KondisiMulut", None)
        if cls_kondisi_root:
            for cls in cls_kondisi_root.descendants():
                if isinstance(cls, ThingClass) and cls != cls_kondisi_root and not list(cls.subclasses()):
                    data["kondisi"].append({ "value": cls.name, "label": format_label(cls.name) })

        # Pemicu
        cls_pemicu_root = getattr(base, "Pemicu", None) or getattr(base, "Stimulus", None)
        if cls_pemicu_root:
             for cls in cls_pemicu_root.descendants():
                if isinstance(cls, ThingClass) and cls != cls_pemicu_root and not list(cls.subclasses()):
                    data["pemicu"].append({ "value": cls.name, "label": format_label(cls.name) })
    
    # Sorting A-Z
    data["gejala"].sort(key=lambda x: x['label'])
    data["kondisi"].sort(key=lambda x: x['label'])
    data["pemicu"].sort(key=lambda x: x['label'])

    return jsonify({"status": "success", "data": data})

# --- Endpoint 2: Diagnosa ---
@app.route('/api/diagnosa', methods=['POST'])
def api_diagnosa():
    data = request.json
    output = run_diagnosis(
        data.get('nama', 'Anonim'), 
        data.get('gejala', []), 
        data.get('kondisi', []), 
        data.get('details', {})
    )
    return jsonify({"status": "success", "data": output})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)