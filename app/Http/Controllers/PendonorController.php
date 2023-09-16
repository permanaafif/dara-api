<?php

namespace App\Http\Controllers;

use App\Models\GolonganDarah;
use App\Models\Jadwal_donor_darah;
use App\Models\Jadwal_donor_pendonor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PendonorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','home']]);
    }

    public function login(Request $request){
       //set validation
       $validator = Validator::make($request->all(), [
        'kode_pendonor' => 'required',
        'password'  => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //get credentials from request
        $credentials = $request->only('kode_pendonor', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode pendonor atau Password Anda salah'
            ], 401);
        }

        //if auth success
        $user = auth()->guard('api')->user();
        return response()->json([
            'success' => true,
            'message' => 'berhasil login',
            'token'   => $token,
            'token_type' => 'bearer',
            'exp_token' => JWTAuth::factory()->getTTL()*1
        ], 200);
    }

    public function home(){
        $currentDate = Carbon::today()->format('Y-m-d');
        $user = auth()->guard('api')->user();
        $goldar = GolonganDarah::find($user->id_golongan_darah)->first();
        if(!$goldar){
            $goldar = null;
        }
        $jadwal = Jadwal_donor_pendonor::where('id_pendonor',$user->id)->get();
        $jadwal_me = null;
        $jadwal_pendonor = [];
        // Jadwal yang akan disimpan
        $jadwalTerdekat = [];

        if(!$jadwal){
            $jadwal_me = null;
        }else{
            foreach($jadwal as $i){
                $jadwal_pendonor[] = Jadwal_donor_darah::find($i->id_jadwal_donor_darah);
            }
            if (!empty($jadwal_pendonor)) {
                foreach ($jadwal_pendonor as $x) {
                    // Mengambil tanggal dari jadwal
                    $tanggalJadwal = Carbon::parse($x->tanggal_donor);
            
                    // Memeriksa apakah tanggal jadwal lebih besar atau sama dengan tanggal saat ini
                    if ($tanggalJadwal->greaterThanOrEqualTo($currentDate)) {
                        // Menambahkan jadwal yang dekat ke dalam array $jadwalTerdekat
                        $jadwalTerdekat[] = $x;
                    }
                }
                // Mengurutkan $jadwalTerdekat berdasarkan tanggal terkecil
                $jadwalTerdekat = collect($jadwalTerdekat)->sortBy(function ($jadwal) {
                    return $jadwal->tanggal_donor;
                })->values();

                // Mengambil jadwal terdekat yang pertama dari array yang telah diurutkan
                $jadwalPalingDekat = $jadwalTerdekat->first();

                if($jadwalPalingDekat){
                    $jadwal_me = $jadwalPalingDekat;
                }else{
                    $jadwal_me = null;
                }
                
                // $jadwalPalingDekat akan berisi jadwal yang paling mendekati tanggal saat ini
            }
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'gambar' => $user->gambar,
                'nama' => $user->nama,
                'kode_pendonor'=> $user->kode_pendonor,
                'id_golongan_darah' => [
                    'id' => $goldar->id,
                    'nama' => $goldar->nama
                ],
                'berat_badan'=> $user->berat_badan,
                'jadwal_terdekat' => $jadwal_me
                ]
        ]);
    }

    public function logout(){        
        //remove token
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if($removeToken) {
            //return response JSON
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil!',  
            ]);
        }
    }
}
