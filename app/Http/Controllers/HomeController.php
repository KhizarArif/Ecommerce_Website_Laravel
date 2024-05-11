<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    { 
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalUsers = User::where('status', 1)->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        // This Month Revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');

        $thisMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)->sum('grand_total');

        // Last Month Revenue
        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $lastMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastMonthStartDate)
            ->whereDate('created_at', '<=', $lastMonthEndDate)->sum('grand_total');

        // Last 30 Days Revenue
        $lastThirtyDaysStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $lastThirtyDaysRevenue = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastThirtyDaysStartDate)
            ->whereDate('created_at', '<=', $currentDate)->sum('grand_total');

        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');

        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get(); 
        foreach ($tempImages as $tempImage) {
            $path = public_path('/temp/' . $tempImage->name); 
            $thumbPath = public_path('/temp/thumb/' . $tempImage->name); 

            if (file_exists($path)) {
                File::delete($path);
            }

            if (file_exists($thumbPath)) {
                File::delete($thumbPath);
            }
        }

        return view(
            "admin.dashboard",
            compact(
                'totalOrders',
                'totalProducts',
                'totalUsers',
                'totalRevenue',
                'thisMonthRevenue',
                'lastMonthRevenue',
                'lastThirtyDaysRevenue',
                'lastMonthName'
            )
        );
    }

    public function logout()
    {

        Session::flush();
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
