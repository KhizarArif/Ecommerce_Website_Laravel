<?php

namespace App\Http\Services;

use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Maatwebsite\Excel\Facades\Excel;  


class ProductService
{ 
 
    public function index(Request $request)
    {
    $query = Product::latest('id')->with('productImages');

    if ($request->get('table_search')) {
        $query->where('title', 'like', '%' . $request->get('table_search') . '%');
    }

    $products = $query->paginate();

    return view('admin.products.index', compact('products'));
    }


    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get(); 
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {     
        $rules = [
            "title" => "required",
            "slug" => "required ",
            "price" => "required | numeric",
            "sku" => "required ",
            "track_qty" => "required | in:Yes,No ",
            "category_id" => "required | numeric",
            "is_featured" => "required | in:Yes,No ",
        ];
        if(!empty($request->track_qty) && $request->track_qty == "Yes"){
            $rules["qty"] = "required | numeric";
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product = $request->id ? Product::find($request->id) : new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->brand_id = $request->brand_id;
            $product->is_featured = $request->is_featured; 
            $product->save();

            if(!empty($request->image_array)){
                foreach ($request->image_array as  $temp_value_image) { 
                    $tempImageInfo = TempImage::find($temp_value_image); 
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";    
                    $productImage->save();

                    $newImageName = $product-> id . '-' . $productImage->id . '.' . time() . '.' . $ext;
                    $productImage->image = $newImageName;
                    $productImage->save();  

                    // For Large Image  
                    $spath = public_path() . '/temp/' . $tempImageInfo->name; 
                    $dpath = public_path() . '/uploads/product/large/' . $tempImageInfo->name;
                      $manager = new ImageManager(new Driver()); 
                      $image = $manager->read($spath);
                      $image->resize(1400, 900);                
                      $image->save($dpath); 

                    // For Small Image  
                        $dpath = public_path() . '/uploads/product/small/' . $tempImageInfo->name;
                          $manager = new ImageManager(new Driver()); 
                          $image = $manager->read($spath);
                          $image->resize(300, 300);                
                          $image->save($dpath); 
                }
            };
            $successMsg = $request->id ? "Product Updated Successfully!" : "Product Added Successfully!.";
            session()->flash('success', $successMsg);

            return response()->json([
                "status" => true, 
            ]);
        }else{
            return response()->json([
                "status" => false,
                "message" => $validator->errors(),
            ]);
        }

    }


    public function edit($id)
    {
        $product = Product::find($id); 
        $productImages = ProductImage::where('product_id', $product->id)->get();
        $subcategories = SubCategory::where('category_id', $product->category_id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get(); 

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'subcategories', 'productImages'));
    }

    public function destroy($id)
    {  
          Product::find($id)->delete();  

        return response()->json([
            "status" => true,
            "message" => 'success',
        ]);
    }

    // Get Sub categories
    public function GetSubCategory($request)
    { 
        $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        return response()->json([
            'status' => true,
            'subCategories' => $subCategories
        ]);
    }

    // Import Excel File 
    public function fileImport(Request $request) 
    {
        // Excel::import(new CategoryImport, $request->file('file')->store('temp'));
        return back();
    }

    // Export Excel File 
    public function fileExport(){
        ob_clean();
        ob_start();
        // return Excel::download(new CategoryExport, 'categories.xlsx', true, ['X-Vapor-Base64-Encode' => 'True']);
        return Excel::download(new CategoryExport, 'categories.xlsx', \Maatwebsite\Excel\Excel::XLSX, ['X-Vapor-Base64-Encode' => 'True']);
    }

    // View PDF
    public function viewPDF()
    {
        $categories = Category::all();

        $pdf = Pdf::loadView('admin.pdf.pdftable', array('categories' =>  $categories))
        ->setPaper('a4', 'portrait');

        return $pdf->stream();

    }

    // Download Pdf
    public function downloadPDF()
    {
        $categories = Category::all();

        $pdf = PDF::loadView('admin.pdf.pdftable', array('categories' =>  $categories))
        ->setPaper('a4', 'portrait');

        return $pdf->download('users-details.pdf');   
    }



}