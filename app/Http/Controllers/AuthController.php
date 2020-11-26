<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except'=>['login', 'register']]);

    }

    public function login(Request $req){
        $validator = Validator::make(
            $req->all(),[
                'email'=>'required|email',
                'password'=> 'required|min:6'
            ]
        );
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $token_validity = (24*60);
        $this->guard()->factory()->setTTL($token_validity);
        if(!$token = $this->guard()->attempt($validator->validated())){
            return response()->json(['error'=>'Unauthorized'], 401);
        }
        return $this->responseWithToken($token);
    }

    public function register(Request $req){
        $validator = Validator::make($req->all(), [
            'name'=>'required|string|between:2,108',
            'email'=>'required|email|unique:users',
            'password'=> 'required|confirmed|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ], 422);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password'=> bcrypt($req->password)]
        ));
        return response()->json(['message'=>'User created successfully', 'user' => $user]);
    }

    public function profile(){
        return response()->json($this->guard()->user());
    }

    public function logout(){
        $this->guard()->logout();
        return response()->json(['message'=>'User logged out successfully']);

    }
    
    public function refresh(){
        return $this->responseWithToken($this->guard()->refresh());
    }

    protected function responseWithToken($token){
        return response()->json([
            'token'=> $token,
            'token_type'=> 'bearer',
            'token_validity'=> $this->guard()->factory()->getTTL() * 60
        ]);
    }

    protected function guard(){
        return Auth::guard();
    }
}
