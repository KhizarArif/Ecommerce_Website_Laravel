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

    public function index()
    {
        return $this->categoryService->index();
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryService->store($request);
    }
    public function edit($id)
    {
        return $this->categoryService->edit($id);
    }
    public function destroy($id)
    {
        return $this->categoryService->destroy($id);
    }
}
