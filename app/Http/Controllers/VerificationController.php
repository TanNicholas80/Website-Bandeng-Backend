<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($mitra_id, Request $req ) {
        if(!$req->hasValidSignature()) {
            return view('mails.resend', ['msg' => 'Invalid / Expired url provided.'], 401);
        }

        $mitra = Mitra::find($mitra_id);

        if(!$mitra->hasVerifiedEmail()) {
            $mitra->markEmailAsVerified();
            return view('mails.success', ['status' => 200, 'message' => "Your Email $mitra->email Successfully Verified"]);
        } else {
            // return response()->json([
            //     "status" => 400,
            //     "message" => "Email Already Verified"
            // ], 400);
            return view('mails.error', ['status' => 400, 'message' => "Email Already Verified"]);
        }

        // return response()->json([
        //     "status" => 200,
        //     "message" => "Your Email $mitra->email Successfully Verified",
        // ], 200);
    }
}