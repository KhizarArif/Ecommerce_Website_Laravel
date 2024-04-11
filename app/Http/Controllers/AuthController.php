<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        return view("frontend.account.profile");
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

    public function removeFromWishlist(Request $request){
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();

        if($wishlist == null ){
            return response()->json(['status' => true, 'message' => "Product Already Removed From Wishlist"]);
        } else {
            $wishlist->delete();
            return response()->json(['status' => true, 'message' => "Product Removed From Wishlist"]);
        }
    }

}
