<?php
namespace App\Http\Controllers;

use Illuminate\Support\Str;
Use app\Mail\TestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{
    public $sucessStatus = 200;
    public function register(Request $request)
   {
        $validatedData = $request->validate([
            'name'=>'required|String',
            'email'=>'email|required|unique:users',
            'password'=>'required|String'
        ]);
        $input= $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $sucess['token'] = $user->createToken('MyApp')->accessToken;
            $sucess['name'] = $user->name;
            return response()->json(['sucess'=>$sucess],$this->sucessStatus);

    }

    public function login()
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

       public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $token = Str::random(60);
        
        

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()

           ]);

           Mail::raw('https://spa.test/reset-password?token='.$token, function($message,) use ($email) {
            $message->subject('Reset Password')->to($email);


         });

        
        

    }

    public function token(Request $request){
        $request->validate([
            'email' => 'required',
            'token' => 'required|String',
            'password' => 'required|confirmed'
        ]);
        $token = $request->token;
        $email = $request->email;
        $resetPassword = DB::table('password_resets')->where('token',$token)->first();
        
        $user = User::where('email',$email)->first();
        if(!$user)
         {
           return response(['message'=>"User not found"],200);
        }
        if(!$resetPassword)
        {
            return response(['message'=>"Token not found"],200);
        }

        if(!$resetPassword->created_at >= now())
        {
            return response(['message'=>"Token has expired"],200);
        }
        

        $user = User::where('email', $request->email)
        ->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response(['message'=>'Successfully']);

    }




       public function reset1(Request $request)
       {
           $request->validate([
               'email' => 'required|email|exists:users'
           ]);
           
           $email = $request->email;
           $token = Str::random(60);

           DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()->addHours(6)

           ]);
           
           $status = Password::sendResetLink(
            $request->only('email')
        );
    
        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);


        }

        public function forgotpassword()
        {
            $request->validte([
                'token' => 'required',
                'password' => 'required'|'confirmed',
                'email' => 'required'
            ]);

            $status = Password::reset(
                $request->only('email','password','password_confirmation','token'),
                function($user)use ($request)
                {
                    $user->forceFill(
                        [
                            'password'=> Hash::make($request->password),
                            'remember_token' => Str::random(60),
                        ]
                    )->save();

                    event(new PasswordReset($user));

                    
                }
            );

            if($status == Password::PASSWORD_RESET){
                return response([
                    'message'=>'Password reset successfull'
                ]);
            }

            else{
                return response([
                    'message'=>__($status)
                ],500);
            }
            
        }


}
