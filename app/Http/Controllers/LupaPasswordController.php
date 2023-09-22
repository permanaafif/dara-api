<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\LupaPassword;
use App\Models\Pendonor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LupaPasswordController extends Controller
{
    public function sendOtp(Request $request){
        //set validation
        $validator = Validator::make($request->all(), [
        'kode_pendonor' => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = Pendonor::where('kode_pendonor',$request->kode_pendonor)->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'kode pendonor tidak terdaftar'
            ]);
        }
        $email = $user->email;
        if(!$email){
            return response()->json([
                'success' => false,
                'message' => 'email tidak terdaftar'
            ]);
        }
        $token = Str::random(64);
        $otp = rand(1000,9999);

        $cekEmail = LupaPassword::where('email',$email)->first();
        
        if($cekEmail){
            $cekEmail->delete();
        }

        LupaPassword::create([
            'email' => $email,
            'token' => $token,
            'otp' => $otp,
            'created_at' => now()
        ]);

        Mail::to($email)->send(new SendOtpMail($otp));
        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil di kirim',
            'email' => $email,
            'token' => $token
        ]);
    }

    public function checkOtp(Request $request){
        //set validation
       $validator = Validator::make($request->all(), [
        'email' => 'required',
        'token'  => 'required',
        'otp'  => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $otp = LupaPassword::where('email',$request->email)->where('token',$request->token)
        ->where('otp',$request->otp)->first();
        if(!$otp){
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah'
            ]);
        }
        $otp->token = $token = Str::random(64);
        $otp->update();
        return response()->json([
            'success' => true,
            'message' => 'Kode OTP Benar',
            'email' => $request->email,
            'token' => $otp->token
        ]);
    }

    public function resetPassword(Request $request){
        //set validation
       $validator = Validator::make($request->all(), [
        'email' => 'required',
        'token'  => 'required',
        'password'  => 'required'
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $otp = LupaPassword::where('email',$request->email)->where('token',$request->token)->first();
        if(!$otp){
            return response()->json([
                'success' => false,
                'message' => 'Token Expired'
            ]);
        }
        $user = Pendonor::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Email tidak di temukan'
            ]);
        }
        $user->password = Hash::make($request->password);
        $user->update();

        $lp = LupaPassword::where('email',$request->email)->first();
        if($lp){
            $lp->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Reset Password Berhasil'
        ]);
    }
}
