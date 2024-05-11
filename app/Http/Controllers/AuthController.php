<?php

namespace App\Http\Controllers;

use App\Mail\UserResetPassword;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function App\Helpers\successMessage;

class AuthController extends Controller
{
    public function login()
    {
        return view('frontend.account.login');
    }
    public function register()
    {
        return view('frontend.account.register');
    }

    public function processRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required | min:3 | max:100',
            'email' => 'required | email | unique:users',
            'password' => 'required | min:6 | confirmed',
        ]);

        if ($validator->passes()) {

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'User created successfully');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required | email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            if (Auth::attempt(["email" => $request->email, "password" => $request->password], $request->get('remember'))) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {

                return redirect()->route('account.login')->withInput($request->only('email'))->with('error', "Either email/password is incorrect.");
            }
        } else {

            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view("frontend.account.profile", compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | min:3 | max:100',
            'email' => 'required | email | unique:users,email,' . Auth::user()->id . ',id',
            'phone' => 'required ',
        ]);

        if ($validator->passes()) {
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success', "Profile Updated Successfully");

            return response()->json([
                'status' => true,
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', "Logout Successfully!.");
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('frontend.account.order', compact('orders'));
    }

    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->with('orderItems')->where('id', $id)->first();
        $orderCount = OrderItem::where('order_id', $id)->count();
        return view('frontend.account.order_detail', compact('order', 'orderCount'));
    }

    public function wishlists()
    {
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        return view('frontend.account.wishlists', compact('wishlists'));
    }

    public function removeFromWishlist(Request $request)
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();

        if ($wishlist == null) {
            return response()->json(['status' => true, 'message' => "Product Already Removed From Wishlist"]);
        } else {
            $wishlist->delete();
            return response()->json(['status' => true, 'message' => "Product Removed From Wishlist"]);
        }
    }

    public function showChangePassword()
    {
        return view('frontend.account.change_password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required | min:6 |',
            'confirm_password' => 'required | same:new_password',
        ]);

        $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();

        if ($validator->passes()) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Old Password is incorrect'
                ]);
            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            successMessage("Password has been updated");

            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }


    public function userForgotPassword()
    {
        return view('frontend.account.forgot_password');
    }

    public function processForgotPassword(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'email' => 'required | email | exists:users',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $user = User::where('email', $request->email)->first();

        $formData = [
            'token' => $token,
            'user' => $user,
            'subject' => "You are request to change your password"
        ];

        session()->flash('success', "Reset password link has been sent to your email.");

        Mail::to($request->email)->send(new UserResetPassword($formData));

        return redirect()->route('account.userForgotPassword')->with('success', "Reset password link has been sent to your email.");
    }

    public function userResetPassword($token)
    {
        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExist == null) {
            return redirect()->route('account.userForgotPassword');
        }

        return view('frontend.account.update_user_password', compact('tokenExist'));
    }


    public function processUpdatePassword(Request $request)
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
            ],422);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        session()->flash('success', "Your Password has been updated  ");

       
        return response()->json([
            'status' => true,
            'message' => "Your Password has been updated",

        ]);
    }
}
