<?php

namespace App\Http\Controllers;

use App\Models\Jadwal_donor_darah;
use Illuminate\Http\Request;

class JadwalDonorDarahController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    public function show(){
        $jadwal_donor_darah = Jadwal_donor_darah::all();
        return response()->json(
            $jadwal_donor_darah
        );
    }
}
