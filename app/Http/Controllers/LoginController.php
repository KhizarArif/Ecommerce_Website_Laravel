<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function redirectToGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        dd($user);
    }



    public function stripePost(Request $request) 
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = Customer::create(array(

            "address" => [
                "line1" => "Virani Chowk",
                "postal_code" => "360001",
                "city" => "Rajkot",
                "state" => "GJ",
                "country" => "IN",
            ],

            "email" => "demo@gmail.com",
            "name" => "Hardik Savani",
            "source" => $request->stripeToken 
        ));



        Charge::create([

            "amount" => 100 * 100,

            "currency" => "usd",

            "customer" => $customer->id,

            "description" => "Test payment from itsolutionstuff.com.",

            "shipping" => [

                "name" => "Jenny Rosen",

                "address" => [

                    "line1" => "510 Townsend St",

                    "postal_code" => "98140",

                    "city" => "San Francisco",

                    "state" => "CA",

                    "country" => "US",

                ],

            ]

        ]);



        Session::flash('success', 'Payment successful!');



        return back();
    }
}
