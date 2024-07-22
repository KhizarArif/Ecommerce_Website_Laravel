<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Wishlist;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Home');
        $featuredProducts = Product::where('is_featured', 'Yes')->orderBy('id', 'desc')->take(10)->get();
        $productAllImages = ProductImage::all(); 
        $productImageCount = $productAllImages->count();
        $latestProducts = Product::orderBy('id', 'desc')->where('status', 1)->take(10)->get();
        $exhibitions = Exhibition::where('status', 1)->with('exhibitionImages')->get();  

      
        return view('frontend.home', compact('featuredProducts', 'latestProducts', 'exhibitions', 'productAllImages', 'productImageCount'));
    }

    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {
            session(['url.intended' => url()->previous()]);
            return response()->json(['status' => false, 'message' => "Please Login First"]);
        }

        $product = Product::findOrFail($request->id);

        if ($product->title == null) {
            return response()->json(['status' => false, 'message' => '<div> Product not Found"</div>']);
        }

        $wishlistExists = Wishlist::where('id', $request->id)->exists();
        if ($wishlistExists) {
            $message = '<div> <strong> "' . $product->title . '" </strong>  Already Added To Wishlist. </div>';
        } else {
            $message = '<div> <strong> "' . $product->title . '" </strong>  Added To Wishlist. </div>';
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,

            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );

        return response()->json(['status' => true, 'message' => $message]);
    }

    public function allCategories($id){ 
        $deCryptId = decrypt($id);
        dd($deCryptId);
        // $allProducts = 
    }

    
}
