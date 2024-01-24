<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $brandservice;

    public function __construct(BrandService $brandservice)
    {
        $this->brandservice = $brandservice;
    }

    public function index(Request $request)
    {
        return $this->brandservice->index($request);
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store( Request $request)
    {
        return $this->brandservice->store($request);
    }
    public function edit($id)
    {
        return $this->brandservice->edit($id);
    }
    public function destroy($id)
    { 
        return $this->brandservice->destroy( $id);
    }

    public function fileImport(Request $request){
        return $this->brandservice->fileImport($request);
    } 
    public function fileExport(){
        return $this->brandservice->fileExport();
    }

    public function viewPDF(){
        return $this->brandservice->viewPDF();
    }
    public function downloadPDF(){
        return $this->brandservice->downloadPDF();
    }
}
