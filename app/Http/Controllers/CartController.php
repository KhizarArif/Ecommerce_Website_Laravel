<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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

        $countries = Country::orderBy('name', 'asc')->get();

        return view('frontend.checkout', compact('countries', 'customerAddress'));
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
                'zip'         => $request->zip

            ]
        );

        if($request->payment_method == 'cod'){

            $shipping = 0;
            $discount = 0;
            $subtotal = Cart::subtotal(2 , '.', '');
            $grandtotal = $subtotal + $shipping - $discount;

            $order = new Order;
            $order->subtotal = $subtotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandtotal;

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
               $orderItem = new OrderItem;
               $orderItem->order_id = $order->id;
               $orderItem->product_id = $item->id;
               $orderItem->name = $item->name;
               $orderItem->price = $item->price;
               $orderItem->qty = $item->qty;
               $orderItem->total = $item->price * $item->qty;
               $orderItem->save();
            }

            Cart::destroy();
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

}
