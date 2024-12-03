<!-- resources/views/dashboard/mahasiswa.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengisian Rencana Studi - SIP-IRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- CSS dan JS dari public -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" type="text/css">
    <script type="text/javascript" src="{{ asset('js/javascript.js') }}"></script>
    <style>
        /* Tabel IRS */
        /* Mengubah warna header tabel */
        .table thead th {
            background-color: #FED488; /* Sesuaikan warna header */
            color: black; /* Teks putih */
            font-family: 'Poppins';
            text-align: center; /* Menengahkan teks */
            font-size: 12px;
        }

        .table tbody td {
            color: black; /* Teks putih */
            font-family: 'Poppins';
            text-align: center; /* Menengahkan teks */
            font-size: 12px;
        }

        /* Menambahkan roundness pada tabel */
        .table {
            border-radius: 10px; /* Sesuaikan besar roundness */
            overflow: hidden; /* Menghindari isi tabel keluar dari roundness */
        }
        
        /* Roundness untuk header */
        .table thead th:first-child {
            border-top-left-radius: 10px;
        }
        .table thead th:last-child {
            border-top-right-radius: 10px;
        }
        
        /* Roundness untuk footer jika dibutuhkan */
        .table tfoot td:first-child {
            border-bottom-left-radius: 10px;
        }
        .table tfoot td:last-child {
            border-bottom-right-radius: 10px;
        }

    </style> 
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- untuk manggil komponen sidebar -->
        <x-sidebar-mahasiswa :mahasiswa="$mahasiswa"></x-sidebar-mahasiswa>
        <!-- Wave decoration -->
        <div class="wave-decoration"> 
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 35%; width: 35%;">
                <path d="M0.00,49.98 C150.00,150.00 349.20,-49.00 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #fff;"></path>
            </svg>
        </div>
        <!-- Main Content -->
        
        <div class="main-content flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    {{-- <h1>{{$Periode_sekarang->jenis}}</h1> --}}
                </div>
                <div class="position-relative">
                    <button class="btn btn-primary rounded-circle p-2">
                        <span class="material-icons">notifications</span>
                    </button>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">Notifikasi baru</span>
                    </span>
                </div>
            </div>

             <!-- Pengisian IRS Cards -->
        {{-- <div class="col-12">
            <div class="card shadow-sm h-100">
              <h5 class="card-header" style="background-color: #027683; color: white;">Pengisian Rencana Studi</h5>
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="margincard">
                            <div class="fw-bold" style="font-size: 12px;">MAX BEBAN SKS</div>
                            <span class="badge irs-badge" style="background-color: #67C3CC;">0 SKS</span>
                        </div>
                        <div class="margincard" style="margin-left: 10px;">
                            <div class="fw-bold" style="font-size: 12px;">TOTAL SKS</div>
                            <span class="badge irs-badge" style="background-color: #67C3CC;">0 SKS</span>
                        </div>
                    </div>
                    <div>
                        <div class="margincard">
                            <div class="fw-bold" style="font-size: 12px;">MAX BEBAN SKS</div>
                        </div>
                    </div>
                </div>
            </div>  --}}
            {{-- <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Cari Jadwal Kuliah" aria-label="Search" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="button-addon2">Cari</button>
            </div> --}}
                
            <div class="period-banner mb-1 text-center font-size: 12px" style="background-color: #027683; color: white;">
                <div class="d-flex justify-content-center align-items-center">
                    <span class="fw-medium">Isian Rencana Studi</span>
                </div>
            </div>

            {{-- Rencana Studi untuk setiap semester --}}
            <!-- Accordion untuk Jadwal IRS -->
            <div class="accordion accordion-flush" id="accordionFlushExample">
                @foreach($semesters as $semester)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $semester }}" aria-expanded="false" aria-controls="flush-collapse{{ $semester }}">
                            Semester {{ $semester }}
                        </button>
                    </h2>
                    <div id="flush-collapse{{ $semester }}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            @if(isset($irsPerSemester[$semester]))
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode MK</th>
                                        <th>Mata Kuliah</th>
                                        <th>Kelas</th>
                                        <th>SKS</th>
                                        <th>Ruang</th>
                                        <th>Hari</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($irsPerSemester[$semester] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->kode_matkul }}</td>
                                        <td>{{ $item->nama_matkul }}</td>
                                        <td>{{ $item->kelas }}</td>
                                        <td>{{ $item->sks }}</td>
                                        <td>{{ $item->ruang }}</td>
                                        <td>{{ $item->hari }}</td>
                                        <td>{{ $item->jam_mulai }}</td>
                                        <td>{{ $item->jam_selesai }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p>Tidak ada data IRS untuk semester ini.</p>
                            @endif
                        </div>
                        <!-- Status Terakhir -->
                        <div class="alert alert-danger">
                            <strong>Status Terakhir IRS Semester {{ $semester }}:</strong> 
                            {{ $statusTerakhirPerSemester[$semester] ?? 'Belum ada status' }}
                        </div>
                        <!-- Tombol Cetak IRS -->
                        <div class="mt-3">
                            <a href="{{ route('cetak_pdf') }}" class="btn btn-primary">Cetak IRS</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
                    </div>
                  </div>
                </div>
              </div>

            

    </div>
  </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>