<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class TempImageController extends Controller
{
    public function create(Request $request){
  
        $image = $request->image;

        if (!empty($image) ) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path() . '/temp', $newName);
 
            $spath = public_path() . '/temp/' . $newName; 
              // Creating Image Thumbnail  
            //   try {
                $manager = new ImageManager(new Driver()); 
                $image = $manager->read($spath);
                $image = $image->resize(300, 275);                     
                $image->toJpeg()->save(base_path('public/temp/thumb/'. $newName));
                $save_url = 'temp/thumb/'.$newName;
                $image->save($save_url);
            // } catch (\Intervention\Image\Exceptions\DecoderException $e) { 
            //     dd($e->getMessage());
            // } 
            
            return response()->json([
               "status" => true,
               "image_id" => $tempImage->id,
               "ImagePath" => $save_url,
               "message" => "Image uploaded successfully" 
            ]);
        }
    }
}