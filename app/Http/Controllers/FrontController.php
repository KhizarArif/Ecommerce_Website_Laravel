<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){

        $featuredProducts = Product::where('is_featured', 'Yes')->orderBy('id', 'desc')->take(8)->get();
        $latestProducts = Product::orderBy('id', 'desc')->where('status', 1)->take(8)->get();

        return view('frontend.home', compact('featuredProducts', 'latestProducts'));
    }
}
