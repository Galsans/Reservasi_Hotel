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
                        <h4 class="card-title mb-3">List rooms</h4>
                        <!-- Menggunakan d-flex agar sejajar -->
                        <div class="d-flex justify-content-lg-between mb-4">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                data-target="#createModal">
                                Create
                            </button>

                            <!-- Modal Create rooms -->
                            <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                                aria-labelledby="createModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="createModalLabel">Form rooms</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form class="forms-sample" action="{{ route('rooms.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="createInputTypeRoom">Tipe Kamar</label>
                                                    <select name="jenis_kamar" class="form-control">
                                                        <option selected disabled>--Pilih Tipe Kamar--</option>
                                                        <option value="suite">Suite</option>
                                                        <option value="deluxe">Deluxe</option>
                                                        <option value="standard">Standard</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="createInputStatus">Status</label>
                                                    <select name="status" class="form-control">
                                                        <option selected disabled>--Pilih Status--</option>
                                                        <option value="terisi">Terisi</option>
                                                        <option value="tersedia">Tersedia</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="createInputImage">Image</label>
                                                    <input type="file" class="form-control" name="img" required
                                                        id="createInputImage" placeholder="Image">
                                                </div>
                                                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- END MODAL CREATE --}}

                            <!-- Search Form & Reset Button -->
                            <div class="search-field ms-3 d-flex" style="width: 50%; min-width: 250px;">
                                <form class="d-flex align-items-center w-100" method="GET"
                                    action="{{ route('rooms.index') }}">
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-light">
                                            <i class="mdi mdi-magnify"></i>
                                        </span>
                                        <input type="text" name="search" id="searchInput"
                                            class="form-control border-0 bg-light" placeholder="Search rooms"
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
                                window.location.href = "{{ route('rooms.index') }}"; // Redirect tanpa parameter search
                            });
                        </script>

                        <!-- Tabel responsif agar tidak pecah di layar kecil -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Foto</th>
                                        <th>No Kamar</th>
                                        <th>Tipe Kamar</th>
                                        <th>Status</th>
                                        <th>Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rooms as $item)
                                        <tr>
                                            <td class="text-dark"><img src="{{ Storage::url($item->img) }}" alt="">
                                            </td>
                                            <td class="text-dark">{{ $item->no_kamar }}</td>
                                            <td class="text-dark">{{ $item->jenis_kamar }}</td>
                                            <td class="text-dark">{{ $item->status }}</td>
                                            <td class="text-dark">
                                                Rp.{{ number_format($item->harga_per_malam, 0, ',', '.') }}</td>
                                            <td>
                                                @include('admin.partial.showRoom')
                                                <!-- Button edit trigger modal -->
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#updateModal{{ $item->id }}"
                                                    data-id="{{ $item->id }}">
                                                    {{-- <i class="fa fa-pencil-square"></i>  --}}
                                                    Update
                                                </button>
                                                <!-- Modal Update rooms -->
                                                <div class="modal fade" id="updateModal{{ $item->id }}"
                                                    aria-labelledby="roomsModalLabel{{ $item->id }}" tabindex="-1"
                                                    role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"
                                                                    id="roomsModalLabel{{ $item->id }}">Form
                                                                    rooms</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="forms-sample"
                                                                    action="{{ route('rooms.update', $item->id) }}"
                                                                    method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <label for="createInputTypeRoom">Tipe Kamar</label>
                                                                        <select name="jenis_kamar"
                                                                            class="form-control text-dark">
                                                                            <option disabled>--Pilih Tipe Kamar--</option>
                                                                            <option value="suite"
                                                                                {{ $item->jenis_kamar == 'suite' ? 'selected' : '' }}>
                                                                                Suite</option>
                                                                            <option value="deluxe"
                                                                                {{ $item->jenis_kamar == 'deluxe' ? 'selected' : '' }}>
                                                                                Deluxe</option>
                                                                            <option value="standard"
                                                                                {{ $item->jenis_kamar == 'standard' ? 'selected' : '' }}>
                                                                                Standard</option>
                                                                        </select>

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="createInputStatus">Status</label>
                                                                        <select name="status"
                                                                            class="form-control text-dark text-bold">
                                                                            <option disabled>--Pilih Status--
                                                                            </option>
                                                                            <option value="terisi"
                                                                                {{ $item->status == 'terisi' ? 'selected' : '' }}>
                                                                                Terisi</option>
                                                                            <option value="tersedia"
                                                                                {{ $item->status == 'tersedia' ? 'selected' : '' }}>
                                                                                Tersedia</option>

                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="createInputImage">Image</label>
                                                                        <img src="{{ Storage::url($item->img) }}"
                                                                            alt="">
                                                                        <input type="file" class="form-control"
                                                                            name="img" id="createInputImage"
                                                                            placeholder="Image">
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="btn btn-gradient-primary me-2">Submit</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- END MODAL Update --}}


                                                {{-- <button class="btn btn-sm btn-danger">Delete</button> --}}
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
                                                    action="{{ route('rooms.destroy', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Data Tidak Ada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- Pagination links -->
                            {{-- <div class="mt-2 gap-2 d-flex justify-content-between">
                                {{ $rooms->links('vendor.pagination.custom') }}
                            </div> --}}
                        </div> <!-- /table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
