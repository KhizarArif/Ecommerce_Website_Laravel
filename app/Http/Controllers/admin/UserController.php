<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Exports\CategoryExport;
use App\Models\Category;
use App\Models\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    public function index(Request $request)
    {
        SEOMeta::setTitle('Users');
        if ($request->get('table_search')) {
            $users = User::where('name', 'like', '%' . $request->get('table_search') . '%')->paginate(10);
        } else {
            $users = User::paginate(10);
        }
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $user = $request->id > 0 ?  User::find($request->id) : new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->phone = $request->phone; 
        $user->status = $request->status;
        $user->save();



        $successMessage = $request->id > 0 ? 'User Updated Successfully' : 'User Added Successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            "status" => true,
        ]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.create', compact('user'));
    }
    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

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

    public function fileExport()
    {
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
