<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use Artesaos\SEOTools\Facades\SEOMeta; 
use Illuminate\Http\Request;

use function App\Helpers\orderEmail;
use function App\Helpers\successMessage;

class OrderController extends Controller
{
    public function index(Request $request)
    { 
        SEOMeta::setTitle('Orders'); 
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email'); 
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');

        if ($request->get("table_search") != "") {
            $orders = $orders->where('users.name', 'like', '%' . $request->get("table_search") . '%');
            $orders = $orders->orWhere('users.email', 'like', '%' . $request->get("table_search") . '%');
            $orders = $orders->orWhere('orders.id', 'like', '%' . $request->get("table_search") . '%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::select('orders.*', 'countries.name as countryName')->where('orders.id', $id)->leftJoin('countries', 'countries.id', 'orders.country_id')->with('orderItems')->first();
        return view('admin.orders.detail', compact('order'));
    }

    public function changeOrderStatus(Request $request, $id)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipping_date = $request->shipping_date;
        $order->save();
        $message = "Order Status Updated Successfully";
        successMessage($message);
        return response()->json(['status' => true, 'message' => $message]);
    }


    public function sendEmailInvoice(Request $request, $id)
    { 
        orderEmail($id, $request->userType);
        $message = "Order email invoice sent Successfully";
        successMessage($message);
        return response()->json(['status' => true, 'message' => $message]);
    }
}
