<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP-IRS Dashboard</title>
    <!-- jQuery HARUS PERTAMA -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Kemudian Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
            <x-sidebar-kaprodi :kaprodi="$kaprodi"></x-sidebar-kaprodi>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
            </div>

            <!-- Progress Cards -->
            <div class="card shadow-sm">
                <h5 class="card-header bg-teal text-white text-center">Tambah Mata Kuliah Baru</h5>
                <div class="card-body d-flex flex-column">
                    <!-- Form Input Ruang -->
                    <div class="mt-4">
                    <form id="formInputMatkul" action="{{ route('matkul.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="kodeMatkul" class="form-label">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" name="kode_matkul" id="kodeMatkul" placeholder="Kode mata kuliah harus diawali PAIK atau UUW dan diikuti 4 angka." required>
                            <div class="invalid-feedback">Kode harus diawali 3-4 huruf kapital diikuti 4 angka.</div>
                        </div>
                        <div class="mb-3">
                            <label for="namaMatkul" class="form-label">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" name="nama_matkul" id="namaMatkul" placeholder="Masukkan Nama Mata Kuliah" required>
                            <div class="invalid-feedback">Nama mata kuliah harus terdiri dari 1-50 huruf.</div>
                        </div>
                        <div class="mb-3">
                            <label for="sks" class="form-label">SKS</label>
                            <input type="number" class="form-control" name="sks" id="sks" placeholder="SKS harus berupa angka antara 1-8, atau 'lainnya'" required>
                            <div class="invalid-feedback">SKS harus berupa angka antara 1-8, atau 'lainnya'.</div>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <input type="number" class="form-control" name="semester" id="semester" placeholder="Semester harus berupa angka antara 1-6, atau 'lainnya'" required>
                            <div class="invalid-feedback">Semester harus berupa angka antara 1-6, atau 'lainnya'.</div>
                        </div>
                        <div class="button-group-tabel">
                            <button type="submit" class="btn btn-cyan w-100 mb-5">
                                Simpan
                            </button>
                            <a href="{{ route('kaprodi_UpdateDeleteMatkul') }}" class="btn btn-warning">Kembali</a>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

     <!-- SweetAlert JS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Validation Logic -->
    <script>
        document.getElementById('formInputMatkul').addEventListener('submit', function (e) {
            
            const kodeMatkul = document.getElementById('kodeMatkul').value;
            const namaMatkul = document.getElementById('namaMatkul').value;
            const sks = parseInt(document.getElementById('sks').value);
            const semester = document.getElementById('semester').value;


            // Validasi Nama Mata Kuliah
            if (namaMatkul.length > 50) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Nama mata kuliah tidak boleh lebih dari 50 karakter.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validasi SKS
            if (isNaN(sks) || sks < 1 || sks > 8) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'SKS harus berupa angka antara 1-8.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

             // Validasi Semester
             if (isNaN(semester) || semester < 1 || semester > 6) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Semester harus berupa angka antara 1-6.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
        });
        // Success/Error message handler
        @if(session('sweetAlert'))
            document.addEventListener('DOMContentLoaded', function() {
                const alert = @json(session('sweetAlert'));
                Swal.fire({
                    title: alert.title,
                    text: alert.text,
                    icon: alert.icon,
                    confirmButtonColor: '#028391',
                    confirmButtonText: 'OK'
                });
            });
        @endif
    </script>
</body>
</html>