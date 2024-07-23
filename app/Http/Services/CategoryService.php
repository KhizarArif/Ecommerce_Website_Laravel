<?php

namespace App\Http\Services;

use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Category;
use App\Models\CategoryImage;
use App\Models\TempImage; 
use Artesaos\SEOTools\Facades\SEOMeta; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel; 
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class CategoryService
{ 

    public function index(Request $request)
    {   
        SEOMeta::setTitle('Categories'); 
        $query = Category::latest('id')->with('categoryImages');
        if($request->get('table_search')){ 
            $query->where('name', 'like', '%'.$request->get('table_search').'%')->paginate(10); 
        }else{
            $categories = $query->paginate(10);
        }
        return view('admin.category.index', compact('categories'));
    }


    public function store(Request $request)
    {    
        $category = $request->id > 0 ?  Category::find($request->id) : new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->showHome = $request->showHome;
        $category->save(); 
        
        if (isset($request->id) && $request->id !== '' && !empty($request->image_array)) { 
            foreach ($request->image_array as  $temp_value_image) { 
                $tempImageInfo = TempImage::find($temp_value_image); 
                if($tempImageInfo){
                    $extArray = explode('.', $tempImageInfo->name);
                $ext = last($extArray);
                $categoryImage = $request->id > 0 ? CategoryImage::where('category_id', $category->id)->first() : null;

                if (!$categoryImage) {
                    $categoryImage = new CategoryImage();
                    $categoryImage->category_id = $category->id;
                    $categoryImage->image = "NULL";
                    $categoryImage->save();
                }

             
                $newImageName = $category-> id . '-' . $categoryImage->id . '-' . time() . '.' . $ext;
                $categoryImage->image = $newImageName;
                $categoryImage->save();    
                $spath = public_path() . '/temp/' . $tempImageInfo->name;
                     if (File::exists($spath)){
                    // For Large Image 
                    try {  
                        $spath = public_path() . '/temp/' . $tempImageInfo->name;  
                        $dpath = public_path() . '/uploads/category/large/' . $newImageName;
                        $manager = new ImageManager(new Driver()); 
                        $image = $manager->read($spath);
                        $image->resize(1400, 900);                
                        $image->save($dpath); 
                        } catch (\Exception $e) { 
                            dd("Large Image ",$e->getMessage());
                        }

                        // For Small Image  
                        try { 
                            $dpath = public_path() . '/uploads/category/small/' . $newImageName;
                            $manager = new ImageManager(new Driver()); 
                            $image = $manager->read($spath);
                            $image->resize(300, 300);                
                            $image->save($dpath); 
                        } catch (\Exception $e) { 
                            dd("Small Image ",$e->getMessage());
                        }
                    }
                }
                    
            }
           $category->image = $newImageName;
            $category->save();

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
        $categoryImages = CategoryImage::where('category_id', $category->id)->get(); 
        return view('admin.category.edit', compact('category', 'categoryImages'));
    }
    

    public function destroy($id)
    {  
        $category = Category::find($id);

        $categoryImages = CategoryImage::where('category_id', $category->id)->get();
        if(!empty($categoryImages)){
            foreach ($categoryImages as $categoryImage) {
                File::delete(public_path() . '/uploads/category/large/' . $categoryImages->image);
                File::delete(public_path() . '/uploads/category/small/' . $categoryImages->image); 
            }
            CategoryImage::where('category_id', $category->id)->delete();
        } 
        
        $category->delete(); 

        session()->flash('success', 'Category Deleted Successfully! ');

        return response()->json([
            "status" => true,
            "message" => 'Category Deleted Successfully! ',
        ]);
    }

    public function updateCategoryImage(Request $request){
        // dd($request->all());
        $image = $request->file;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $categoryImage = new CategoryImage();
        $categoryImage->category_id = $request->category_id;
        $categoryImage->image = "NULL";
        $categoryImage->save();

        $newImageName = $request->category_id . '-' . $categoryImage->id . '-' . time() . '.' . $ext;
        $categoryImage->image = $newImageName;
        $categoryImage->save();

        try {  
            $dpath = public_path() . '/uploads/category/large/' . $newImageName;
              $manager = new ImageManager(new Driver()); 
              $image = $manager->read($sourcePath);
              $image->resize(1400, 900);                
              $image->save($dpath); 
            } catch (\Exception $e) { 
                dd($e->getMessage());
            }

            // For Small Image  
            try {
                $dpath = public_path() . '/uploads/category/small/' . $newImageName;
                  $manager = new ImageManager(new Driver()); 
                  $image = $manager->read($sourcePath);
                  $image->resize(300, 300);                
                  $image->save($dpath); 
            } catch (\Exception $e) { 
                dd($e->getMessage());
            }

            return response()->json([
                "status" => true,
                "image_id" => $categoryImage->id,
                "ImagePath" => asset('uploads/category/small/'. $categoryImage->image), 
                "message" => 'Image Saved Successfully!',
            ]);

    }

    public function deleteCategoryImage(Request $request){ 
        $categoryImage = CategoryImage::find($request-> id);
        File::delete(public_path() . '/uploads/category/large/' . $categoryImage->image);
        File::delete(public_path() . '/uploads/category/small/' . $categoryImage->image);
        $categoryImage->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
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