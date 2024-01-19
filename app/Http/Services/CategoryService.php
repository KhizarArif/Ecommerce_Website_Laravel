<?php

namespace App\Http\Services;

use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Category;
use App\Models\TempImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel; 



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
        $category = $request->id > 0 ?  Category::find($request->id) : new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->save();

        if (!empty($request->image_id)) {
            $tempImage = TempImage::find($request->image_id);
            
            // Check if $tempImage->name is a file
            if (is_file(public_path('temp/' . $tempImage->name))) {
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '.' . $ext;
                $spath = public_path() . '/temp/' . $tempImage->name;
                $dpath = public_path() . '/uploads/category/' . $newImageName;

                // Check if $spath is a file before attempting to copy
                if (is_file($spath)) {
                    File::copy($spath, $dpath);

                    $category->image = $newImageName;
                    $category->save();
                }
            }
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
        // dd($id);
        $category = Category::find($id)->delete();
              
        return response()->json([
            "message" => 'success',
        ]);
    }

    public function fileImport(Request $request) 
    {
        // Excel::import(new CategoryImport, $request->file('file')->store('temp'));
        return back();
    }

    public function fileExport(){
        ob_clean();
        ob_start();
        // return Excel::download(new CategoryExport, 'categories.xlsx', true, ['X-Vapor-Base64-Encode' => 'True']);
        return Excel::download(new CategoryExport, 'categories.xlsx', \Maatwebsite\Excel\Excel::XLSX, ['X-Vapor-Base64-Encode' => 'True']);
    }

    public function viewPDF()
    {
        $categories = Category::all();

        $pdf = Pdf::loadView('admin.category.index', array('categories' =>  $categories))
        ->setPaper('a4', 'portrait');

        return $pdf->stream();

    }


    public function downloadPDF()
    {
        $categories = Category::all();

        $pdf = PDF::loadView('admin.pdf.pdftable', array('categories' =>  $categories))
        ->setPaper('a4', 'portrait');

        return $pdf->download('users-details.pdf');   
    }



}
