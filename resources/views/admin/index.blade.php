@extends('layouts.dashboard')

@section('content')
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

        @if (Auth::user()->role === 'admin')
            @php
                $userCount = App\Models\User::count();
                $roomCount = App\Models\Rooms::count();
                $reservationCount = App\Models\Reservations::count();
                $roomAvailable = App\Models\Rooms::where('status', 'tersedia')->count();
                $reservationAktif = App\Models\Reservations::where('status', 'terkonfirmasi')->count();
                $reservations = DB::table('reservations')
                    ->where('status', 'terkonfirmasi')
                    ->orWhere('status', 'check_out')
                    ->orderBy('created_at', 'desc')
                    ->get();
                $totalPendapatan = $reservations->sum('total_biaya');
                $roomPopularId = App\Models\Reservations::select('rooms_id')
                    ->groupBy('rooms_id')
                    ->orderByRaw('COUNT(*) DESC')
                    ->limit(1)
                    ->pluck('rooms_id')
                    ->first();

                $roomPopular = App\Models\Rooms::find($roomPopularId);

            @endphp

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                {{-- Users --}}
                <div class="col">
                    <div class="card text-white bg-primary h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="mdi mdi-account-group mdi-36px me-3"></i>
                            <div>
                                <h6 class="card-title mb-0">Users</h6>
                                <h4 class="fw-bold">{{ $userCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rooms --}}
                <div class="col">
                    <div class="card text-white bg-info h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="mdi mdi-bed mdi-36px me-3"></i>
                            <div>
                                <h6 class="card-title mb-0">Rooms</h6>
                                <h4 class="fw-bold">{{ $roomCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reservations --}}
                <div class="col">
                    <div class="card text-white bg-success h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="mdi mdi-calendar-check mdi-36px me-3"></i>
                            <div>
                                <h6 class="card-title mb-0">Reservations</h6>
                                <h4 class="fw-bold">{{ $reservationCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Available Rooms --}}
                <div class="col">
                    <div class="card text-white bg-secondary h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="mdi mdi-door-open mdi-36px me-3"></i>
                            <div>
                                <h6 class="card-title mb-0">Available Rooms</h6>
                                <h4 class="fw-bold">{{ $roomAvailable }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-secondary h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="mdi mdi-calendar-check mdi-36px me-3"></i>
                            <div>
                                <h6 class="card-title mb-0">Reservations Active</h6>
                                <h4 class="fw-bold">{{ $reservationAktif }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-white bg-secondary h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="mdi mdi-cash mdi-36px me-3"></i>
                            <div>
                                <h6 class="card-title mb-0">Total Pendapatan</h6>
                                <h4 class="fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card h-100 shadow-sm border-0">
                        @if ($roomPopular && $roomPopular->img)
                            <img src="{{ asset('storage/' . $roomPopular->img) }}" class="card-img-top"
                                alt="{{ $roomPopular->jenis_kamar }}" style="object-fit: cover; height: 200px;">
                        @else
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/2048px-No_image_available.svg.png"
                                class="card-img-top" alt="No Image" style="object-fit: cover; height: 200px;">
                        @endif

                        <div class="card-body">
                            <h6 class="card-title text-muted">🏆 Kamar Terpopuler</h6>

                            @if ($roomPopular)
                                <h4 class="fw-bold">{{ $roomPopular->jenis_kamar }}</h4>

                                <p class="mb-1">
                                    💰 <strong>Harga / Malam:</strong> Rp
                                    {{ number_format($roomPopular->harga_per_malam, 0, ',', '.') }}
                                </p>
                                <p class="mb-1">
                                    🛏️ <strong>Fasilitas:</strong> @php
                                        $facilities = json_decode($roomPopular->fasilitas, true);
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
                                </p>
                            @else
                                <p class="text-muted">Kamar tidak ditemukan.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
@endsection
