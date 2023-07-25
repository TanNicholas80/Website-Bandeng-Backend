<?php

namespace App\Http\Controllers;

use App\Mail\MitraVerification;
use Illuminate\Http\Request;
use App\Models\Mitra;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MitraController extends Controller
{
    public function register(Request $req) {
        $validator = Validator::make($req->all(), [
            'namaMitra' => 'required',
            'alamatMitra' => 'required',
            'tglLahir' => 'required',
            'email' => 'required|email|unique:mitras',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 401,
                'errors' => $validator->errors(),
            ], 422);
        }
        $mitra = Mitra::create([
            'namaMitra' => $req->namaMitra,
            'alamatMitra' => $req->alamatMitra,
            'tglLahir' => $req->tglLahir,
            'jeniskel' => $req->jeniskel,
            'no_tlp' => $req->no_tlp,
            'foto_mitra' => $req->foto_mitra,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        try {
            Mail::to($mitra)->send(new MitraVerification($mitra));
            echo 'suk';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        // if ($mitra) {
        //     try {
        //         return response()->json([
        //             'status' => 200,
        //             'message' => "Registered, verify your email address to login",
        //         ], 200);

        //         // echo 'as';
        //     } catch(Exception $err) {
        //         $mitra->delete();
                
        //         return response($err)->json([
        //             'status' => 500,
        //             'errors' => $err,
        //         ], 500);

        //         echo 'er';
        //     }
        // }
        // return response()->json([
        //     'status' => 500,
        //     'message' => "Failed To Create Mitra"
        // ]);
    }
    function login(Request $req) {
        $mitra = Mitra::where('email', $req->email)->first();
        if(!$mitra || !Hash::check($req->password, $mitra->password)) {
            return ["Error" => "Sorry, email or password doesn't match"];
        }
        return $mitra;
    }
}
