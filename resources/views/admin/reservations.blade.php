@extends('layouts.dashboard')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: true
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title d-flex align-items-center">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-home"></i>
                </span>
                Dashboard
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span>Overview</span>
                        <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <script>
                setTimeout(function() {
                    document.getElementById('error-alert').style.display = 'none';
                }, 3000);
            </script>
        @endif


        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">List reservation</h4>
                        <!-- Menggunakan d-flex agar sejajar -->
                        <div class="d-flex justify-content-lg-between mb-4">
                            <!-- Search Form & Reset Button -->
                            <div class="search-field ms-3 d-flex" style="width: 50%; min-width: 250px;">
                                <form class="d-flex align-items-center w-100" method="GET"
                                    action="{{ route('reservation.index') }}">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-light">
                                            <i class="mdi mdi-magnify"></i>
                                        </span>
                                        <input type="text" name="search" id="searchInput"
                                            class="form-control border-0 bg-light" placeholder="Search reservation"
                                            value="{{ request('search') }}">
                                    </div>
                                    <!-- Reset Button -->
                                    @if (request('search'))
                                        <button type="button" class="btn btn-danger ms-2" id="resetSearch">Reset</button>
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- JavaScript untuk Reset Search -->
                        <script>
                            document.getElementById('resetSearch')?.addEventListener('click', function() {
                                window.location.href = "{{ route('reservation.index') }}"; // Redirect tanpa parameter search
                            });
                        </script>

                        <!-- Tabel responsif agar tidak pecah di layar kecil -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Reservation</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telepone</th>
                                        <th>Jumlah Tamu</th>
                                        <th>Total Hari</th>
                                        <th>Total Biaya</th>
                                        <th>Tipe Kamar</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reservations as $item)
                                        <tr>
                                            <td class="text-dark">{{ $item->kode_reservation }}</td>
                                            <td class="text-dark">{{ $item->nama }}</td>
                                            <td class="text-dark">{{ $item->email }}</td>
                                            <td class="text-dark">{{ $item->nomor_telepon }}</td>
                                            <td class="text-dark">{{ $item->jumlah_tamu }}</td>
                                            <td class="text-dark">{{ $item->totalHari }}</td>
                                            <td class="text-dark">
                                                Rp.{{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                                            <td class="text-dark">{{ $item->rooms->jenis_kamar }}</td>
                                            <td class="text-dark">{{ $item->status }}</td>
                                            <td class="d-flex gap-2">
                                                @include('admin.partial.showReservation')
                                                <!-- Button edit trigger modal -->
                                                {{--  --}}
                                                {{-- <button class="btn btn-sm btn-danger">Delete</button>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $item->id }})">Delete</button>

                                                <script>
                                                    function confirmDelete(id) {
                                                        Swal.fire({
                                                            title: "Apakah Anda yakin?",
                                                            text: "Data yang dihapus tidak dapat dikembalikan!",
                                                            icon: "warning",
                                                            showCancelButton: true,
                                                            confirmButtonColor: "#d33",
                                                            cancelButtonColor: "#3085d6",
                                                            confirmButtonText: "Ya, hapus!",
                                                            cancelButtonText: "Batal"
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                document.getElementById('delete-form-' + id).submit();
                                                            }
                                                        });
                                                    }
                                                </script>

                                                <form id="delete-form-{{ $item->id }}"
                                                    action="{{ route('reservation.destroy', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form> --}}
                                                @if ($item->status === 'check_out')
                                                    <span class="btn btn-sm btn-success disabled">Sudah
                                                        Selesai</span>
                                                @endif

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Data Tidak Ada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- /table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
