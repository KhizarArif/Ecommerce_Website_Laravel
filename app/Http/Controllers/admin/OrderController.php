<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');

        if ($request->get('table_search') != "") {
            $orders = $orders->where('users.name', 'like', '%' . $request->table_search . '%');
            $orders = $orders->orWhere('users.email', 'like', '%' . $request->table_search . '%');
            $orders = $orders->orWhere('orders.id', 'like', '%' . $request->table_search . '%');
        }

        $orders = $orders->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function details($id)
    {
        $order = Order::select('orders.*', 'countries.name as countryName')->where('orders.id', $id)->leftJoin('countries', 'countries.id', 'orders.country_id')->with('orderItems')->first();
        // dd($order);
        return view('admin.orders.admin_order_detail', compact('order'));
    }



}
