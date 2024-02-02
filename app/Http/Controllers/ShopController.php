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
        $products = Product::with('productImages')->orderBy("id", "desc")->where('status', 1)->get();

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = Product::where('category_id', $category->id)->with('productImages')->orderBy("id", "desc")->where('status', 1)->get();
            $categorySelected = $category->id;
        }

        if (!empty($subcategorySlug)) {
            $subcategory = SubCategory::where('slug', $subcategorySlug)->first();
            $products = Product::where('subcategory_id', $subcategory->id)->with('productImages')->orderBy("id", "desc")->where('status', 1)->get();
            if($request->get('sort') != ''){
                if($request->get('sort') == 'price_desc'){
                    $products = Product::where('subcategory_id', $subcategory->id)->with('productImages')->orderBy("price", "desc")->where('status', 1)->get();
                }else if($request->get('sort') == 'price_asc'){
                    $products = Product::where('subcategory_id', $subcategory->id)->with('productImages')->orderBy("price", "asc")->where('status', 1)->get();
                }else if($request->get('sort') == 'latest'){
                    $products = Product::where('subcategory_id', $subcategory->id)->with('productImages')->orderBy("id", "desc")->where('status', 1)->get();
                }
            }else{
                $products = Product::where('subcategory_id', $subcategory->id)->with('productImages')->orderBy("id", "desc")->where('status', 1)->get();
            }
            $subcategorySelected = $subcategory->id;
        }

        if(!empty($request->get('brand'))){
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }
        // dd($request->get('price_min'), $request->get('price_max'));
        if($request->get('price_min') !=  '' && $request->get('price_max') !=  ''){
            if($request->get('price_max') == 10000){
                $products = $products->whereBetween('price', [intval($request->get('price_min')),10000]); 
            }else{
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);  
            }     
        }
        
        $data['priceMax'] = (intval($request->get('price_max')) == 0 ) ? 10000 : intval($request->get('price_max')); 
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] =  $request->get('sort');

        return view('frontend.shop', compact('categories', 'brands', 'products', 'categorySelected', 'subcategorySelected', 'brandsArray'))->with($data);

    }
}