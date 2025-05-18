@include('partial.style')
@include('partial.header')
@include('partial.script')
<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle">Kamar Yang Tersedia</h2>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a></li>
                <li class="active">Kamar Yang Tersedia</li>
            </ol>
        </div>
    </div>
</section>

<div class="container mt-5">
    <div class="row">
        @foreach ($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm rounded-4 h-100 d-flex flex-column">
                    {{-- Gambar kamar --}}
                    @if ($room->image)
                        <img src="{{ asset('storage/' . $room->image) }}" class="card-img-top rounded-top-4"
                        alt="Kamar {{ $room->jenis_kamar }}" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ Storage::url($room->img) }}" class="card-img-top rounded-top-4" alt="No Image"
                            style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $room->jenis_kamar }}</h5>
                        <p class="card-text"><strong>Harga / Malam:</strong> Rp
                            {{ number_format($room->harga_per_malam, 0, ',', '.') }}</p>
                        <p class="card-text"><strong>Status:</strong> {{ $room->status }}</p>

                        @php
                            $facilities = json_decode($room->fasilitas, true);
                        @endphp

                        @if (!empty($facilities))
                            <span for="">Fasilitas :</span>
                            <ul class="mt-2">
                                @foreach ($facilities as $facility)
                                    <li>{{ $facility }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted mt-2">Tidak ada fasilitas</span>
                        @endif

                        {{-- Tombol pilih di bagian bawah --}}
                        <a href="{{ route('reservasi.form', $room->id) }}" class="btn btn-primary mt-auto w-100">
                            Pilih Kamar Ini
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
