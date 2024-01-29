<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productservice;

    public function __construct(ProductService $productservice)
    {
        $this->productservice = $productservice;
    }

    public function index(Request $request)
    {
        return $this->productservice->index($request);
    }

    public function create()
    {
        return $this->productservice->create();
    }

    public function store( Request $request)
    {
        return $this->productservice->store($request);
    }
    public function edit($id)
    {
        return $this->productservice->edit($id);
    }
    public function destroy($id)
    { 
        return $this->productservice->destroy( $id);
    }

    public function updateProductImage(Request $request){
        return $this->productservice->updateProductImage($request);
    }

    public function deleteProductImage(Request $request){
        return $this->productservice->deleteProductImage($request);
    }

    public function GetSubCategory(Request $request){
        return $this->productservice->GetSubCategory($request);
    }

    public function fileImport(Request $request){
        return $this->productservice->fileImport($request);
    } 
    public function fileExport(){
        return $this->productservice->fileExport();
    }

    public function viewPDF(){
        return $this->productservice->viewPDF();
    }
    public function downloadPDF(){
        return $this->productservice->downloadPDF();
    }
}
