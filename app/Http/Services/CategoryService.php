<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;

class CategoryService
{
    public function index(Request $request)
    { 
        if($request->get('table_search')){
            $categories = Category::where('name', 'like', '%'.$request->get('table_search').'%')->paginate(10); 
        }else{
            $categories = Category::paginate(10);
        }
        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $category = $request->id > 0 ?  Category::find($request->id) : new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        if(!empty($request->image_id)){
            $tempImage = TempImage::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);

            $newImageName = $category->id.'.'.$ext;
            
        }

        $successMessage = $request->id > 0 ? 'Category Updated Successfully' : 'Category Added Successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            "status" => true,
        ]);
    }

    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.create', compact('category'));
    }
    public function destroy($id)
    {
        $category = Category::find($id)->delete();
        session()->flash('success', "Category deleted successfully");
        return back();
    }
}
