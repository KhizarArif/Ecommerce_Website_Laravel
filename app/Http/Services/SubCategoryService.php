<?php

namespace App\Http\Services;

use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Category;
use App\Models\SubCategory;
use Artesaos\SEOTools\Facades\SEOMeta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request; 
use Maatwebsite\Excel\Facades\Excel; 
use Intervention\Image\Drivers\Gd\Driver;


class SubCategoryService
{

    public function index($request)
    { 
        SEOMeta::setTitle('SubCategories');
        if ($request->get('table_search')) {
            $subcategories = SubCategory::with('category')->where('name', 'like', '%' . $request->get('table_search') . '%')->paginate(10);
        } else {
            $subcategories = SubCategory::with('category')->paginate(10);
        }
        return view('admin.subcategory.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.subcategory.create', compact('categories'));
    }

    public function store(Request $request)
    { 
        $subcategory = $request->id > 0 ? SubCategory::find($request->id) : new SubCategory();
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;
        $subcategory->status = $request->status;
        $subcategory->category_id = $request->category_id;
        $subcategory->save();

        
        $successMessage = $request->id > 0 ? 'Category Updated Successfully' : 'Category Added Successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            "status" => true, 
        ]);
    }

    public function edit($id)
    {
        $subcategory = SubCategory::find($id); 
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.subcategory.create', compact('subcategory', 'categories'));

    }
    public function destroy($id)
    {
        $subcategory = SubCategory::find($id);
        $subcategory->delete();
        return response()->json([
            "status" => true,
            "message" => 'Subcategory Deleted Successfully! ',
        ]);

    }

    public function fileImport(Request $request)
    {
        // Excel::import(new CategoryImport, $request->file('file')->store('temp'));
        return back();
    }

    public function fileExport()
    {
        ob_clean();
        ob_start();
        // return Excel::download(new CategoryExport, 'categories.xlsx', true, ['X-Vapor-Base64-Encode' => 'True']);
        return Excel::download(new CategoryExport, 'categories.xlsx', \Maatwebsite\Excel\Excel::XLSX, ['X-Vapor-Base64-Encode' => 'True']);
    }

    public function viewPDF()
    {
        $categories = Category::all();

        $pdf = Pdf::loadView('admin.pdf.pdftable', array('categories' => $categories))
            ->setPaper('a4', 'portrait');

        return $pdf->stream();

    }


    public function downloadPDF()
    {
        $categories = Category::all();

        $pdf = PDF::loadView('admin.pdf.pdftable', array('categories' => $categories))
            ->setPaper('a4', 'portrait');

        return $pdf->download('users-details.pdf');
    }



}
