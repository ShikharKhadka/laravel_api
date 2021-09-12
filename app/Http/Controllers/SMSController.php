<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;

class SMSController extends Controller
{
    public function index(Request $request){
        
        $num = $request->input('mobile');
        $otp = mt_rand(1000,9999);

        Nexmo::message()->send([
            'to' => '977'.$num,
            'from' => '9779860478968',
            'text' => $otp,
        ]);
        echo "$otp";

    }
}
