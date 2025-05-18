<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $rooms = Rooms::all();
        $query = Rooms::query();

        $search = $request->query('search');

        // Cek apakah ada input pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_kamar', 'LIKE', '%' . $search . '%')
                    ->OrWhere('status', 'LIKE', '%' . $search . '%')
                    ->OrWhere('jenis_kamar', 'LIKE', '%' . $search . '%');
            });
        }

        // $rooms = $query->paginate(2)->appends(['search' => $search]);
        $rooms = $query->get();
        return view('admin.rooms', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "jenis_kamar" => 'required|in:suite,deluxe,standard',
            "status" => 'required|in:tersedia,terisi',
            "img" => "required|image|mimes:png,jpg,jpeg"
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }
        $validateData = $validate->validated();
        // no_room
        $count = Rooms::count() + 1;
        $no_kamar = 'K-' . $count;

        // harga/malam
        $harga = match ($validateData['jenis_kamar']) {
            'suite' => 500000,
            'deluxe' => 300000,
            'standard' => 200000,
        };

        // fasilitas
        $fasilitas = match ($validateData['jenis_kamar']) {
            'suite' => [
                "Tempat tidur king size",
                "Ruang tamu terpisah",
                "Jacuzzi",
                "Mini bar",
                "Smart TV",
                "Wi-Fi gratis",
                "Layanan kamar 24 jam"
            ],
            'deluxe' => [
                "Tempat tidur queen size",
                "TV kabel",
                "AC",
                "Mini bar",
                "Wi-Fi gratis",
                "Kamar mandi pribadi dengan shower"
            ],
            'standard' => [
                "Tempat tidur double",
                "TV",
                "Kipas angin atau AC",
                "Wi-Fi gratis",
                "Kamar mandi dalam"
            ],
        };

        $maxTamu = match ($validateData['jenis_kamar']) {
            'suite' => 7,
            'deluxe' => 5,
            'standard' => 3,
        };

        $imgPath = $request->file('img')->store('rooms', 'public');

        Rooms::create([
            "no_kamar" => $no_kamar,
            "img" => $imgPath,
            "jenis_kamar" => $request->input('jenis_kamar'),
            "harga_per_malam" => $harga,
            "fasilitas" => json_encode($fasilitas),
            "status" => $request->input('status'),
            "max_tamu" => $maxTamu
        ]);

        Alert::success('Success!!!', 'berhasil menyimpan data');
        return redirect()->route('rooms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rooms $rooms)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rooms $rooms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rooms $rooms, $id)
    {
        $validate = Validator::make($request->all(), [
            "jenis_kamar" => 'in:suite,deluxe,standard',
            "img" => "image|mimes:png,jpg,jpeg",
            "status" => 'in:tersedia,terisi',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput();
        }

        $rooms = Rooms::findOrFail($id);
        // no_room
        $no_kamar = 'K-' . $rooms->id;

        // harga/malam
        $harga = match ($request->input('jenis_kamar')) {
            'suite' => 500000,
            'deluxe' => 300000,
            'standard' => 100000,
        };

        $maxTamu = match ($request->input('jenis_kamar')) {
            'suite' => 7,
            'deluxe' => 5,
            'standard' => 3,
        };

        // fasilitas
        $fasilitas = match ($request->input('jenis_kamar')) {
            'suite' => [
                "Tempat tidur king size",
                "Ruang tamu terpisah",
                "Jacuzzi",
                "Mini bar",
                "Smart TV",
                "Wi-Fi gratis",
                "Layanan kamar 24 jam"
            ],
            'deluxe' => [
                "Tempat tidur queen size",
                "TV kabel",
                "AC",
                "Mini bar",
                "Wi-Fi gratis",
                "Kamar mandi pribadi dengan shower"
            ],
            'standard' => [
                "Tempat tidur double",
                "TV",
                "Kipas angin atau AC",
                "Wi-Fi gratis",
                "Kamar mandi dalam"
            ],
        };

        $rooms->no_kamar = $no_kamar;
        $rooms->jenis_kamar = $request->input('jenis_kamar', $rooms->jenis_kamar);
        $rooms->harga_per_malam = $harga;
        $rooms->fasilitas = json_encode($fasilitas);
        $rooms->status = $request->input('status', $rooms->status);
        $rooms->max_tamu = $maxTamu;


        if ($request->hasFile('img')) {
            if ($rooms->img) {
                Storage::delete($rooms->img);
            }
            $rooms->img = $request->file('img')->store('rooms', 'public');
        }

        $rooms->save();
        // dd($rooms);
        Alert::success('Success!!!', 'berhasil mangubah data');
        return redirect()->route('rooms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rooms $rooms, $id)
    {
        $rooms = Rooms::find($id);
        $rooms->delete();
        Alert::success('Success!!!', 'berhasil menyimpan data');
        return redirect()->route('rooms.index');
    }
}
