@include('partial.style')
@include('partial.header')
@include('partial.script')
@include('sweetalert::alert')
<section class="breadcrumb_area">
    <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
    <div class="container">
        <div class="page-cover text-center">
            <h2 class="page-cover-tittle">Your Booking</h2>
            <ol class="breadcrumb">
                <li><a href="index.html">Home</a></li>
                <li class="active">Your Booking</li>
            </ol>
        </div>
    </div>
</section>
<section class="latest_blog_area section_gap">
    <div class="container">
        @if ($reservation->isNotEmpty())
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('user.pdf') }}" class="btn btn-danger shadow-sm d-flex align-items-center">
                    <i class="mdi mdi-file-pdf-box me-2" style="font-size: 1.2rem;"></i>
                    <span>Unduh PDF</span>
                </a>
            </div>
        @endif


        <div class="row mb_30">
            @forelse ($reservation as $item)
                @php
                    $checkIn = \Carbon\Carbon::parse($item->check_in);
                    $checkOut = \Carbon\Carbon::parse($item->check_out);
                    $durasi = $checkIn->diffInDays($checkOut);
                @endphp
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="single-recent-blog-post border rounded shadow-sm">
                        <div class="thumb">
                            <img class="img-fluid rounded-top" src="{{ Storage::url($item->rooms->img) }}"
                                alt="Room Image">
                        </div>
                        <div class="details p-3">
                            <div class="mb-3">
                                <label class="d-block text-muted small mb-1">Kode Reservasi</label>
                                <div class="border rounded px-3 py-2 bg-light text-primary fw-semibold text-center"
                                    style="font-size: 1rem;">
                                    {{ $item->kode_reservation }}
                                </div>
                            </div>

                            <h5 class="mb-2">{{ $item->nama }}</h5>
                            <p class="mb-1"><strong>Total Harga:</strong> Rp
                                {{ number_format($item->total_biaya, 0, ',', '.') }}</p>
                            <p class="mb-1"><strong>Check-in:</strong> {{ $checkIn->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Check-out:</strong> {{ $checkOut->format('d M Y') }}</p>
                            <p class="mb-0"><strong>Durasi:</strong> {{ $durasi }} malam</p>
                            <div class="mt-3">
                                @include('user.partialReservation.show')
                                <button type="button" class="btn btn-primary btn-sm mr-2" style="cursor: pointer"
                                    data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}"
                                    data-id="{{ $item->id }}">
                                    {{-- <i class="fa fa-pencil-square"></i>  --}}
                                    Update
                                </button>
                                <!-- Modal Update rooms -->
                                <div class="modal fade" id="updateModal{{ $item->id }}"
                                    aria-labelledby="roomsModalLabel{{ $item->id }}" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="roomsModalLabel{{ $item->id }}">Form
                                                    rooms</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('reservation.update', $item->id) }}"
                                                    method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <input type="hidden" name="rooms_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="users_id"
                                                        value="{{ auth()->user()->id }}">

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label for="check_in" class="form-label">Check-in</label>
                                                            <input type="date" name="check_in" id="check_in"
                                                                value="{{ $item->check_in }}" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="check_out" class="form-label">Check-out</label>
                                                            <input type="date" name="check_out" id="check_out"
                                                                value="{{ $item->check_out }}" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                                        <input type="text" name="nama" id="nama"
                                                            class="form-control" required value="{{ $item->nama }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Alamat Email</label>
                                                        <input type="email" name="email" id="email"
                                                            value="{{ $item->email }}" class="form-control" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="nomor_telepon" class="form-label">Nomor
                                                            Telepon</label>
                                                        <input type="text" name="nomor_telepon" id="nomor_telepon"
                                                            value="{{ $item->nomor_telepon }}" class="form-control"
                                                            required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jumlah_tamu" class="form-label">Jumlah
                                                            Tamu</label>
                                                        <input type="number" name="jumlah_tamu" id="jumlah_tamu"
                                                            class="form-control" value="{{ $item->jumlah_tamu }}"
                                                            required min="1" max="{{ $item->max_tamu }}">
                                                    </div>

                                                    <button type="submit" class="btn btn-success w-100">Reservasi
                                                        Sekarang</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Tidak ada data reservasi ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
