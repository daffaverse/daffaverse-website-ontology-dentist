import sys
import contextlib
import io
from owlready2 import *

# ================= CONFIGURATION =================
ONTOLOGY_FILE = "project_sw.owl"  # Pastikan pakai .owl
BASE_IRI = "http://www.semanticweb.org/iban/ontologies/2025/10/spesialis-gigi-recommender/"

# ================= HELPER FUNCTIONS =================

def load_ontology():
    """Memuat file ontologi dengan error handling."""
    print(f"\n[INIT] Memuat ontologi: {ONTOLOGY_FILE} ... ", end="")
    try:
        onto = get_ontology(ONTOLOGY_FILE).load()
        base = onto.get_namespace(BASE_IRI)
        print("BERHASIL.")
        return onto, base
    except Exception as e:
        print(f"GAGAL.\nError: {e}")
        sys.exit(1)

@contextlib.contextmanager
def suppress_output():
    """
    Konteks manajer untuk menyembunyikan log sampah dari Pellet/Java
    agar terminal tetap bersih.
    """
    with contextlib.redirect_stdout(io.StringIO()), contextlib.redirect_stderr(io.StringIO()):
        yield

def print_separator(char="=", length=60):
    print(char * length)

def format_list(data_list):
    """Mengubah list object menjadi string rapi dipisah koma."""
    if not data_list:
        return "-"
    # Mengambil nama entity saja (menghilangkan namespace)
    return ", ".join([str(item.name) for item in data_list])

# ================= CORE LOGIC =================

def run_test_case(onto, base, case_name, input_data, expected_disease_name):
    """
    Menjalankan satu skenario tes dengan tampilan rapi.
    """
    print_separator("=")
    print(f"TEST CASE: {case_name}")
    print_separator("-")
    
    # Tampilkan Input agar jelas apa yang dites
    gejala_input = ", ".join(input_data.get('gejala', [])) or "-"
    kondisi_input = ", ".join(input_data.get('kondisi', [])) or "-"
    print(f"Input Gejala  : {gejala_input}")
    print(f"Input Kondisi : {kondisi_input}")
    if 'details' in input_data:
        print(f"Detail Data   : {input_data['details']}")

    with onto:
        # 1. Buat Pasien Sementara
        pasien_name = f"Pasien_{case_name.replace(' ', '_')}"
        pasien = base.Pasien(pasien_name)
        
        # 2. Masukkan Gejala
        for gejala_str in input_data.get('gejala', []):
            cls_gejala = getattr(base, gejala_str, None)
            if cls_gejala:
                inst_gejala = cls_gejala(f"Gejala_{gejala_str}_{pasien_name}")
                
                # Handle Data Properties (Durasi/Pemicu)
                details = input_data.get('details', {})
                if gejala_str in details:
                    if 'durasi' in details[gejala_str]:
                        inst_gejala.durasiNyeri = [details[gejala_str]['durasi']]
                    if 'pemicu' in details[gejala_str]:
                        pemicu_name = details[gejala_str]['pemicu']
                        pemicu_cls = getattr(base, pemicu_name, None)
                        if pemicu_cls:
                            inst_pemicu = pemicu_cls(f"Pemicu_{pemicu_name}_{pasien_name}")
                            inst_gejala.dipicuOleh.append(inst_pemicu)

                pasien.mengalamiGejala.append(inst_gejala)

        # 3. Masukkan Kondisi
        for kondisi_str in input_data.get('kondisi', []):
            cls_kondisi = getattr(base, kondisi_str, None)
            if cls_kondisi:
                inst_kondisi = cls_kondisi(f"Kondisi_{kondisi_str}_{pasien_name}")
                pasien.mengalamiKondisi.append(inst_kondisi)

        # 4. Jalankan Reasoner (Heningkan outputnya)
        # print("... sedang berpikir (reasoning) ...") 
        try:
            with suppress_output():
                sync_reasoner_pellet(infer_property_values=True, infer_data_property_values=True)
        except Exception as e:
            print(f"\n[ERROR] Reasoner Crash: {e}")
            return

        # 5. Ambil Hasil
        found_diseases = [d.name for d in pasien.didugaMenderita]
        referrals = [s.name for s in pasien.dirujukKe]
        
        # 6. Tampilkan Output
        print_separator("-")
        print(f"Hasil Diagnosa: {format_list(pasien.didugaMenderita)}")
        print(f"Rujukan Dokter: {format_list(pasien.dirujukKe)}")
        
        # 7. Validasi
        is_success = expected_disease_name in found_diseases
        print_separator("-")
        if is_success:
            print("STATUS: [ ✅ PASS ] - Sesuai Harapan")
        else:
            print(f"STATUS: [ ❌ FAIL ] - Harapan: {expected_disease_name}")
        
        # Cleanup
        destroy_entity(pasien)

# ================= MAIN EXECUTION =================

def main():
    onto, base = load_ontology()

    # 1. TEST PERIODONTITIS
    run_test_case(onto, base, "Cek Periodontitis", 
        input_data={"gejala": ["GusiBerdarah"], "kondisi": ["GigiGoyang"]},
        expected_disease_name="Penyakit_Periodontitis"
    )

    # 2. TEST IMPAKSI MOLAR
    run_test_case(onto, base, "Cek Impaksi Molar", 
        input_data={"gejala": [], "kondisi": ["OperkulumBengkak", "GigiErupsiSebagian"]},
        expected_disease_name="Penyakit_Impaksi_Molar"
    )

    # 3. TEST PULPITIS IRREVERSIBLE (Sakit Lama)
    run_test_case(onto, base, "Cek Pulpitis Irreversible", 
        input_data={
            "gejala": ["Ngilu"],
            "kondisi": [],
            "details": {"Ngilu": {"durasi": 45, "pemicu": "StimulusDingin"}}
        },
        expected_disease_name="Penyakit_Pulpitis_Irreversible"
    )

    # 4. TEST PULPITIS REVERSIBLE (Sakit Bentar)
    run_test_case(onto, base, "Cek Pulpitis Reversible", 
        input_data={
            "gejala": ["Ngilu"],
            "kondisi": [],
            "details": {"Ngilu": {"durasi": 5, "pemicu": "StimulusDingin"}}
        },
        expected_disease_name="Penyakit_Pulpitis_Reversible"
    )
    
    # 5. TEST ABSES
    run_test_case(onto, base, "Cek Abses Periapikal Akut", 
        input_data={"gejala": ["ResponGigiNegatif", "NyeriPerkusi", "BengkakWajah"], "kondisi": []},
        expected_disease_name="Penyakit_Abses_Periapikal_Akut"
    )

    print("\n")

if __name__ == "__main__":
    main()