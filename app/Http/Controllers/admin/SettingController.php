<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\successMessage;

class SettingController extends Controller
{
    public function changeAdminPassword()
    {
        return view('admin.settings.change_admin_password');
    }

    public function updateAdminPassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required | min:6 ',
            'confirm_password' => 'required | same:new_password',
        ]);

        $id = Auth::user()->id;  
 
        $admin = User::select('id', 'password')->where('id', $id)->first();

        if ($validator->passes()) { 
            if (!Hash::check($request->old_password, $admin->password)) {  
                return response()->json([
                    'status' => false,
                    'message' => 'Old Password is incorrect'
                ]);
            }

            User::where('id', $id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            successMessage("Password has been updated");

            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

    }
}
