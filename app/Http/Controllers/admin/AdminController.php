<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
 

class AdminController extends Controller
{
    public function index(){
      
        return view("admin.login");
    }

    public function authenticate(Request $request){

        $validate = Validator::make($request->all(), [
            "email"=> "required|email",
            "password"=> "required",
        ]);

        if($validate->passes()){
            if(auth()->attempt(array('email' => $request->email, 'password' => $request->password))){ 
                if(auth()->user()->is_admin == 1){ 
                    return redirect()->route('admin.dashboard');
                }else{
                    return redirect()->route('admin.login')->with('error', 'You are not an authenticated person to access this site.');
                }
            }else{
                return redirect()->route('admin.login')->with('error', 'Email or Password is incorrect');
            }
        }
         

    }
  
}
