<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Profile;
use Auth;
use Validator;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=>'required',
            'password'=>'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->messages(), 401); 
        }

        $input = $request->all();
        $login = User::where('email', $input['email'])->first();
        if($login){
            //if ($login->count()>0){
            if (Hash::check($input['password'], $login->password)){
                    $api_token = sha1($login->id.time());
                    $create_token = User::where('id', $login->id)->update(['api_token' => $api_token]);
                    $n_ = User::where('email', $input['email'])->first();
                    return response()->json($n_);
            }else{
                return response()->json(['error' => 'Password salah'], 401);
            }
            //}
        }else{
            return response()->json(['error' => 'Email tidak terdaftar'], 401);
        }
    }
}
