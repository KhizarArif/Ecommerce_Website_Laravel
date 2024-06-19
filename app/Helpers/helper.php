<?php

namespace App\Helpers;

use App\Jobs\CompleteOrderPlaceJob; 
use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
    return Category::orderBy('name', 'ASC')->with('subcategories')->where('showHome', 'Yes')->where('status', '1')->orderBy('id', 'DESC')->get();
}

function successMessage($message)
{
    return toastr()->warning($message, "success", ['timeOut' => 2000]);
}
function errorMessage($message)
{
    return toastr()->error($message, "error", ['timeOut' => 2000]);
}

function getProductImage($productId)
{
    return ProductImage::where('product_id', $productId)->first();
}


function orderEmail($orderId, $userType = "customer")
{ 
    $order = Order::where('id', $orderId)->with(['orderItems.orderProductImages'])->first(); 
    if ($userType == "customer") {
        $subject = "Thank You For Shopping With Us";
        $email = $order->email;
    } else {
        $subject = "You Received A New Order";
        $email = env("ADMIN_EMAIL"); 
    }


    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType,
        'email' => $email
    ];


    dispatch(new CompleteOrderPlaceJob($mailData));
    // dd(CompleteOrderPlaceJob::dispatch($mailData));
    // CompleteOrderPlaceJob::dispatch($mailData);

    // Mail::to($email)->send(new OrderEmail($mailData));
}
