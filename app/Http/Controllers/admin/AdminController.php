<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    public function index()
    {

        return view("admin.login");
    }

    public function authenticate(Request $request)
    {

        $validate = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        if ($validate->passes()) {
            if (auth()->attempt(array('email' => $request->email, 'password' => $request->password))) {
                if (auth()->user()->is_admin == 1) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('admin.login')->with('error', 'You are not an authenticated person to access this site.');
                }
            } else {
                return redirect()->route('admin.login')->with('error', 'Email or Password is incorrect');
            }
        }
    }

    public function forgotAdminPassword()
    {
        return view('admin.forgot_admin_password');
    }

    public function processAdminPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required |email | exists:users,email',
        ]);

        if ($validator->fails()) {
            session()->flash('error', 'Invalid Email address');
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email address',
                'errors' => $validator->errors()
            ]);
        }

        $token = str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $admin = User::where('email', $request->email)->where('is_admin', 1)->first();

        if ($admin) {
            $formData = [
                'token' => $token,
                'user' => $admin,
            ];

            session()->flash('success', "Reset password link has been sent to your email.");

            Mail::to($request->email)->send(new AdminResetPassword($formData));

            return redirect()->route('admin.forgotAdminPassword')->with('success', "Reset password link has been sent to your email.");
        } else {
            session()->flash('error', 'Invalid Admin Email address. Enter Admin Email Address');
            return redirect()->route('admin.forgotAdminPassword');
        }
    }

    public function updateAdminPassword($token)
    {
        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExist == null) {
            return redirect()->route('account.userForgotPassword');
        }

        return view('admin.update_admin_password', compact('tokenExist'));
    }

    public function processUpdateAdminPassword(Request $request)
    {
        $token = $request->token;
        $tokenObj = DB::table('password_reset_tokens')->where('token', $token)->first();
        if ($tokenObj == null) {
            session()->flash('error', ' Password reset token not found');
            return redirect()->route('account.userForgotPassword');
        }

        $user =  User::where('email', $tokenObj->email)->first();

        $validator = Validator::make(request()->all(), [
            'new_password' => 'required | min:6 ',
            'confirm_password' => 'required | same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        session()->flash('success', "Your Password has been updated.");


        return response()->json([
            'status' => true,
            'message' => "Your Password has been updated",

        ]);
    }
}
