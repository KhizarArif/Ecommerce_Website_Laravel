<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        $countries = Country::orderby('name', 'asc')->get();

        $shippingCharges = ShippingCharge::leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        return view('admin.shipping.create', compact('countries', 'shippingCharges'));
    }

    public function store(Request $request){ 
        
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required | numeric',
        ]);

        if($validator->passes()){ 
            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();

            return response()->json([
                "status" => true,
                "message" => "Shipping charge created successfully"
            ]);
        } else{ 
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        }
    }

}
