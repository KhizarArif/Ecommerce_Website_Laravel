<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use function App\Helpers\sucessMessage;

class   CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::with('productImages')->find($request->id); 
        
        if(empty($product)){
            return response()->json([
                "status" => false,
                "message" => "Product Not Found"
            ]);
        }

        if(Cart::count() > 0){ 
            $contentCart = Cart::content();
            $productAlreadyExists = false;

            foreach($contentCart as $item ){
                if($item->id == $product->id ){
                    $productAlreadyExists = true;
                }
            }

            if($productAlreadyExists == false){ 
                Cart::add($product->id, $product->title, 1, $product->price, ["productImage" => (!empty($product->productImages))? $product->productImages->first() : '']);
                $status = true;
                $message = $product->title . ' added to Cart';
            } else{
                $status = false;
                $message = $product->title . ' already added to Cart';
            }

        }else{ 
            Cart::add($product->id, $product->title, 1, $product->price, ["productImage" => (!empty($product->productImages))? $product->productImages->first() : '']);
            $status = true;
            $message = $product->title . ' added to Cart';
        }

        return response()->json([
            "status" => $status,
            "message" => $message
        ]);

    }
    public function cart(){
        $contentCart = Cart::content(); 
        return view('frontend.cart', compact('contentCart'));
    }

    public function updateCart(Request $request){  

        $rowId = $request->rowId;
        $qty = $request->qty; 

        $cartInfo = Cart::get($rowId);
        $product = Product::find($cartInfo->id); 

        if($product ->track_qty == 'Yes'){
            if($qty <= $product->qty){
                Cart::update($rowId,$qty);
                $status = true;
                $message = "Cart Updated Successfully!.";
             
            }else{
                $status = false;
                $message = "Request qty($qty) Out of Stock";
               
            }
        }else{
            Cart::update($rowId,$qty);
            $status = true;
            $message = "Cart Updated Successfully!.";           
        }
 
        // Session::flash('success',$message);
    
        return response()->json([
            "status" => $status,
            "message" => $message
        ]);

    }

    public function deleteToCart(Request $request){
        $rowId = $request->rowId;
        $cartInfo = Cart::get($rowId);
        
        if($cartInfo == null){
            $status = false;
            $message = "Product Not Found";
            return response()->json([
                "status" => $status,
                "message" => $message
            ]);
        }

        Cart::remove($rowId);
        $status = true;
        $message = "Product Deleted Successfully!.";
        return response()->json([
            "status" => $status,
            "message" => $message
        ]);
    }

    public function checkout(){

        if(Cart::count() == 0){
            return redirect()->route('front.shop');
        }

        if(Auth::check() == false){

            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');

        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();  
        // Calculate Shipping Charges
       if($customerAddress != ''){
        $userCountry = $customerAddress->country_id;
        $shippingInfo =  ShippingCharge::where('country_id', $userCountry)->first();
        $totalQty = 0;
        $totalShippingCharges = 0;
        $grandTotal = 0;

        foreach(Cart::content() as $item){
            $totalQty += $item->qty;
        }

        $totalShippingCharges = $totalQty * $shippingInfo->amount;
        $grandTotal = Cart::subTotal(2, '.', '') + $totalShippingCharges;
       }
        
        $countries = Country::orderBy('name', 'asc')->get();

        return view('frontend.checkout', compact('countries', 'customerAddress', 'totalShippingCharges', 'grandTotal' ));
    }

    public function processCheckout(Request $request){
         

        $validator = Validator::make($request->all(),[
           'first_name' => 'required | min:5',
           'last_name'  => 'required',
           'email'      => 'required | email',
           'mobile'     => 'required',
           'country' => 'required',
           'address'    => 'required',
           'city'       => 'required',
           'state'      => 'required',
           'zip'        => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ]);
        }

        $user = Auth::user(); 
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id'     => $user->id,
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'email'       => $request->email,
                'mobile'      => $request->mobile,
                'appartment'  => $request->appartment,
                'address'     => $request->address,
                'country_id'     => $request->country,
                'state'       => $request->state,
                'city'        => $request->city,
                'zip'         => $request->zip,

            ]
        );

        if($request->payment_method == 'cod'){

            $subTotal = Cart::subtotal(2, '.', '');
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
            $totalQty = 0;
            $totalShippingCharges = 0;
            $grandTotal = 0;

            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if($shippingInfo != null){
                $totalShippingCharges = $totalQty * $shippingInfo->amount;
                $grandTotal = $subTotal + $totalShippingCharges;
            } else {
                $shippingInfo = ShippingCharge::where('country_id', "rest_of_world")->first();
                $totalShippingCharges = $totalQty * $shippingInfo->amount;
                $grandTotal = $subTotal + $totalShippingCharges;
            }

            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $totalShippingCharges;
            $order->grand_total = $grandTotal;

            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->appartment = $request->appartment;
            $order->address = $request->address;
            $order->country_id = $request->country;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->save();

            foreach(Cart::content() as $item){
               $orderItem = new OrderItem();
               $orderItem->order_id = $order->id;
               $orderItem->product_id = $item->id;
               $orderItem->name = $item->name;
               $orderItem->price = $item->price;
               $orderItem->qty = $item->qty;
               $orderItem->total = $item->price * $item->qty;
               $orderItem->save();
            }

            Cart::destroy();

            sucessMessage("Order Created Successfully");

            return response()->json([
                "message" => "Order Created Successfully",
                "orderId" => $order->id,
                "status" => true,
            ]);

        }

    }

    public function thankyou($id){
        $order = Order::find($id);
        return view('frontend.thankyou', compact('order'));
    }

    public function getShippingAmount(Request $request){ 
        if($request->country_id > 0){
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
            $totalQty = 0;
            $totalShippingCharges = 0;
            $grandTotal = 0;

            foreach(Cart::content() as $item){
                $totalQty += $item->qty;
            }

            if($shippingInfo != null){
                $totalShippingCharges = $totalQty * $shippingInfo->amount;
                $grandTotal = Cart::subtotal(2, '.', '') + $totalShippingCharges;

                return response()->json([
                    "status" => true,
                    "totalShippingCharges" => number_format($totalShippingCharges,2),
                    "grandTotal" => number_format($grandTotal,2)
                ]);

            } else {
                $shippingInfo = ShippingCharge::where('country_id', "rest_of_world")->first();
                $totalShippingCharges = $totalQty * $shippingInfo->amount;
                $grandTotal = Cart::subtotal(2, '.', '') + $totalShippingCharges;

                return response()->json([
                    "status" => true,
                    "totalShippingCharges" => number_format($totalShippingCharges,2),
                    "grandTotal" => number_format($grandTotal,2)
                ]);
            }
            
        } else {

            return response()->json([
                "status" => true,
                "totalShippingCharges" => number_format(0,2),
                "grandTotal" => number_format(Cart::subtotal(2, '.', ''), 2),
            ]);
        }
    }

}
