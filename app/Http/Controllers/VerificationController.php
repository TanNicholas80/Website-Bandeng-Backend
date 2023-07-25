<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($mitra_id, Request $req ) {
        if(!$req->hasValidSignature()) {
            return response()->json(['msg' => 'Invalid / Expired url provided.'], 401);
        }

        $mitra = Mitra::find($mitra_id);

        if(!$mitra->hasVerifiedEmail()) {
            $mitra->markEmailAsVerified();
        } else {
            return response()->json([
                "status" => 400,
                "message" => "Email Already Verified"
            ], 400);
        }

        return response()->json([
            "status" => 200,
            "message" => "Your Email $mitra->email Successfully Verified",
        ], 200);
    }
}