<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        return $this->categoryService->index($request);
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store( Request $request)
    {
        return $this->categoryService->store($request);
    }
    public function edit($id)
    {
        return $this->categoryService->edit($id);
    }
    public function destroy(Request $request, $id)
    { 
        return $this->categoryService->destroy($request->id, $id);
    }

    public function fileImport(Request $request){
        return $this->categoryService->fileImport($request);
    } 
    public function fileExport(){
        return $this->categoryService->fileExport();
    }

    public function viewPDF(){
        return $this->categoryService->viewPDF();
    }
    public function downloadPDF(){
        return $this->categoryService->downloadPDF();
    }

}

