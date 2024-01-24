<?php

namespace App\Http\Services;

use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\TempImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel; 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class BrandService
{ 

    public function index(Request $request)
    {  
        if($request->get('table_search')){ 
            $brands = Brand::where('name', 'like', '%'.$request->get('table_search').'%')->paginate(10); 
        }else{
            $brands = Brand::paginate(10);
        }
        return view('admin.brands.index', compact('brands'));
    }


    public function store(Request $request)
    {  
        $brand = $request->id > 0 ?  Brand::find($request->id) : new Brand();
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();
 
        $successMessage = $request->id > 0 ? 'Brand Updated Successfully' : 'Brand Added Successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            "status" => true,
        ]);
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brands.create', compact('brand'));
    }
    public function destroy($id)
    {  
          Brand::find($id)->delete();  

        return response()->json([
            "status" => true,
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

        $pdf = Pdf::loadView('admin.pdf.pdftable', array('categories' =>  $categories))
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
