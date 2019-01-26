<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

class RegisterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function Register(Request $request){
        $validator = Validator::make($request->all(), [
            'email'=>'required|unique:users|max:191',
            'password'=>'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->messages(), 401); 
        }

        try{
            $input = $request->all();
            $hasher = app()->make('hash');
            $password = $hasher->make($input['password']);
            $save = User::create([
                'email'=>$input['email'],
                'password'=>$password,
                'api_token'=>''
            ]);
            return response()->json($save);
        }catch (\Illuminate\Database\QueryException $ex) {
            $res['error'] = $ex->getMessage();
            return response()->json($res, 500);
        }
    }
}
