<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PDFController extends Controller
{
    public function generatePDF()
    {
        $user = Auth::user()->id;
        $reservation = Reservations::where('users_id', $user)->get();

        $data = [
            'title' => 'Daftar Reservasi Kamu',
            'date' => now()->format('d-m-Y'),
            'reservations' => $reservation
        ];
        $pdf = Pdf::loadView('user.pdf', $data);

        return $pdf->download('user.pdf');

        Alert::success('Success!!!', 'berhasil menyimpan data');
        return redirect()->route('user.reservation');
    }


    public function pendapatan(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $reservations = DB::table('reservations')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->where('status', 'terkonfirmasi')
            ->orWhere('status', 'check_out')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = $reservations->sum('total_biaya');

        return view('admin.pendapatan', compact('reservations', 'totalPendapatan', 'startDate', 'endDate'));
    }
}
