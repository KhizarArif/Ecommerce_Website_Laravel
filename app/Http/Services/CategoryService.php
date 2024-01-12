<?php

namespace App\Http\Services;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $category = $request->id > 0 ?  Category::find($request->id) : new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

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
