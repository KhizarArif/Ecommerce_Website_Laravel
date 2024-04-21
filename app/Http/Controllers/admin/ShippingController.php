<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\successMessage;

class ShippingController extends Controller
{
    public function create()
    {
        $countries = Country::orderby('name', 'asc')->get();

        $shippingCharges = ShippingCharge::select('shipping_charges.*', 'countries.name')->leftJoin('countries', 'countries.id', 'shipping_charges.country_id')->get();
        return view('admin.shipping.create', compact('countries', 'shippingCharges'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required | numeric',
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('country_id', $request->country)->count();

            if ($count > 0) {
                successMessage("Shipping Already Exists");
                return response()->json([
                    "status" => true,
                    "message" => "Shipping Already Exists"
                ]);
            }

            $shipping = new ShippingCharge();
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();
            successMessage("Shipping charge created successfully");
            return response()->json([
                "status" => true,
                "message" => "Shipping charge created successfully"
            ]);
        } else {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $countries = Country::get();
        $shippingCharges = ShippingCharge::find($id);
        return view('admin.shipping.edit', compact('countries', 'shippingCharges'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'amount' => 'required | numeric',
        ]);

        if ($validator->passes()) {

            $count = ShippingCharge::where('country_id', $request->country)->count(); 
            if ($count > 1) {
                successMessage("Shipping Already Exists");
                return response()->json([
                    "status" => true,
                    "message" => "Shipping Already Exists"
                ]);
            }

            $shipping = ShippingCharge::find($id);
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save(); 

            successMessage("Shipping charge Updated successfully!.");

            return response()->json([
                "status" => true,
                "message" => "Shipping charge Updated successfully!."
            ]);
        } else {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        }
    }

    public function destroy($id){
        $shipping = ShippingCharge::find($id)->delete();
        successMessage("Shipping Deleted Successfully!. ");
        return response()->json([
            "status" => true,
        ]);
    }
    

}
