<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function Psy\debug;

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
            if(Auth::guard("admin") ->attempt(["email" => $request->email,"password"=> $request->password], $request->get("remember"))){
                $admin = Auth::guard('admin')->user(); 
                 
                if($admin->role == '2'){
                    return redirect()->route('admin.dashboard')->with('message', 'Login Successfully! ');
                }else{
                    return redirect()->route('admin.login')->with('error', 'You are autheticate person to access this site. ');
                }
                
            }else{
                return redirect()->route('admin.login')->with('error', 'Email or Password is incorrect');
            }
        }else{
            return redirect()->route('admin.login')->withErrors($validate)->withInput($request->only('email')); 
        }

    }
  
}
