<?php

namespace App\Http\Controllers;

use App\Models\Jadwal_donor_darah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalDonorDarahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    public function show(){
        $currentDate = Carbon::today()->format('Y-m-d');
        $jadwal_donor_darah = Jadwal_donor_darah::all();
        $jadwalTerdekat = [];
        if (!empty($jadwal_donor_darah)) {
            foreach ($jadwal_donor_darah as $x) {
                // Mengambil tanggal dari jadwal
                $tanggalJadwal = Carbon::parse($x->tanggal_donor);
        
                // Memeriksa apakah tanggal jadwal lebih besar atau sama dengan tanggal saat ini
                if ($tanggalJadwal->greaterThanOrEqualTo($currentDate)) {
                    // Menambahkan jadwal yang dekat ke dalam array $jadwalTerdekat
                    $jadwalTerdekat[] = $x;
                }
            }
        }
        if(!empty($jadwalTerdekat)){
            $jadwal_donor_darah = $jadwalTerdekat;
        }else{
            $jadwal_donor_darah = null;
        }
        return response()->json(
            $jadwal_donor_darah
        );
    }
}
