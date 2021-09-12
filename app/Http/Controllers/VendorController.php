<?php

namespace App\Http\Controllers;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Validator;

class VendorController extends Controller
{
    public $sucessStatus = 200;
    public function register(Request $request)
   {
        $validatedData = $request->validate([
            'email'=>'email|required|unique:users',
            'password'=>'required|String'
        ]);
        $input= $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = Vendor::create($input);
            $sucess['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['sucess'=>$sucess],$this->sucessStatus);

    }
    public function login(){
        {
            if(Auth::attempt(['email' => request('email'),'password' => request('password')])) {
                $user = Auth::user();
                $sucess['name'] = $user->name; 
                $sucess['token'] = $user->createToken('MyApp')->accessToken;
                return response()->json(['sucess'=>$sucess],$this->sucessStatus);
            }
            else{
                return response()->json(['error'=>'Unauthorized'],401);
            }
    
            $accessToken = auth()->user()->createToken('authToken')->accessToken;
    
            return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    
       }
}

}
