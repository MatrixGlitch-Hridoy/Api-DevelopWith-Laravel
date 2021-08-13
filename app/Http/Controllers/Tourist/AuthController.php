<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'username'=>'required|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                "errors"=>$validator->errors()
            ],401);
        }

        try{

            $tourist=User::create([
                'name'  => $request->name,
                'username'  => $request->username,
                'email'  => $request->email,
                'password'  => Hash::make($request->password),
            ]);

            return response()->json([
                'success'=>true,
                'message'=>'User Registration Successful',
                'data'=>$tourist
            ],200);
        }
        catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Something is Wrong...',
            ],400);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                "errors"=>$validator->errors()
            ],401);
        }

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials))
        {
            $user = Auth::user();
            $data['name'] = $user->name;
            $data['access_token'] = $user->createToken('accessToken')->accessToken;
            return response()->json([
                'data' => $data
            ]);
        }
    }
    
    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out.'
        ]);
    }
}
