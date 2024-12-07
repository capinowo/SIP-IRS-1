<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-IRS Dashboard</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- CSS dan JS dari public -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" type="text/css">
    <script type="text/javascript" src="{{ asset('js/javascript.js') }}"></script>
    <style>
        .btn-teal {
            width: 45px;
            height: 45px;
            background-color: #028391;
            color: #ffffff;
        }
        .text-blue {
            color: #456DDB;
        }
        .card-body {
            background-color: #FFF2E5;
        }
        .bg-teal {
            background-color: #028391;
        }
        .btn-cyan {
            background-color: #67C3CC;
        }
        .btn-cyan:hover {
            background-color: #028391;
        }
        .table thead th, .table tbody td {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            font-size: 12px;
        }
        .d-flex.gap-3 {
            gap: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <x-sidebar-akademik :akademik="$akademik"></x-sidebar-akademik>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
            </div>

            <!-- Progress Cards -->
            <div class="card shadow-sm">
                <h5 class="card-header bg-teal text-white text-center">Tinjau dan Hapus Ruang Kelas</h5>
                <div class="card-body d-flex flex-column">
                    <div class="d-flex gap-3 text-center">
                        <!-- Dropdown Prodi -->
                        <!-- Dropdown Gedung -->
                        <div>
                            <div class="fw-bold">Gedung</div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuGedung" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih Gedung
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuGedung">
                                    <li><a class="dropdown-item dropdown-item-gedung" href="#">A</a></li>
                                    <li><a class="dropdown-item dropdown-item-gedung" href="#">B</a></li>
                                    <li><a class="dropdown-item dropdown-item-gedung" href="#">C</a></li>
                                    <li><a class="dropdown-item dropdown-item-gedung" href="#">D</a></li>
                                    <li><a class="dropdown-item dropdown-item-gedung" href="#">E</a></li>
                                    <li><a class="dropdown-item dropdown-item-gedung" href="#">F</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Ruang</th>
                                <th>Kapasitas</th>
                                <th>Aksi</th>
                        </thead>
                        <tbody>
                            @foreach($tabelRuang as $ruang)
                            <tr>
                                <td>{{ $ruang->nama }}</td>
                                <td>{{ $ruang->kapasitas }}</td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $ruang->id_ruang }}">
                                        Update
                                    </button>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $ruang->id_ruang }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            <!-- Update Modal -->
                            <div class="modal fade" id="updateModal{{ $ruang->id_ruang }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Ruang {{ $ruang->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('update.ruang') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_ruang" value="{{ $ruang->id_ruang }}">
                                                <div class="mb-3">
                                                    <label for="nama" class="form-label">Nama Ruang</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $ruang->nama }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kapasitas" class="form-label">Kapasitas</label>
                                                    <input type="number" class="form-control" id="kapasitas" name="kapasitas" value="{{ $ruang->kapasitas }}" required>
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $ruang->id_ruang }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Ruang {{ $ruang->nama }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('delete.ruang') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_ruang" value="{{ $ruang->id_ruang }}">
                                                <p class="mb-3">Apakah Anda yakin ingin menghapus ruang {{ $ruang->nama }}?</p>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dropdown Logic -->
    <script>
        $(document).ready(function() {
            // Get initial selected gedung
            const initialGedung = $('#dropdownMenuGedung').text().trim().replace('Gedung ', '');
            if (initialGedung !== 'Pilih Gedung') {
                filterTabelByGedung(initialGedung);
            }
    
            // Untuk Dropdown Prodi
            $('.dropdown-item-prodi').on('click', function() {
                $('#dropdownMenuProdi').text($(this).text());
            });
    
            // Untuk Dropdown Gedung
            $('.dropdown-item-gedung').on('click', function() {
                const gedung = $(this).text();
                $('#dropdownMenuGedung').text('Gedung ' + gedung);
                
                // Filter tabel berdasarkan gedung yang dipilih
                filterTabelByGedung(gedung);
            });
        });
    
        function filterTabelByGedung(gedung) {
            $('#tabelRuang tr').each(function() {
                if ($(this).find('td').length) { // Skip header row
                    const namaRuang = $(this).find('td:eq(1)').text().trim();
                    
                    if (namaRuang.toLowerCase().startsWith(gedung.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                }
            });
        }
    </script>
</body>
</html>