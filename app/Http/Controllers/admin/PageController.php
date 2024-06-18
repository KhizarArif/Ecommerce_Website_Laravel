<?php

namespace App\Http\Controllers\admin;

use App\Exports\CategoryExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PageController extends Controller
{
    public function index(Request $request)
    {  
        if($request->get('table_search')){ 
            $pages = Page::where('name', 'like', '%'.$request->get('table_search').'%')->paginate(10); 
        }else{
            $pages = Page::paginate(10);
        }
        return view('admin.pages.index', compact('pages'));
    }

    public function create(){
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {   
        $page = $request->id > 0 ?  Page::find($request->id) : new Page();
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->status = $request->status; 
        $page->save(); 
        $successMessage = $request->id > 0 ? 'Page Updated Successfully' : 'Page Added Successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            "status" => true,
        ]);
    }

    public function edit($id)
    {
        $page = Page::find($id);
        return view('admin.pages.create', compact('page'));
    }
    public function destroy($id)
    {  
        $page = Page::find($id); 
        $page->delete(); 

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
