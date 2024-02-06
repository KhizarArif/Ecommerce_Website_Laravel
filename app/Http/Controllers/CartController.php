<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

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

        if(Cart::content() -> isNotEmpty()){ 
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
}
