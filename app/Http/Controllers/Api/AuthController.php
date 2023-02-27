<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $filds =$request->validate([
            'First_Name'      => 'required|string',
            'Last_Name'      => 'required|string',
            'phone'         =>'required',
            'email'     => 'required|string|unique:Users',
            'password'  =>'required|string'
        ]);
        $user=User::create([
            'First_Name'        =>$filds['First_Name'],
            'Last_Name'         =>$filds['Last_Name'],
            'phone'             =>$filds['phone'],
            'email'             =>$filds['email'],
            'password'          => bcrypt($filds['password'])
        ]);

        $respons=[
            'user'=>$user,
        ];

        return response($respons,201);
    }

    public function login(Request $request){
        $filds =$request->validate([
            'email'     => 'required|string',
            'password'  =>'required|string'
        ]);


        //check email
        $user = User::where('email',$filds['email'])->first();

        //check password
        if(!$user ||!Hash::check($filds['password'],$user->password)){
            return response(['mesegag' => 'bad'], 401);
        }

        $token =$user->createToken('myappToken')->plainTextToken;

        $respons=[
            'user'=>$user,
            'token' =>$token
        ];

        return response($respons,201);
    }



    public function logout(Request $request){
        auth()->logout();
         return response()->json(['message' => 'User successfully signed out']);
        // return [
        //     'massege'   => 'loggout'
        // ];
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

}
