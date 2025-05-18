@include('partial.style')
@include('partial.header')
@include('partial.script')
@include('sweetalert::alert')

<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle">Form Pengisian Reservasi</h2>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a></li>
                <li class="active">Form Pengisian Reservasi</li>
            </ol>
        </div>
    </div>
</section>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Form Reservasi Kamar</h2>

    {{-- Notifikasi Success --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Card Informasi Kamar --}}
        <div class="col-md-5">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <h4 class="card-title">Jenis Kamar : {{ $room->jenis_kamar }}</h4>
                    <p class="card-text mb-2">
                        <strong>Harga per Malam :</strong> Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}
                    </p>
                    <p class="card-text mb-2">
                        <strong>Maks. Tamu :</strong> {{ $room->max_tamu }} Orang
                    </p>
                    <p class="card-text mb-0"><strong>Fasilitas :</strong></p>
                    {{-- <ul class="ps-3">
                        @foreach (explode(',', $room->fasilitas) as $fasilitas)
                            <li>{{ trim($fasilitas) }}</li>
                        @endforeach
                    </ul> --}}
                    @php
                        $facilities = json_decode($room->fasilitas, true);
                    @endphp
                    @if (!empty($facilities))
                        <ul class="mt-2">
                            @foreach ($facilities as $facility)
                                <li> {{ $facility }}</li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-muted">Tidak ada fasilitas</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Reservasi --}}
        <div class="col-md-7">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <form action="{{ route('reservasi.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rooms_id" value="{{ $room->id }}">
                        <input type="hidden" name="users_id" value="{{ auth()->user()->id }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="check_in" class="form-label">Check-in</label>
                                <input type="date" name="check_in" id="check_in" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="check_out" class="form-label">Check-out</label>
                                <input type="date" name="check_out" id="check_out" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" required
                                value="{{ Auth::user()->name }}">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                value="{{ Auth::user()->email }}">
                        </div>

                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_tamu" class="form-label">Jumlah Tamu</label>
                            <input type="number" name="jumlah_tamu" id="jumlah_tamu" class="form-control"
                                value="{{ $room->max_tamu }}" required min="1" max="{{ $room->max_tamu }}">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Reservasi Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk isi otomatis dari localStorage --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkIn = localStorage.getItem('check_in');
        const checkOut = localStorage.getItem('check_out');

        if (checkIn) document.getElementById('check_in').value = checkIn;
        if (checkOut) document.getElementById('check_out').value = checkOut;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
