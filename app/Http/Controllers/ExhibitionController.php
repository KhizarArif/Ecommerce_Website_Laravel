<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Category;
use App\Models\ExhibitionImage;
use App\Models\TempImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ExhibitionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('table_search')) {
            $exhibitions = Exhibition::where('name', 'like', '%' . $request->get('table_search') . '%')->paginate(10);
        } else {
            $exhibitions = Exhibition::paginate(10);
        }
        return view('admin.exhibition.index', compact('exhibitions'));
    }

    public function create()
    {
        return view('admin.exhibition.create');
    }

    public function store(Request $request)
    { 
        $exhibition = $request->id > 0 ?  Exhibition::find($request->id) : new Exhibition();
        $exhibition->name = $request->name;
        $exhibition->slug = $request->slug;
        $exhibition->status = $request->status;
        $exhibition->showHome = $request->showHome;
        $exhibition->save();
        $oldImage = $exhibition->image;
        if (!$request->id && !empty($request->image_array)) {
            foreach ($request->image_array as  $temp_value_image) {
                $tempImageInfo = TempImage::find($temp_value_image);
                $extArray = explode('.', $tempImageInfo->name);
                $ext = last($extArray);

                $exhibitionImage = new ExhibitionImage();
                $exhibitionImage->exhibition_id = $exhibition->id;
                $exhibitionImage->image = "NULL";
                $exhibitionImage->save();

                $newImageName = $exhibition->id . '-' . $exhibitionImage->id . '-' . time() . '.' . $ext;
                $exhibitionImage->image = $newImageName;
                $exhibitionImage->save();

                // For Large Image 
                try {
                    $spath = public_path() . '/temp/' . $tempImageInfo->name;
                    $dpath = public_path() . '/uploads/exhibition/large/' . $newImageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($spath);
                    $image->resize(1400, 900);
                    $image->save($dpath);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

                // For Small Image  
                try {
                    $dpath = public_path() . '/uploads/exhibition/small/' . $newImageName;
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($spath);
                    $image->resize(300, 300);
                    $image->save($dpath);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
        };

        $successMessage = $request->id > 0 ? 'Exhibition Updated Successfully' : 'Exhibition Added Successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            "status" => true,
        ]);
    }

    public function edit($id)
    {
        $exhibition = Exhibition::find($id);
        $exhibitionImages = ExhibitionImage::where('exhibition_id', $exhibition->id)->get();
        return view('admin.exhibition.edit', compact('exhibition', 'exhibitionImages'));
    }

    public function destroy($id)
    {
        $exhibition = Exhibition::find($id);

        $exhibitionImages = ExhibitionImage::where('product_id', $exhibition->id)->get();
        if (!empty($exhibitionImages)) {
            foreach ($exhibitionImages as $exhibitionImage) {
                File::delete(public_path() . '/uploads/exhibition/large/' . $exhibitionImage->image);
                File::delete(public_path() . '/uploads/exhibition/small/' . $exhibitionImage->image);
            }
            ExhibitionImage::where('product_id', $exhibition->id)->delete();
        }

        $exhibition->delete();

        session()->flash('success', 'Exhibition Deleted Successfully! ');

        return response()->json([
            "status" => true,
            "message" => 'Exhibition Deleted Successfully! ',
        ]);
    }

    public function updateExhibitionImage(Request $request){
        
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $exhibitionImage = new ExhibitionImage();
        $exhibitionImage->exhibition_id = $request->exhibition_id;
        $exhibitionImage->image = "NULL";
        $exhibitionImage->save();

        $newImageName = $request->exhibition_id . '-' . $exhibitionImage->id . '-' . time() . '.' . $ext;
        $exhibitionImage->image = $newImageName;
        $exhibitionImage->save();

        try {  
            $dpath = public_path() . '/uploads/exhibition/large/' . $newImageName;
              $manager = new ImageManager(new Driver()); 
              $image = $manager->read($sourcePath);
              $image->resize(1400, 900);                
              $image->save($dpath); 
            } catch (\Exception $e) { 
                dd($e->getMessage());
            }

            // For Small Image  
            try {
                $dpath = public_path() . '/uploads/exhibition/small/' . $newImageName;
                  $manager = new ImageManager(new Driver()); 
                  $image = $manager->read($sourcePath);
                  $image->resize(300, 300);                
                  $image->save($dpath); 
            } catch (\Exception $e) { 
                dd($e->getMessage());
            }

            return response()->json([
                "status" => true,
                "image_id" => $exhibitionImage->id,
                "ImagePath" => asset('uploads/exhibition/small/'. $exhibitionImage->image), 
                "message" => 'Image Saved Successfully!',
            ]);

    }



    public function deleteExhibitionImage(Request $request)
    {
        $exhibitionImage = ExhibitionImage::find($request->id);
        File::delete(public_path() . '/uploads/product/large/' . $exhibitionImage->image);
        File::delete(public_path() . '/uploads/product/small/' . $exhibitionImage->image);
        $exhibitionImage->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
