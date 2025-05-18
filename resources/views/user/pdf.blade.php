<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .card h2 {
            margin-top: 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .label {
            font-weight: bold;
            width: 150px;
        }

        .value {
            flex: 1;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

        hr {
            margin: 16px 0;
        }
    </style>
</head>

<body>
    <h1 class="title">{{ $title }}</h1>
    <p>Date: {{ $date }}</p>

    @foreach ($reservations as $reservation)
        <div class="card">
            <h2>Kode Reservasi: {{ $reservation->kode_reservation }}</h2>
            <div class="info-row">
                <span class="label">Nama</span>
                <span class="value">{{ $reservation->name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email</span>
                <span class="value">{{ $reservation->email }}</span>
            </div>
            <div class="info-row">
                <span class="label">Jenis Kamar</span>
                <span class="value">{{ $reservation->rooms->jenis_kamar }}</span>
            </div>
            <div class="info-row">
                <span class="label">Harga Total</span>
                <span class="value">Rp {{ number_format($reservation->total_biaya, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Check-in</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Check-out</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Durasi</span>
                <span class="value">
                    {{ \Carbon\Carbon::parse($reservation->check_in)->diffInDays(\Carbon\Carbon::parse($reservation->check_out)) }}
                    malam
                </span>
            </div>
        </div>
    @endforeach

    <div class="footer">
        &copy; {{ now()->year }} Reservasi Hotel. Semua Hak Dilindungi.
    </div>
</body>

</html>
