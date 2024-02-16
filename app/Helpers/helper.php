<?php

namespace App\Helpers;

use App\Models\Category;

function getCategories(){ 
    return Category::orderBy('name', 'ASC')->with('subcategories')->where('showHome', 'Yes')->where('status', '1')->orderBy('id', 'DESC')->get();
}

