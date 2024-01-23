<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Services\SubCategoryService;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    protected $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function index(Request $request){
        return $this->subCategoryService->index($request);
    }
 
    public function create()
    {
       return $this->subCategoryService->create();
    }

    public function store(Request $request){
        return $this->subCategoryService->store($request);
    }

    public function edit($id){
        return $this->subCategoryService->edit($id);
    }

    public function destroy($id){
        return $this->subCategoryService->destroy($id);
    }


}
