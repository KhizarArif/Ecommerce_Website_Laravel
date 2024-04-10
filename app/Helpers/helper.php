<?php

namespace App\Helpers;

use App\Models\Category;
use App\Models\ProductImage;

function getCategories(){ 
    return Category::orderBy('name', 'ASC')->with('subcategories')->where('showHome', 'Yes')->where('status', '1')->orderBy('id', 'DESC')->get();
}

function successMessage($message){
    return toastr()->warning($message, "success", ['timeOut' => 2000]);
}
function errorMessage($message){
    return toastr()->error($message, "error", ['timeOut' => 2000]);
}

function getProductImage($productId){
    return ProductImage::where('product_id', $productId)->first();
}
