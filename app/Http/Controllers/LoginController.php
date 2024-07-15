<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        try {
            $google_user = Socialite::driver('google')->user();
            
            // Check if a user with the Google ID exists
            $user = User::where('google_id', $google_user->getId())->first();
    
            if (!$user) {
                // Check if a user with the same email exists
                $user = User::where('email', $google_user->getEmail())->first();
    
                if (!$user) {
                    // Create a new user if not found
                    $user = User::create([
                        'name' => $google_user->getName(),
                        'email' => $google_user->getEmail(),
                        'google_id' => $google_user->getId(),
                    ]);
                } else {
                    // Update existing user with google_id if not set
                    $user->update([
                        'google_id' => $google_user->getId(),
                    ]);
                }
            }
    
            // Log in the user
            Auth::login($user);
    
            return redirect()->intended('/');
        } catch (\Throwable $th) {
            dd("Error in Google Authentication: " . $th->getMessage());
        }
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