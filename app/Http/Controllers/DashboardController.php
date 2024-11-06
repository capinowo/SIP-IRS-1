<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    // Method untuk Dashboard Dosen
    public function index()
    {

        $dosen = DB::table('dosen')
                    ->join('users', 'dosen.id_user', '=', 'users.id')
                    ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                    ->select(
                        'dosen.nip',
                        'dosen.nama as dosen_nama',         // Alias to avoid conflict
                        'program_studi.nama as prodi_nama', // Alias for program_studi's nama
                        'dosen.prodi_id',
                        'users.username'
                    )
                    ->where ('dosen.id_user', '=', auth()->id())
                    ->first(); 
        return view('dashboardDosen', compact('dosen'));

    }


    // Method untuk Dashboard Kaprodi
    public function indexKaprodi()
    {

        $kaprodi = DB::table('dosen')
                        ->join('users', 'dosen.id_user', '=', 'users.id')
                        ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                        ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
                        ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
                        ->where('dosen.id_user', '=', auth()->id())
                        ->select(
                            'dosen.nip',
                            'dosen.nama as dosen_nama',
                            'program_studi.nama as prodi_nama',
                            'dosen.prodi_id',
                            'users.username'
                        )
                        ->first();

        return view('dashboardKaprodi', compact('kaprodi'));
    }

    


    // Method untuk Dashboard Mahasiswa
    public function indexMahasiswa()
{
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
    
    return view('dashboardMahasiswa', compact('mahasiswa'));  // Gunakan dot notation
}
    //Method untuk Dasboard Dekan
    public function indexDekan()
    {
    
        // Data dummy untuk dekan
        $dekan = DB::table('dosen')
                ->join('users', 'dosen.id_user', '=', 'users.id')
                ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                ->where('users.roles1', '=', 'dosen')
                ->where('users.roles2', '=', 'dekan')
                ->where('dosen.id_user', '=', auth()->id())
                ->select(
                    'dosen.nip',
                    'dosen.nama as dosen_nama',
                    'program_studi.nama as prodi_nama',
                    'dosen.prodi_id',
                    'users.username'
                )
                ->first();
    
        return view('dashboardDekan', compact('dekan'));
    }
    
    public function indexAkademik()
    {
        // Contoh data dummy, nantinya bisa diambil dari database
        $data = [
            'user' => [
                'name' => 'Albus Dumbledore',
                'nip' => '198203092006041002',
                'role' => 'Tenaga Kependidikan',
                'periode' => 'Periode 2024-2029'
            ],
            'semester' => [
                'current' => '2024/2025 Ganjil',
                'period' => '19 Jan - 2 Mar'
            ],
            'progress' => [
                'belum_usul' => ['count' => 1, 'total' => 6],
                'dikonfirmasi' => ['count' => 4, 'total' => 6],
                'belum_dikonfirmasi' => ['count' => 1, 'total' => 6]
            ],
            'status' => [
                'bagiruang' => 'Belum Disetujui Dekan', // or 'disetujui', 'pending'
            ]
        ];

        return view('dashboardAkademik', compact('data'));
    }
}
