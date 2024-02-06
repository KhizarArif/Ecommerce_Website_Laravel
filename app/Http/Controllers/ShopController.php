<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
   public function index(Request $request, $categorySlug = null, $subcategorySlug = null)
    {
        $categorySelected = "";
        $subcategorySelected = "";
        $brandsArray = [];

        $categories = Category::with('subCategories')->orderBy("name", "asc")->where('status', 1)->get();
        $brands = Brand::orderBy("name", "asc")->where('status', 1)->get();
        $productsQuery = Product::with('productImages')->orderBy("id", "desc")->where('status', 1);

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $productsQuery->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subcategorySlug)) {
            $subcategory = SubCategory::where('slug', $subcategorySlug)->first();
            $productsQuery->where('subcategory_id', $subcategory->id);
            $subcategorySelected = $subcategory->id;
        }

        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $productsQuery->whereIn('brand_id', $brandsArray);
        }

        if ($request->has('price_min') && $request->has('price_max')) {
            $productsQuery->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
        }

        if ($request->has('sort')) {
            if ($request->get('sort') == 'price_desc') {
                $productsQuery->orderBy('price', 'desc');
            } elseif ($request->get('sort') == 'price_asc') {
                $productsQuery->orderBy('price', 'asc');
            }
        }

        $products = $productsQuery->paginate(6);

        $data = [
            'priceMax' => (intval($request->get('price_max')) == 0) ? 10000 : intval($request->get('price_max')),
            'priceMin' => intval($request->get('price_min')),
            'sort' => $request->get('sort')
        ];

        return view('frontend.shop', compact('categories', 'brands', 'products', 'categorySelected', 'subcategorySelected', 'brandsArray'))->with($data);
    }

    
    public function product($slug){
        $product = Product::where('slug', $slug)->with('productImages')->first();
        if($product == null){
            abort(404);
        } 
        
        $relatedProducts = [];
        if($product->related_products != ""){
            $productArray = explode(',', $product->related_products); 
            $relatedProducts = Product::whereIn('id', $productArray)->with('productImages')->get(); 
        }   

        return view('frontend.product', compact('product', 'relatedProducts'));


    }
}