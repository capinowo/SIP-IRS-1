<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-IRS Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Buat ubah font jadi poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- CSS dan JS dari public -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" type="text/css">
    <script type="text/javascript" src="{{ asset('js/javascript.js') }}"></script>

</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
                 <!-- Sidebar -->
        @include ('sidebar')

        <!-- Wave decoration -->
        <div class="wave-decoration"> 
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 35%; width: 35%;">
                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.00 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #fff;"></path>
            </svg>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fs-3 fw-bold">Selamat datang, {{  $dekan->dosen_nama }} 👋</h1>
                    <p class="text-muted">Semester Akademik Sekarang </p>
                </div>
                <div class="position-relative">
                    <!-- Button Notifikasi -->
                    <button class="btn btn-notification rounded-circle p-2">
                        <span class="material-icons text-white">notifications</span>
                    </button>
                    <span class="notification-badge"></span> <!-- Bulatan merah notifikasi -->
                </div>
            </div>


            <!-- Period Banner -->
            <div class="period-banner p-3 rounded-3 mb-4">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-column">
                        <span class="text-teal">Periode Penyetujuan Jadwal Kuliah</span>
                        <span class="text-teal">Periode Penyetujuan Ruang Kelas</span>
                    </div>
                    <div class="d-flex flex-column align-items-end">
                        <span class="text-teal fw-bold">bla</span> <!-- Tanggal periode jadwal kuliah -->
                        <span class="text-teal fw-bold">bla</span> <!-- Tanggal periode ruang kelas -->
                    </div>
                </div>
            </div>

            <!-- Progress Cards Jadwal Kuliah-->
            <div class="row g-4 mb-4">
                <!-- Progress Card -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h3 class="fs-5 fw-semibold mb-4">Progress persetujuan Jadwal Kuliah</h3>
                            <div class="d-flex justify-content-between text-center">
                                <!-- ini aku buat biar kita bisa liat tampilan klo datanya udah ada, aku ngecek side bar pake iniiii -->
                                <div>
                                    <div class="fs-4 fw-bold text-danger">
                                        1 / 1  
                                    </div>
                                    <div class="small text-muted">Belum<br>Mengusulkan</div>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-konfirmasi">
                                        1 / 1  
                                    </div>
                                    <div class="small text-">Telah<br>Dikonfirmasi</div>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-muted">
                                        1 / 1 
                                    </div>
                                    <div class="small text-muted">Belum<br>Dikonfirmasi</div>
                                </div>
                                <!-- <div>
                                    <div class="fs-4 fw-bold text-danger">
                                        $data['progress']['belum_mengusulkan']['count'] / $data['progress']['belum_mengusulkan']['total'] 
                                    </div>
                                    <div class="small text-muted">Belum<br>Mengusulkan</div>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-konfirmasi">
                                         $data['progress']['telah_dikonfirmasi']['count'] / $data['progress']['telah_dikonfirmasi']['total'] 
                                    </div>
                                    <div class="small text-">Telah<br>Dikonfirmasi</div>
                                </div>
                                <div>
                                    <div class="fs-4 fw-bold text-muted">
                                         $data['progress']['belum_dikonfirmasi']['count'] / $data['progress']['belum_dikonfirmasi']['total'] 
                                    </div>
                                    <div class="small text-muted">Belum<br>Dikonfirmasi</div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proggress Persetujuan Ruang Kuliah -->
                <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h3 class="fs-5 fw-semibold text-center">Persetujuan Ruang Kuliah</h3>
                                </div>
                                <p class="fs-6 fw-semibold mb-2">Anda belum mendapat usulan ruang kuliah</p>
                                <p class="text-muted mb-3">Silahkan kembali beberapa saat kedepan</p>
                                <button class="btn btn-danger">
                                    Lihat Detail
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div> 
            

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
