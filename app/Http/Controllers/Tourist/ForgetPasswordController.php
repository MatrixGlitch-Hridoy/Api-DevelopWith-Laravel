<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Mail;
use App\Mail\ForgotMail;
use Exception;

class ForgetPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=>false,
                "errors"=>$validator->errors()
            ],401);
        }

        $email = $request->email;

        if(User::where('email',$email)->exists())
        {
            $token=rand(10,100000);

            try{
                DB::table('password_resets')->insert([
                    'email'=>$email,
                    'token'=>$token
                ]);
        
                Mail::to($email)->send(new ForgotMail($token));
                return response([
                    'message'=>'Reset Password send to your email'
                ],200);
            }
            catch(Exception $e){
                return response()->json([
                    'success'=>false,
                    'message'=>'Something is Wrong...',
                ],400);
            }
        }
        else{
            return response([
                'message'=>'Email Not Found'
            ],404);
        }
       
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=>false,
                "errors"=>$validator->errors()
            ],401);
        }

        $email = $request->email;
        $token = $request->token;
        $password = Hash::make($request->password);

        $emailCheck = DB::table('password_resets')->where('email',$email)->first();
        $tokenCheck = DB::table('password_resets')->where('token',$token)->first();

        if(!$emailCheck)
        {
            return response([
                'message'=>'Email Not Found'
            ],401);
        }
        if(!$tokenCheck)
        {
            return response([
                'message'=>'Invalid Token'
            ],401);
        }

        DB::table('users')->where('email',$email)->update(['password'=>$password]);
        DB::table('password_resets')->where('email',$email)->delete();

        return response([
            'message'=>'Password Reset Successfully'
        ],200);
    }
}
