<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<!-- Button Detail trigger modal -->
<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
    Detail
</button>

<!-- Modal Detail Kamar -->
<div class="modal fade" id="detailModal{{ $item->id }}" aria-labelledby="roomModalLabel{{ $item->id }}"
    tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roomModalLabel{{ $item->id }}">
                    🛏️ Detail Reservations - No. {{ $item->rooms->no_kamar }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 text-start">
                <div class="text-center mb-4">
                    <div class="thumb">
                        <img src="{{ Storage::url($item->rooms->img) }}" alt="Gambar Kamar"
                            class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: cover;">
                    </div>
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>🏷️ Jenis Kamar:</strong> {{ ucfirst($item->rooms->jenis_kamar) }}
                    </li>
                    <li class="list-group-item">
                        <strong>💰 Total Biaya:</strong> Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                    </li>
                    <li class="list-group-item">
                        <strong>📖 Status:</strong>
                        <span class="badge {{ $item->status == 'konfirmasi' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong>🧰 Fasilitas:</strong><br>
                        @php
                            $facilities = json_decode($item->rooms->fasilitas, true);
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
                    </li>
                </ul>
            </div>

            <div class="modal-footer justify-content-end">
                @if ($item->status == 'menunggu pembayaran')
                    <form action="{{ route('reservation.confirm', $item->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure to confirm this booking?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                    </form>
                @elseif ($item->status === 'terkonfirmasi')
                    <form action="{{ route('reservation.checkout', $item->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure to check-out this booking?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary">Check
                            Out</button>
                    </form>

                    {{-- <a href="{{ route('reservation.checkout', $booking->id) }}"
                                                    class="">Check Out</a> --}}
                @else
                    <span class="btn btn-success disabled">Sudah
                        Selesai</span>
                @endif
                <button type="button" class="btn btn-secondary text-dark" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
