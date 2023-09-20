<?php

namespace App\Http\Controllers;

use App\Models\GolonganDarah;
use App\Models\Jadwal_donor_darah;
use App\Models\Jadwal_donor_pendonor;
use App\Models\Pendonor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function showProfile(){
        $user = auth()->guard('api')->user();
        $goldar = GolonganDarah::find($user->id_golongan_darah)->first();
        if(!$goldar){
            $goldar = null;
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
                'alamat_pendonor' => $user->alamat_pendonor,
                'tanggal_lahir' => $user->tanggal_lahir,
                'kontak_pendonor' => $user->kontak_pendonor,
                'jenis_kelamin' => $user->jenis_kelamin
                ]
        ]);
    }

    public function updateGambar(Request $request) {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan jenis gambar yang diterima
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Mendapatkan ID pengguna dari token
        $userId = auth()->guard('api')->user()->id;
    
        // Temukan pengguna berdasarkan ID
        $user = Pendonor::find($userId);
    
        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }
    
        // Simpan nama gambar lama untuk pengecekan
        $oldImageName = $user->gambar;
    
        // Mengunggah gambar baru jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $file_name);
            $user->gambar = $file_name;
        }
    
        $user->save();
    
        // Hapus gambar lama jika gambar baru diunggah
        if ($request->hasFile('gambar') && $oldImageName) {
            $oldImagePath = public_path('images/' . $oldImageName);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }    

    public function updateData(Request $request)
    {
        $userId = auth()->guard('api')->user()->id;

        // Validasi data yang diterima dari request
        // $validator = Validator::make($request->all(), [
        //     'nama' => 'required',
        //     'alamat_pendonor' => 'required',
        //     // 'tanggal_lahir' => 'required',
        //     'jenis_kelamin' => 'required',
        //     'kontak_pendonor' => 'required',
        //     'berat_badan' => 'required|integer', // Pastikan berat_badan adalah integer
        // ]);

        // // Jika validasi gagal, kembalikan pesan kesalahan
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 400);
        // }

        // Temukan pengguna berdasarkan ID
        $user = Pendonor::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        // Perbarui data pengguna
        $user->nama = $request->nama;
        $user->alamat_pendonor = $request->alamat_pendonor;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->kontak_pendonor = $request->kontak_pendonor;
        $user->berat_badan = $request->berat_badan;

        // Simpan perubahan
        $user->update();

        return response()->json([
            'success' => true,
             'message' => "berhasil update data"]);
    }



    public function editPassword(Request $request){
        $userId = auth()->guard('api')->user();
        $validator = Validator::make($request->all(), [
            'password_lama' => 'required|min:5',
            'password_baru' => 'required|min:5',
        ]);
    
        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        // Ambil password dari database
        $user = Pendonor::find($userId->id);
        $databasePassword = $user->password;

        // Memeriksa apakah password lama yang dimasukkan oleh pengguna cocok dengan password di database
        if (!Hash::check($request->password_lama, $databasePassword)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama Anda salah'
            ]);
        }

        // Jika password lama cocok, maka Anda dapat mengganti password
        $user->password = Hash::make($request->password_baru);
        $user->update();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil tukar password'
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
