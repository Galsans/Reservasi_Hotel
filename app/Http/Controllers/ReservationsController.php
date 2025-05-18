<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use App\Models\Rooms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ReservationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $query = Reservations::query();

    //     $search = $request->query('search');

    //     $reservations = Reservations::all();
    //     // Cek apakah ada input pencarian
    //     if (!empty($search)) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('no_kamar', 'LIKE', '%' . $search . '%')
    //                 ->OrWhere('status', 'LIKE', '%' . $search . '%')
    //                 ->OrWhere('jenis_kamar', 'LIKE', '%' . $search . '%');
    //         });
    //     }
    //     foreach ($reservations as $reservation) {
    //         $checkIn = Carbon::parse($reservation->check_in);
    //         $checkOut = Carbon::parse($reservation->check_out);

    //         $reservation->totalHari = $checkIn->diffInDays($checkOut); // Menghitung selisih hari
    //     }

    //     return view('admin.reservations', compact(['reservations']));
    // }


    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Reservations::query();

        // Cek apakah ada input pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_reservation', 'LIKE', '%' . $search . '%'); // tambahkan ini
            });
        }

        $reservations = $query->get();

        // Hitung total hari untuk setiap reservasi
        foreach ($reservations as $reservation) {
            $checkIn = Carbon::parse($reservation->check_in);
            $checkOut = Carbon::parse($reservation->check_out);

            $reservation->totalHari = $checkIn->diffInDays($checkOut);
        }

        return view('admin.reservations', compact('reservations'));
    }

    public function create()
    {
        return view('welcome');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        // Urutan jenis kamar yang diinginkan
        $order = ['suite', 'deluxe', 'standard'];

        // Ambil kamar tersedia dan filter yang tidak bentrok
        $rooms = Rooms::where('status', 'tersedia')->get()->map(function ($room) use ($checkIn, $checkOut) {
            $reserved = Reservations::where('rooms_id', $room->id)
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<', $checkIn)
                                ->where('check_out', '>', $checkOut);
                        });
                })->count();

            $room->tersedia = ($room->jumlah ?? 1) - $reserved;

            return $room;
        })->filter(fn($r) => $r->tersedia > 0)
            ->sortBy(function ($room) use ($order) {
                // Gunakan index dari jenis_kamar sebagai nilai sorting
                return array_search(strtolower($room->jenis_kamar), $order);
            });

        // dd($rooms);
        return view('user.ketersediaan', compact('rooms'));
    }


    public function showReservationForm($room_id)
    {
        $room = Rooms::findOrFail($room_id);
        return view('user.create', compact('room'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'rooms_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'nama' => 'required|string',
            'email' => 'required|email',
            'nomor_telepon' => 'required|string|max:20',
        ]);

        $room = Rooms::findOrFail($request->rooms_id);

        if ($room->status !== 'tersedia') {
            Alert::error('Gagal', 'Tidak Bisa Reservasi, Kamar Sudah di Booking');
            return redirect('/');
        }   // Hitung jumlah hari

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $days = $checkIn->diffInDays($checkOut);

        // Hitung biaya kamar tanpa pajak
        $totalBiaya = $days * $room->harga_per_malam;

        // Hitung total biaya dengan pajak 12%
        // $taxRate = 0.12;
        // $totalBiaya = $subtotal + ($subtotal * $taxRate);

        $kode_bookings = 'BOOK-' . date('Ymd') . '-' . rand(1000, 9999);

        Reservations::create([
            'users_id' => auth()->id(),
            'rooms_id' => $request->rooms_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'jumlah_tamu' => $request->jumlah_tamu ?? 1,
            'kode_reservation' => $kode_bookings,
            'total_biaya' => $totalBiaya,
        ]);

        // Ubah status kamar menjadi "booking"
        $room->status = 'booking';
        $room->save();

        Alert::success('Success!!!', 'Berhasil Membuat Reservasi');
        return redirect()->route('user.reservation')->with('success', 'Reservasi berhasil disimpan!');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function your_booking()
    {
        $user = Auth::user()->id;
        $reservation = Reservations::where('users_id', $user)->get();
        return view('user.reservation', compact('reservation'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservations $reservations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservations $reservations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservations $reservations, $id)
    {
        $request->validate([
            'rooms_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'nama' => 'required|string',
            'email' => 'required|email',
            'nomor_telepon' => 'required|string|max:20',
            'jumlah_tamu' => 'required|integer|min:1',
        ]);

        $reservation = Reservations::findOrFail($id);

        // Jika ganti kamar, update status kamar lama ke 'available'
        if ($reservation->rooms_id != $request->rooms_id) {
            $oldRoom = Rooms::find($reservation->rooms_id);
            $oldRoom->status = 'tersedia';
            $oldRoom->save();
        }

        $room = Rooms::findOrFail($request->rooms_id);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $days = $checkIn->diffInDays($checkOut);
        $subtotal = $days * $room->harga_per_malam;
        $taxRate = 0.12;
        $totalBiaya = $subtotal + ($subtotal * $taxRate);

        // Update reservasi
        $reservation->update([
            'rooms_id' => $request->rooms_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'jumlah_tamu' => $request->jumlah_tamu,
            'total_biaya' => $totalBiaya,
        ]);

        // Ubah status kamar yang baru menjadi "booking"
        $room->status = 'booking';
        $room->save();

        Alert::success('Success!', 'Reservasi berhasil diperbarui.');
        return redirect()->route('user.reservation')->with('success', 'Reservasi berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reservation = Reservations::where('status', 'menunggu pembayaran')->find($id);
        $room = $reservation->rooms_id;

        $room->update([
            'status' => 'tersedia'
        ]);

        $reservation->delete();
        Alert::success('Success!!!', 'Berhasil Cancel Reservasi');
        return back();
    }

    public function checkOut($id)
    {
        // Ambil reservasi dengan status 'confirm'
        $reservation = Reservations::where('id', $id)->where('status', 'terkonfirmasi')->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'Reservasi tidak ditemukan atau status tidak valid.');
        }

        // Update status reservasi
        $reservation->status = 'check_out';
        $reservation->save();

        // Ambil kamar berdasarkan ID
        $room = Rooms::find($reservation->rooms_id);

        if ($room) {
            $room->status = 'tersedia';
            $room->save();
        }

        return redirect()->back()->with('success', 'Checkout berhasil dan status kamar diperbarui.');
    }


    public function confirmReservation(Request $request, $id)
    {
        $reservation = Reservations::where('id', $id)->where('status', 'menunggu pembayaran')->first();

        $room = $reservation->rooms;

        $room->update([
            'status' => 'terisi'
        ]);

        // Update data reser$reservation
        $reservation->update([
            'status' => 'terkonfirmasi',
        ]);

        return redirect()->back()->with('success', 'Konfimasi Berhasil');
    }
}
