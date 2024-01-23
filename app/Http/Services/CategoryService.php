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
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class   CategoryService
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

        $oldImage = $category->image;

        if (!empty($request->image_id)) {
            $tempImage = TempImage::find($request->image_id); 

                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray); 

                $newImageName = hexdec(uniqid()) . '.' . $ext;
                $spath = public_path() . '/temp/' . $tempImage->name;
                $dpath = public_path() . '/uploads/category/' . $newImageName;
                File::makeDirectory(public_path() . '/uploads/category/', 0755, true, true);
                File::copy($spath, $dpath);
                

                 // Creating Image Thumbnail  
                 try {
                    $manager = new ImageManager(new Driver()); 
                    $image = $manager->read($spath);
                    $image = $image->resize(370, 246);                     
                    $image->toJpeg()->save(base_path('public/uploads/category/thumb/'. $newImageName));
                    $save_url = 'uploads/category/'.$newImageName;
                    $image->save($save_url);
                } catch (\Intervention\Image\Exceptions\DecoderException $e) {
                    // Log or handle the exception
                    dd($e->getMessage());
                }
                

                 $category->image = $newImageName;
                $category->save(); 

                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/category/' . $oldImage);

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
    public function destroy($request, $id)
    {  
        $category = Category::find($id);

        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);
        
        $category->delete(); 

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
