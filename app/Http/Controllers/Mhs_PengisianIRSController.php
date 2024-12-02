<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan DB di-import
use App\Models\IRS; // Model untuk tabel IRS
use App\Models\JadwalKuliah; // Model untuk tabel jadwal kuliah
use App\Models\PeriodeAkademik;  // Add this line to import the PeriodeAkademik model
use App\Models\Mahasiswa;  // Add this line to import the PeriodeAkademik model
use APP\Models\Matakuliah;
use Illuminate\Support\Facades\Log;



class Mhs_PengisianIRSController extends Controller
{
    public function indexPilihJadwal()
    {
        // Mengambil data periode akademik yang sedang berlangsung
        $Periode_sekarang = DB::table('periode_akademik')
            ->orderByDesc('id_periode')
            ->select('jenis')
            ->first();

        // Cek apakah periode akademik ditemukan
        if (!$Periode_sekarang) {
            return redirect()->back()->with('error', 'Periode akademik tidak ditemukan.');
        }

        // Fetch jadwal kuliah berdasarkan periode
        $jadwalKuliah = DB::table('jadwal_kuliah')
            ->join('matakuliah', 'matakuliah.kode_matkul', '=', 'jadwal_kuliah.kode_matkul')
            ->join('ruangan', 'ruangan.id_ruang', '=', 'jadwal_kuliah.id_ruang')
            ->join('periode_akademik', 'periode_akademik.id_periode', '=', 'jadwal_kuliah.id_periode')
            ->when($Periode_sekarang->jenis == 'ganjil', function($query) {
                return $query->whereRaw('matakuliah.semester % 2 != 0');
            })
            ->when($Periode_sekarang->jenis == 'genap', function($query) {
                return $query->whereRaw('matakuliah.semester % 2 = 0');
            })
            ->orderBy('matakuliah.semester')
            ->orderBy('matakuliah.nama_matkul')
            ->select(
                'jadwal_kuliah.id_jadwal',
                'matakuliah.kode_matkul as kode_matkul',
                'matakuliah.nama_matkul',
                'jadwal_kuliah.kelas',
                'matakuliah.semester',
                'matakuliah.sks',
                'ruangan.nama as namaruang',
                'jadwal_kuliah.hari',
                'jadwal_kuliah.jam_mulai',
                'jadwal_kuliah.jam_selesai',
                'jadwal_kuliah.kuota',
                DB::raw('(SELECT COUNT(*) FROM irs WHERE irs.id_jadwal = jadwal_kuliah.id_jadwal) as kuota_terisi')
            )
            ->get();

        // Mengambil data mahasiswa yang sedang login
        $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.id_user', '=', 'users.id')
            ->join('program_studi', 'mahasiswa.id_prodi', '=', 'program_studi.id_prodi')
            ->join('dosen', 'mahasiswa.id_dosen', '=', 'dosen.id_dosen')
            ->crossJoin('periode_akademik')
            ->where('mahasiswa.id_user', auth()->id())
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama as nama_mhs',
                'program_studi.nama as prodi_nama',
                'dosen.nama as nama_doswal',
                'dosen.nip',
                'users.username',
                'periode_akademik.nama_periode'
            )
            ->first();

        // Cek apakah data mahasiswa ditemukan
        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Menambahkan pengecekan status untuk setiap jadwal kuliah
        $jadwalStatus = [];
        foreach ($jadwalKuliah as $jadwal) {
            $jadwalStatus[$jadwal->id_jadwal] = $this->cekStatusPengambilan($jadwal->id_jadwal);
        }
        $jadwalStatus = collect($jadwalStatus); // Pastikan ini adalah koleksi

        return view('mhs_pengisianIRS', compact('Periode_sekarang', 'jadwalKuliah', 'mahasiswa', 'jadwalStatus'));
    }


    // public function ambilJadwal(Request $request)
    // {
    //     $periodeAkademik = PeriodeAkademik::latest('id_periode')->first();
    
    //     if (!$periodeAkademik) {
    //         return redirect()->back()->with('error', 'Periode akademik tidak ditemukan.');
    //     }
    
    //     $mahasiswa = Mahasiswa::where('id_user', auth()->id())->first();
    //     if (!$mahasiswa) {
    //         return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
    //     }
    
    //     $request->validate([
    //         'id_jadwal' => 'required|exists:jadwal_kuliah,id_jadwal',
    //         'status' => 'required|string|max:255',
    //     ]);
    
    //     IRS::create([
    //         'nim' => $mahasiswa->nim,
    //         'semester' => $mahasiswa->semester,
    //         'id_jadwal' => $request->id_jadwal,
    //         'status' => $request->status,
    //     ]);
    
    //     return redirect()->route('mhs_pengisianIRS')->with('success', 'Jadwal berhasil diambil.');
    // }

    public function cekStatusPengambilan($id_jadwal)
    {
        $mahasiswa = Mahasiswa::where('id_user', auth()->id())->first();
        if ($mahasiswa) {
            return IRS::where('nim', $mahasiswa->nim)
                      ->where('id_jadwal', $id_jadwal)
                      ->exists();
        }
        return false;
    }


    public function ambilJadwal(Request $request)
    {
        try {
            $periodeAkademik = PeriodeAkademik::latest('id_periode')->first();
            if (!$periodeAkademik) {
                return response()->json(['success' => false, 'message' => 'Periode akademik tidak ditemukan.'], 404);
            }

            $mahasiswa = Mahasiswa::where('id_user', auth()->id())->first();
            if (!$mahasiswa) {
                return response()->json(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan.'], 404);
            }

            $request->validate([
                'id_jadwal' => 'required|exists:jadwal_kuliah,id_jadwal',
                'status' => 'required|string|max:255',
            ]);

            IRS::create([
                'nim' => $mahasiswa->nim,
                'semester' => $mahasiswa->semester,
                'id_jadwal' => $request->id_jadwal,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diambil',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }


private function hitungMaxSks($ipsLalu)
{
    if ($ipsLalu >= 0 && $ipsLalu <= 1) {
        return 15;  // Maksimal 15 SKS
    } elseif ($ipsLalu > 1 && $ipsLalu <= 2.5) {
        return 18;  // Maksimal 18 SKS
    } elseif ($ipsLalu > 2.5 && $ipsLalu <= 3.5) {
        return 21;  // Maksimal 21 SKS
    } elseif ($ipsLalu > 3.5 && $ipsLalu <= 4) {
        return 24;  // Maksimal 24 SKS
    } else {
        return 0;  // Tidak valid
    }
}

public function batalkanJadwal(Request $request)
{
    $mahasiswa = Mahasiswa::where('id_user', auth()->id())->first();
    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
    }
    // Validasi input
    $request->validate([
        'id_irs' => 'required|integer|exists:irs,id_irs',
    ]);

    // Hapus record berdasarkan id_irs
    IRS::where('id_irs', $request->id_irs)
        ->where('nim', $mahasiswa->nim)
        ->where('id_jadwal', $request->id_jadwal)           
        ->delete();

    // Redirect atau respon sukses
    return redirect()->back()->with('success', 'Jadwal berhasil dibatalkan.');
}





        public function periodeHabis()
        {
                // Fetch mahasiswa data
            $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.id_user', '=', 'users.id')
            ->join('program_studi', 'mahasiswa.id_prodi', '=', 'program_studi.id_prodi')
            ->join('dosen', 'mahasiswa.id_dosen', '=', 'dosen.id_dosen')
            ->where('mahasiswa.id_user', auth()->id())
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama as nama_mhs',
                'program_studi.nama as prodi_nama',
                'dosen.nama as nama_doswal',
                'dosen.nip',
                'users.username'
            ) 
            ->first();

            // Pass both daftarMk and mahasiswa data to the view
            return view('mhs_habisPeriodeIRS', compact('mahasiswa'));
        }

        public function draftIRS()
        {
            // Fetch mahasiswa data
            $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.id_user', '=', 'users.id')
            ->join('program_studi', 'mahasiswa.id_prodi', '=', 'program_studi.id_prodi')
            ->join('dosen', 'mahasiswa.id_dosen', '=', 'dosen.id_dosen')
            ->where('mahasiswa.id_user', auth()->id())
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama as nama_mhs',
                'program_studi.nama as prodi_nama',
                'dosen.nama as nama_doswal',
                'dosen.nip',
                'users.username'
            ) 
            ->first();

            //Method get untuk fetch data dr tabel IRS
            $rancanganIRSSementara = DB::table('irs')
            ->join('jadwal_kuliah', 'jadwal_kuliah.id_jadwal', '=', 'irs.id_jadwal')
            ->join('matakuliah', 'jadwal_kuliah.kode_matkul','=','matakuliah.kode_matkul')
            ->join('mahasiswa', 'mahasiswa.nim', '=', 'irs.nim')
            ->join('ruangan','ruangan.id_ruang','=','jadwal_kuliah.id_ruang')
            ->where('mahasiswa.id_user', auth()->id())
            ->select(
                'irs.id_irs as id_irs',
                'jadwal_kuliah.id_jadwal as id_jadwal',
                'matakuliah.kode_matkul',
                'matakuliah.nama_matkul',
                'jadwal_kuliah.semester',
                'jadwal_kuliah.kelas',
                'matakuliah.sks',
                'ruangan.nama as nama_ruang',
                'jadwal_kuliah.hari',
                'jadwal_kuliah.jam_mulai',
                'jadwal_kuliah.jam_selesai',
                'jadwal_kuliah.kuota',
            )
            ->get();

            // Pass both daftarMk and mahasiswa data to the view
            return view('mhs_draftIRS', compact('mahasiswa','rancanganIRSSementara'));
        }

        public function newIRS()
        {
                // Fetch mahasiswa data
            $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.id_user', '=', 'users.id')
            ->join('program_studi', 'mahasiswa.id_prodi', '=', 'program_studi.id_prodi')
            ->join('dosen', 'mahasiswa.id_dosen', '=', 'dosen.id_dosen')
            ->where('mahasiswa.id_user', auth()->id())
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama as nama_mhs',
                'program_studi.nama as prodi_nama',
                'dosen.nama as nama_doswal',
                'dosen.nip',
                'users.username'
            ) 
            ->first();

            // Pass both daftarMk and mahasiswa data to the view
            return view('mhs_newIRS', compact('mahasiswa'));
        }

        public function rrencanaStudi()
        {
            // Fetch mahasiswa data
            $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'mahasiswa.id_user', '=', 'users.id')
            ->join('program_studi', 'mahasiswa.id_prodi', '=', 'program_studi.id_prodi')
            ->join('dosen', 'mahasiswa.id_dosen', '=', 'dosen.id_dosen')
            ->crossJoin('periode_akademik')
            ->where('mahasiswa.id_user', auth()->id())
            ->select(
                'mahasiswa.nim',
                'mahasiswa.nama as nama_mhs',
                'program_studi.nama as prodi_nama',
                'dosen.nama as nama_doswal',
                'dosen.nip',
                'users.username',
                'periode_akademik.nama_periode'
            )
            ->first();

            // Pass both daftarMk and mahasiswa data to the view
            return view('mhs_rrencanaStudi', compact('mahasiswa'));
        }
        

}