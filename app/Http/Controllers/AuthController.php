<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        
    }
    public function register(){
        return view('frontend.account.register');
    }

    public function processRegister(Request $request){
        
        $validator = Validator::make($request->all(),[
            'name' => 'required | min:3 | max:100',
            'email' => 'required | email | unique:users',
            'password' => 'required | min:6 | max:100',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->passes()){
            
        } else { 
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

}
