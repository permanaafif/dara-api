<?php

namespace App\Http\Controllers;

use App\Models\Jadwal_donor_darah;
use App\Models\Jadwal_donor_pendonor;
use App\Models\Pendonor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalDonorPendonorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function find($id)
    {
        $pendonor = Jadwal_donor_pendonor::where('id_pendonor', $id)->first();

        if (!$pendonor) {
            return response()->json(['message' => 'Pendonor not found']);
        }

        $pendonors = Jadwal_donor_pendonor::where('id_pendonor', $id)->get();

        $idJadwalPendonorArray = [];

        foreach ($pendonors as $jadwalDonor) {
            $idJadwalPendonorArray[] = $jadwalDonor->id_jadwal_donor_darah;
        }

        $jadwal = [];

        foreach ($idJadwalPendonorArray as $a) {
            $jadwal[] = Jadwal_donor_darah::find($a);
        }

        return response()->json($jadwal);
    }

    public function check($id, $idl){
        $pendonor = Jadwal_donor_pendonor::where('id_pendonor', $id)->first();

        if (!$pendonor) {
            return response()->json([
                'status' => false,
                'message' => 'Pendonor belum mendaftar']);
        }

        $pendonors = Jadwal_donor_pendonor::where('id_pendonor', $id)->get();

        $idJadwalPendonorArray = [];

        foreach ($pendonors as $jadwalDonor) {
            $idJadwalPendonorArray[] = $jadwalDonor->id_jadwal_donor_darah;
        }

        // memeriksa apakah elemen ada dalam array
        if (collect($idJadwalPendonorArray)->contains($idl)) {
            return response()->json([
                'status' => true,
                'message' => 'sudah mendaftar']);
        }

        return response()->json([
            'status' => false,
            'message' => 'belum mendaftar']);
    }

    public function daftar(Request $request){
        $validasi = Validator::make($request->all(), [
            'id_pendonor' => 'integer',
            'id_jadwal_donor_darah' => 'integer'
        ]);
    
        if($validasi->fails()){
            return response()->json([
                'status' => false,
                'message' => $validasi->errors()
            ]);
        }
    
        // Cari pendonor berdasarkan id_pendonor
        $pendonor = Pendonor::find($request->id_pendonor);
    
        if (!$pendonor) {
            return response()->json([
                'status' => false,
                'message' => 'Pendonor tidak ditemukan'
            ]);
        }
    
        // Cek apakah jadwal sudah ada
        $existingJadwal = Jadwal_donor_pendonor::where('id_pendonor', $request->id_pendonor)
            ->where('id_jadwal_donor_darah', $request->id_jadwal_donor_darah)
            ->first();
    
        if ($existingJadwal) {
            return response()->json([
                'status' => true,
                'message' => 'Sudah mendaftar'
            ]);
        }
    
        // Tambahkan jadwal baru
        $jadwalBaru = Jadwal_donor_pendonor::create([
            'id_pendonor' => $request->id_pendonor,
            'id_jadwal_donor_darah' => $request->id_jadwal_donor_darah
        ]);
    
        if($jadwalBaru){
            return response()->json([
                'status' => true,
                'message' => 'Menambahkan jadwal'
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Menambahkan jadwal gagal'
        ]);
    }
}
