<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\SidebarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BAK_PembagianruangController;
use App\Http\Controllers\Kaprodi_JadwalKuliahControler;
use App\Http\Controllers\KaprodiControler;
use App\Http\Controllers\IRSController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Mhs_PengisianIRSController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// {AUTH}
Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login', 'postLogin')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});

// Role Selection Routes (Both Active and Commented)
// Route::post('/roleSelection', [AuthController::class, 'roleSelection'])->name('roleSelection');
// Route::get('/not-page', [AuthController::class, 'notPage'])->name('notPage');
// Route::post('/handleRoleSelection', [AuthController::class, 'handleRoleSelection'])->name('handleRoleSelection');
// Route::post('/submit-role-selection', [AuthController::class, 'submitRoleSelection'])->name('submitRoleSelection');

Route::get('/roleSelection', [AuthController::class, 'roleSelection'])->name('roleSelection');
Route::post('/handleRoleSelection', [AuthController::class, 'handleRoleSelection'])->name('handleRoleSelection');
Route::get('/not-page', [AuthController::class, 'notPage'])->name('notPage');
Route::post('/submit-role-selection', [AuthController::class, 'submitRoleSelection'])->name('submitRoleSelection');

// Protected Routes (Both Active and Commented)
Route::group([], function () {
    // {MAHASISWA}
    Route::prefix('dashboardMahasiswa')->group(function () {
        Route::get('/', [DashboardController::class, 'indexMahasiswa'])->name('dashboardMahasiswa');
    });

    // IRS Routes for Students
    Route::prefix('pengisianIRS')->group(function () {
        Route::get('/', [Mhs_PengisianIRSController::class, 'indexPilihJadwal'])->name('mhs_pengisianIRS');
        Route::post('/ambilJadwal', [Mhs_PengisianIRSController::class, 'ambilJadwal'])->name('ambilJadwal');
    });

    Route::prefix('rencanaStudi')->group(function () {
        Route::get('/', [Mhs_PengisianIRSController::class, 'indexRencanaStudi'])->name('mhs_rencanaStudi');
    });

    Route::prefix('rrencanaStudi')->group(function () {
        Route::get('/', [Mhs_PengisianIRSController::class, 'rrencanaStudi'])->name('mhs_rrencanaStudi');
        Route::get('/cetak_pdf', [Mhs_PengisianIRSController::class, 'cetak_pdf'])->name('cetak_pdf');
    });

    Route::prefix('periodeIRSHabis')->group(function () {
        Route::get('/', [Mhs_PengisianIRSController::class, 'periodeHabis'])->name('mhs_habisPeriodeIRS');
    });

    Route::prefix('newIRS')->group(function () {
        Route::get('/', [Mhs_PengisianIRSController::class, 'newIRS'])->name('mhs_newIRS');
    });

    Route::prefix('draftIRS')->group(function () {
        Route::get('/', [Mhs_PengisianIRSController::class, 'draftIRS'])->name('mhs_draftIRS');
        Route::post('/batalkanJadwal', [Mhs_PengisianIRSController::class, 'batalkanJadwal'])->name('batalkanJadwal');
    });

    // Commented Pengambilan Matkul Route
    // Route::prefix('pengambilanMatkul')->group(function () {
    //     Route::get('/', [Mhs_PengisianIRSController::class, 'indexAmbilMatkul'])->name('pengambilanMatkul');
    // });

    // {DOSEN}
    Route::prefix('dashboardDosen')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboardDosen');
    });

    Route::prefix('dosen_irsMahasiswa')->group(function () {
        Route::get('/', [IRSController::class, 'index'])->name('dosen_irsMahasiswa');
    });

    // {KAPRODI}
    Route::prefix('dashboardKaprodi')->group(function () {
        Route::get('/', [KaprodiControler::class, 'DashboardKaprodi'])->name('dashboardKaprodi');
    });

    // {AKADEMIK/BAK}
    Route::prefix('dashboardAkademik')->group(function () {
        Route::get('/', [DashboardController::class, 'indexAkademik'])->name('dashboardAkademik');
    });

    // {DEKAN}
    Route::prefix('dashboardDekan')->group(function () {
        Route::get('/', [DekanController::class, 'indexDekan'])->name('dashboardDekan');
    });
});

// Protected Routes with Authentication
Route::middleware('auth')->group(function () {
    // {DASHBOARDS}
    Route::get('/dashboardMahasiswa', [DashboardController::class, 'indexMahasiswa'])->name('dashboardMahasiswa');
    Route::get('/dashboardAkademik', [DashboardController::class, 'indexAkademik'])->name('dashboardAkademik');
    Route::get('/dashboardDekan', [DekanController::class, 'indexDekan'])->name('dashboardDekan');
    Route::get('/dashboardKaprodi', [KaprodiControler::class, 'DashboardKaprodi'])->name('dashboardKaprodi');
    Route::get('/dashboardDosen', [DashboardController::class, 'index'])->name('dashboardDosen');

    // {DOSEN}
    Route::get('/dosen_IRSMahasiswa', [IRSController::class, 'index'])->name('dosen_irsMahasiswa');
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::get('/dosen/approve-irs/{nim}', [DosenController::class, 'approveIRS'])->name('dosen.approve.irs');
    Route::get('/dosen/cancel-approval-irs/{nim}', [DosenController::class, 'cancelApprovalIRS'])->name('dosen.cancel.approval.irs');
    Route::get('dosen/print-irs/{nim}', [DosenController::class, 'printIRS'])->name('dosen.print_irs');
    Route::get('dosen/print-irs-pdf/{nim}', [DosenController::class, 'printIRSPDF'])->name('dosen.print_irs_pdf');
    Route::get('/dosen/detail-irs/{nim}', [DosenController::class, 'detailIRS'])->name('dosen_detailIRSMahasiswa');
    Route::post('/approve-irs', [DosenController::class, 'approveSelectedIRS'])->name('approve.selected.irs');

    // {KAPRODI}
    Route::get('/kaprodi_JadwalKuliah', [KaprodiControler::class, 'JadwalKuliah'])->name('kaprodi_JadwalKuliah');
    Route::get('/kaprodi_CreateMatkul', [KaprodiControler::class, 'CreateMatkul'])->name('kaprodi_CreateMatkul');
    Route::post('/matkul/store', [KaprodiControler::class, 'store'])->name('matkul.store');
    Route::get('/kaprodi_StatusMahasiswa', [KaprodiControler::class, 'StatusMahasiswa'])->name('kaprodi_StatusMahasiswa');
    Route::get('/kaprodi_SetMatkul', [KaprodiControler::class, 'setMatkul'])->name('kaprodi_SetMatkul');
    Route::get('/kaprodi_UpdateDeleteMatkul', [KaprodiControler::class, 'UpdateDeleteMatkul'])->name('kaprodi_UpdateDeleteMatkul');

    // {DEKAN}
    Route::get('/dekan_PersetujuanRuang', [DekanController::class, 'PersetujuanRuang'])->name('dekan_PersetujuanRuang');
    Route::post('/ruang/acc', [DekanController::class, 'setujuiRuang'])->name('ruang.acc');
    Route::get('/dekan_PersetujuanJadwal', [DekanController::class, 'PersetujuanJadwal'])->name('dekan_PersetujuanJadwal');

    // {BAK}
    Route::get('/pembagianruang', [BAK_PembagianruangController::class, 'index'])->name('pembagianruang');
    Route::get('/bak_PembagianRuang', [BAK_PembagianruangController::class, 'indexPembagianRuang'])->name('bak_PembagianRuang');
    Route::post('/ruang/cancel', [BAK_PembagianruangController::class, 'cancelAlokasiRuang'])->name('cancel.ruang');
    Route::get('/bak_CekStatusRuang', [BAK_PembagianruangController::class, 'indexCekStatusRuang'])->name('bak_CekStatusRuang');
    Route::post('/ruang/store', [BAK_PembagianruangController::class, 'storeRuang'])->name('ruang.store');
    Route::get('/bak_CreateRuang', [BAK_PembagianruangController::class, 'indexCreateRuang'])->name('bak_CreateRuang');
    Route::post('/ruang/create', [BAK_PembagianruangController::class, 'createRuang'])->name('create.store');
    Route::get('/bak_UpdateDeleteRuang', [BAK_PembagianruangController::class, 'indexUpdateDeleteRuang'])->name('bak_UpdateDeleteRuang');
    Route::get('/bak_NextUpdateDeleteRuang', [BAK_PembagianruangController::class, 'indexNextUpdateDeleteRuang'])->name('bak_NextUpdateDeleteRuang');
    Route::post('/ruang/update', [BAK_PembagianruangController::class, 'updateRuang'])->name('update.ruang');
    Route::post('/ruang/delete', [BAK_PembagianruangController::class, 'deleteRuang'])->name('delete.ruang');

    // {MAHASISWA}
    Route::get('/rencanaStudi', [Mhs_PengisianIRSController::class, 'indexRencanaStudi'])->name('mhs_rencanaStudi');
    Route::get('/pengisianIRS', [Mhs_PengisianIRSController::class, 'indexPilihJadwal'])->name('mhs_pengisianIRS');
    Route::get('/daftarMatkul', [Mhs_PengisianIRSController::class, 'indexDaftarMatkul'])->name('mhs_daftarMatkul');
    Route::get('/rrencanaStudi', [Mhs_PengisianIRSController::class, 'rencanaStudi'])->name('mhs_rrencanaStudi');
    Route::get('/periodeIRSHabis', [Mhs_PengisianIRSController::class, 'periodeHabis'])->name('mhs_habisPeriodeIRS');
    Route::get('/newIRS', [Mhs_PengisianIRSController::class, 'newIRS'])->name('mhs_newIRS');
    Route::get('/draftIRS', [Mhs_PengisianIRSController::class, 'draftIRS'])->name('mhs_draftIRS');
    Route::post('/konfirmasi-irs', [Mhs_PengisianIRSController::class, 'konfirmasiIRS'])->name('konfirmasi_irs');
    Route::get('/cetak-pdf/{semester}', [Mhs_PengisianIRSController::class, 'cetak_pdf'])->name('cetak.pdf');
    // Route::get('/pengambilanMatkul', [Mhs_PengisianIRSController::class, 'indexAmbilMatkul'])->name('pengambilanMatkul');
});

// Commented Routes at the Bottom for Reference
//Pembagian Ruang
// Route::get('/pembagianruang', [BAK_PembagianruangController::class, 'index'])->name('pembagianruang');

// Pengisian IRS
// Route::get('/pengisianIRS', [Mhs_PengisianIRSController::class, 'index'])->name('pengisianIRS');

// Route::get('/dashboardAkademik', [DashboardController::class, 'indexAkademik'])->name('dashboardAkademik');