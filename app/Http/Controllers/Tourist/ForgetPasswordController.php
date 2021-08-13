<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
}
