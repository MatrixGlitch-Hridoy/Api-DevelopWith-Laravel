<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class TouristController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success'=>true,
            'message'=>'Display a listing of the resource',
            'data'=>User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            return response()->json([
                'success'=>true,
                'message'=>'Display the specified resource',
                'data'=>User::findOrFail($id)
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Error Showing Specific User!'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            return response()->json([
                'success'=>true,
                'message'=>'Display the specified resource',
                'data'=>User::findOrFail($id)
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Error Showing Specific User!'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

            $tourist=User::findOrFail($id);

            $tourist->name  = $request->name;
            $tourist->username  = $request->username;
            $tourist->email  = $request->email;
            $tourist->password  = $request->password;
            $tourist->save();

            return response()->json([
                'success'=>true,
                'message'=>'User Updated Successful',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            User::findOrFail($id)->delete();
            return response()->json([
                'success'=>true,
                'message'=>'User Deleted Succesfully',
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Error!',
            ]);
        }
    }

    public function currentUser()
    {
        return Auth::user();
    }
}

