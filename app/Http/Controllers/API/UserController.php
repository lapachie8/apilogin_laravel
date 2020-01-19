<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(){
        if(Auth::attemp(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] = $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], $this->$successStatus);
        }else{
            return response()->json(['error' => 'Unauthorized', 401]);
        }
    }

    public function register(Request $request){
        $validator = Validato::make($request->all(),[
            'name' => 'required',
            'email '=> 'required|email',
            'password'=>'required',
            'c_password'=>'required|same::password',
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token']=$user->createToken('nApp')->accessToken;
        $success['name']=$user->name;

        return response()->json(['success'->$success], $this->$successStatus);
    }

    public function details(){
        $user = Auth::user();
        return response()->json(['success'=>$user], $this->$succesStatus);
    }
}
