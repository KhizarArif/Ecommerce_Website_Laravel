<?php

namespace App\Helpers;

use App\Models\Category;

function getCategories(){ 
    return Category::orderBy('name', 'ASC')->with('subcategories')->where('showHome', 'Yes')->where('status', '1')->orderBy('id', 'DESC')->get();
}

function sucessMessage($message){
    return toastr()->warning($message, "success", ['timeOut' => 2000]);
}
function errorMessage($message){
    return toastr()->error($message, "error", ['timeOut' => 2000]);
}
