<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
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

        return view('frontend.checkout');
    }

}
