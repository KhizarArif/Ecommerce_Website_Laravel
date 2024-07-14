<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge; 
use Artesaos\SEOTools\Facades\SEOMeta; 
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Session;

use function App\Helpers\orderEmail;
use function App\Helpers\successMessage;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('productImages')->find($request->id);

        if (empty($product)) {
            return response()->json([
                "status" => false,
                "message" => "Product Not Found"
            ]);
        } 
        $productImage = null;
        if (!empty($request->image_id)) {
            $productImage = $product->productImages->where('id', $request->image_id)->first();
        }

        if (Cart::count() > 0) {
            $contentCart = Cart::content();
            $productAlreadyExists = false;

            foreach ($contentCart as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExists = true;
                }
            }

            if ($productAlreadyExists == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ["productImage" => $productImage]); 
                $status = true;
                $message = $product->title . ' added to Cart';
            } else {
                $status = false;
                $message = $product->title . ' already added to Cart';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ["productImage" => $productImage]); 
            $status = true;
            $message = $product->title . ' added to Cart';
        }

        return response()->json([
            "status" => $status,
            "message" => $message
        ]);
    }



    public function cart()
    {
        SEOMeta::setTitle('Cart Page');
        $contentCart = Cart::content();
        return view('frontend.cart', compact('contentCart'));
    }

    public function updateCart(Request $request)
    {

        $rowId = $request->rowId;
        $qty = $request->qty;

        $cartInfo = Cart::get($rowId);
        $product = Product::find($cartInfo->id);

        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $status = true;
                $message = "Cart Updated Successfully!.";
            } else {
                $status = false;
                $message = "Request qty($qty) Out of Stock";
            }
        } else {
            Cart::update($rowId, $qty);
            $status = true;
            $message = "Cart Updated Successfully!.";
        }

        // Session::flash('success',$message);

        return response()->json([
            "status" => $status,
            "message" => $message
        ]);
    }

    public function deleteToCart(Request $request)
    {
        $rowId = $request->rowId;
        $cartInfo = Cart::get($rowId);

        if ($cartInfo == null) {
            $status = false;
            $message = "Product Not Found";
            return response()->json([
                "status" => $status,
                "message" => $message
            ]);
        }

        Cart::remove($rowId);
        $status = true;
        $message = "Product Deleted Successfully!.";
        return response()->json([
            "status" => $status,
            "message" => $message
        ]);
    }

    public function checkout()
    {  
        SEOMeta::setTitle('Checkout Page'); 
        $discount = 0;
        $subTotal = Cart::subTotal(2, '.', '');
        if (Cart::count() == 0) {
            return redirect()->route('front.shop');
        }

        if (Auth::check() == false) {

            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');
        }
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        $totalQty = 0;
        $totalShippingCharges = 0;
        $grandTotal = 0;

        session()->forget('url.intended');

        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Calculate Shipping Charges
        if ($customerAddress != '') {

            $userCountry = $customerAddress->country_id;
            $shippingInfo =  ShippingCharge::where('country_id', $userCountry)->first();
            if (!$shippingInfo) {
                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
            }

            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalShippingCharges = $totalQty * $shippingInfo->amount;
            $grandTotal = ($subTotal - $discount) + $totalShippingCharges;
        } else {
            $grandTotal = $subTotal - $discount;
            $totalShippingCharges = 0;
        }

        $countries = Country::orderBy('name', 'asc')->get();

        return view('frontend.checkout', compact('countries', 'customerAddress', 'totalShippingCharges', 'grandTotal', 'discount'));
    }

    // public function processCheckout(Request $request)
    // { 
    //     $validator = Validator::make($request->all(), [
    //         'first_name' => 'required | min:5',
    //         'last_name'  => 'required',
    //         'email'      => 'required | email',
    //         'mobile'     => 'required',
    //         'country' => 'required',
    //         'address'    => 'required',
    //         'city'       => 'required',
    //         'state'      => 'required',
    //         'zip'        => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'error' => $validator->errors()
    //         ]);
    //     }

    //     $user = Auth::user();
    //     CustomerAddress::updateOrCreate(
    //         ['user_id' => $user->id],
    //         [
    //             'user_id'     => $user->id,
    //             'first_name'  => $request->first_name,
    //             'last_name'   => $request->last_name,
    //             'email'       => $request->email,
    //             'mobile'      => $request->mobile,
    //             'appartment'  => $request->appartment,
    //             'address'     => $request->address,
    //             'country_id'  => $request->country,
    //             'state'       => $request->state,
    //             'city'        => $request->city,
    //             'zip'         => $request->zip,
    //             'notes'        => $request->notes
    //         ]
    //     );
    //     $totalQty = 0;
    //     $coupenCode = '';
    //     $coupenCodeId = Null;
    //     $discount = 0;

    //     if ($request->payment_method == 'cod') {

    //         $subTotal = Cart::subtotal(2, '.', '');
    //         $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

    //         $totalShippingCharges = 0;
    //         $grandTotal = 0;

    //         foreach (Cart::content() as $item) {
    //             $totalQty += $item->qty;
    //         }

    //         if (session()->has('code')) {
    //             $code = session()->get('code');
    //             if ($code->type == 'percent') {
    //                 $discount = ($code->discount_amount / 100) * $subTotal;
    //             } else {

    //                 $discount = $code->discount_amount;
    //             }

    //             $coupenCodeId = $code->id;
    //             $coupenCode = $code->code;
    //         }

    //         if ($shippingInfo != null) {
    //             $totalShippingCharges = $totalQty * $shippingInfo->amount;
    //             $grandTotal = ($subTotal - $discount) + $totalShippingCharges;
    //         } else {
    //             $shippingInfo = ShippingCharge::where('country_id', "rest_of_world")->first();
    //             $totalShippingCharges = $totalQty * $shippingInfo->amount;
    //             $grandTotal = ($subTotal - $discount) + $totalShippingCharges;
    //         }



    //         $order = new Order();
    //         $order->subtotal = $subTotal;
    //         $order->shipping = $totalShippingCharges;
    //         $order->grand_total = $grandTotal;
    //         $order->coupen_code = $coupenCode;
    //         $order->coupen_code_id = $coupenCodeId;
    //         $order->discount = $discount;

    //         $order->user_id = $user->id;
    //         $order->first_name = $request->first_name;
    //         $order->last_name = $request->last_name;
    //         $order->email = $request->email;
    //         $order->mobile = $request->mobile;
    //         $order->appartment = $request->appartment;
    //         $order->address = $request->address;
    //         $order->country_id = $request->country;
    //         $order->state = $request->state;
    //         $order->city = $request->city;
    //         $order->zip = $request->zip;
    //         $order->notes = $request->notes;
    //         $order->save();
 
    //         foreach (Cart::content() as $item) { 
    //             $orderItem = new OrderItem(); 
    //             $orderItem->order_id = $order->id;
    //             $orderItem->product_id = $item->id;
    //             $orderItem->name = $item->name;
    //             $orderItem->price = $item->price;
    //             $orderItem->qty = $item->qty;
    //             $orderItem->total = $item->price * $item->qty;
    //             $orderItem->save();

    //             $productData = Product::find($item->id);
    //             if ($productData->track_qty == 'Yes') {
    //                 $currentQty = $productData->qty;
    //                 $updatedQty = $currentQty - $item->qty;
    //                 $productData->qty = $updatedQty;
    //                 $productData->save();
    //             }
    //         }

    //         // Send Order Email
    //         orderEmail($order->id, "customer");

    //         Cart::destroy();
    //         session()->forget('code');
    //         successMessage("Order Created Successfully");

    //         return response()->json([
    //             "message" => "Order Created Successfully",
    //             "orderId" => $order->id,
    //             "status" => true,
    //         ]);
    //     }else{
    //         Stripe::setApiKey(env('STRIPE_SECRET'));
    //         $charge = Charge::create([
    //             'amount' => Cart::subtotal() * 100,
    //             'currency' => 'PKR',
    //             'description' => 'Order description',
    //             'source' => $request->stripeToken,
    //         ]);
    //     }
    // }
    
    
//     public function processCheckout(Request $request)
// {
//     // Validate input fields
//     $validator = Validator::make($request->all(), [
//         'first_name' => 'required|min:5',
//         'last_name'  => 'required',
//         'email'      => 'required|email',
//         'mobile'     => 'required',
//         'country'    => 'required',
//         'address'    => 'required',
//         'city'       => 'required',
//         'state'      => 'required',
//         'zip'        => 'required',
//         'stripeToken' => 'required_if:payment_method,stripe',
//         // 'payment_method' => 'required',
//         'stripeToken' => 'required_if:payment_method,stripe',
//         'card_number' => 'required_if:payment_method,stripe|numeric',
//         'card_expiry_month' => 'required_if:payment_method,stripe|numeric|between:1,12',
//         'card_expiry_year' => 'required_if:payment_method,stripe|numeric|digits:4',
//         'card_cvc' => 'required_if:payment_method,stripe|numeric|digits_between:3,4',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'error' => $validator->errors()
//         ]);
//     }



//     // Update or create customer address
//     $user = Auth::user();
//     CustomerAddress::updateOrCreate(
//         ['user_id' => $user->id],
//         [
//             'user_id'    => $user->id,
//             'first_name' => $request->first_name,
//             'last_name'  => $request->last_name,
//             'email'      => $request->email,
//             'mobile'     => $request->mobile,
//             'appartment' => $request->appartment,
//             'address'    => $request->address,
//             'country_id' => $request->country,
//             'state'      => $request->state,
//             'city'       => $request->city,
//             'zip'        => $request->zip,
//             'notes'      => $request->notes
//         ]
//     );

//     $totalQty = 0;
//     $coupenCode = '';
//     $coupenCodeId = null;
//     $discount = 0;

//         if ($request->payment_method == 'stripe') {
//             $grandTotal = 0;
//             $subTotal = Cart::subtotal(2, '.', '');
//             if (session()->has('code')) {
//                 $code = session()->get('code');
//                 if ($code->type == 'percent') {
//                     $discount = ($code->discount_amount / 100) * $subTotal;
//                 } else {
//                     $discount = $code->discount_amount;
//                 }
    
//                 $coupenCodeId = $code->id;
//                 $coupenCode = $code->code;
//             }
//         $stripeToken = $request->stripeToken;
        
//         // Use Stripe's API to charge the card
//         \Stripe\Stripe::setApiKey('your-secret-key');
//         $charge = \Stripe\Charge::create([
//             'amount' => $grandTotal * 100, // Amount in cents
//             'currency' => 'usd',
//             'source' => $stripeToken,
//             'description' => 'Order description',
//         ]);
//         // Check if the charge was successful
//         if ($charge->status != 'succeeded') {
//             return response()->json([
//                 'status' => false,
//                 'error' => 'Stripe payment failed',
//             ]);
//         }
//     }

//     if ($request->payment_method == 'cod' || $request->payment_method == 'stripe') {
//         $subTotal = Cart::subtotal(2, '.', '');
//         $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
//         $totalShippingCharges = 0;
//         $grandTotal = 0;

//         foreach (Cart::content() as $item) {
//             $totalQty += $item->qty;
//         }
        
//         if (session()->has('code')) {
//             $code = session()->get('code');
//             if ($code->type == 'percent') {
//                 $discount = ($code->discount_amount / 100) * $subTotal;
//             } else {
//                 $discount = $code->discount_amount;
//             }

//             $coupenCodeId = $code->id;
//             $coupenCode = $code->code;
//         }

//         if ($shippingInfo != null) {
//             $totalShippingCharges = $totalQty * $shippingInfo->amount;
//             $grandTotal = ($subTotal - $discount) + $totalShippingCharges;
//         } else {
//             $shippingInfo = ShippingCharge::where('country_id', "rest_of_world")->first();
//             $totalShippingCharges = $totalQty * $shippingInfo->amount;
//             $grandTotal = ($subTotal - $discount) + $totalShippingCharges;
//         }

//         // Create order
//         $order = new Order();
//         $order->subtotal = $subTotal;
//         $order->shipping = $totalShippingCharges;
//         $order->grand_total = $grandTotal;
//         $order->coupen_code = $coupenCode;
//         $order->coupen_code_id = $coupenCodeId;
//         $order->discount = $discount;
//         $order->user_id = $user->id;
//         $order->first_name = $request->first_name;
//         $order->last_name = $request->last_name;
//         $order->email = $request->email;
//         $order->mobile = $request->mobile;
//         $order->appartment = $request->appartment;
//         $order->address = $request->address;
//         $order->country_id = $request->country;
//         $order->state = $request->state;
//         $order->city = $request->city;
//         $order->zip = $request->zip;
//         $order->notes = $request->notes;
//         // $order->payment_method = $request->payment_method;
//         $order->save();

//         // Create order items
//         foreach (Cart::content() as $item) {
//             $orderItem = new OrderItem();
//             $orderItem->order_id = $order->id;
//             $orderItem->product_id = $item->id;
//             $orderItem->name = $item->name;
//             $orderItem->price = $item->price;
//             $orderItem->qty = $item->qty;
//             $orderItem->total = $item->price * $item->qty;
//             $orderItem->save();

//             // Update product quantity
//             $productData = Product::find($item->id);
//             if ($productData->track_qty == 'Yes') {
//                 $currentQty = $productData->qty;
//                 $updatedQty = $currentQty - $item->qty;
//                 $productData->qty = $updatedQty;
//                 $productData->save();
//             }
//         }

//         // Send Order Email
//         orderEmail($order->id, "customer");

//         // Clear cart and session
//         Cart::destroy();
//         session()->forget('code');

//         if ($request->payment_method == 'cod') {
//             successMessage("Order Created Successfully");

//             return response()->json([
//                 "message" => "Order Created Successfully",
//                 "orderId" => $order->id,
//                 "status" => true,
//             ]);
//         } 
//     }

//     // $post_data =  array(
//     //     "pp_Version" 			=> Config::get('constants.jazzcash.VERSION'),
//     //     "pp_TxnType" 			=> "MWALLET",
//     //     "pp_Language" 			=> Config::get('constants.jazzcash.LANGUAGE'),
//     //     "pp_MerchantID" 		=> Config::get('constants.jazzcash.MERCHANT_ID'),
//     //     "pp_SubMerchantID" 		=> "",
//     //     "pp_Password" 			=> Config::get('constants.jazzcash.PASSWORD'),
//     //     "pp_BankID" 			=> "TBANK",
//     //     "pp_ProductID" 			=> "RETL",
//     //     // "pp_TxnRefNo" 			=> $pp_TxnRefNo,
//     //     // "pp_Amount" 			=> $pp_Amount,
//     //     "pp_TxnCurrency" 		=> Config::get('constants.jazzcash.CURRENCY_CODE'),
//     //     // "pp_TxnDateTime" 		=> $pp_TxnDateTime,
//     //     "pp_BillReference" 		=> "billRef",
//     //     "pp_Description" 		=> "Description of transaction",
//     //     // "pp_TxnExpiryDateTime" 	=> $pp_TxnExpiryDateTime,
//     //     "pp_ReturnURL" 			=> Config::get('constants.jazzcash.RETURN_URL'),
//     //     "pp_SecureHash" 		=> "",
//     //     "ppmpf_1" 				=> "1",
//     //     "ppmpf_2" 				=> "2",
//     //     "ppmpf_3" 				=> "3",
//     //     "ppmpf_4" 				=> "4",
//     //     "ppmpf_5" 				=> "5",
//     // );
//     // $pp_SecureHash = $this->get_SecureHash($post_data);
//     // $post_data['pp_SecureHash'] = $pp_SecureHash;

//     // $values = array(
//     //     'TxnRefNo' => $post_data['pp_TxnRefNo'],
//     //     'Amount' => $post_data['pp_Amount'],
//     //     'description' => $post_data['pp_Description'],
//     //     'status' => 'pending'
//     // );


    
//     // Session::put('post_data', $post_data);

//     return response()->json([
//         'status' => false,
//         'error' => 'Invalid payment method',
//     ]);
// }

public function processCheckout(Request $request)
{ 

    // Validate input fields
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|min:5',
        'last_name'  => 'required',
        'email'      => 'required|email',
        'mobile'     => 'required',
        'country'    => 'required',
        'address'    => 'required',
        'city'       => 'required',
        'state'      => 'required',
        'zip'        => 'required', 
        // 'payment_method' => 'required|in:cod,stripe',
        'stripeToken' => 'required_if:payment_method,stripe',
        // 'card_number' => 'required_if:payment_method,stripe|numeric',
        // 'card_expiry_month' => 'required_if:payment_method,stripe|numeric|between:1,12',
        // 'card_expiry_year' => 'required_if:payment_method,stripe|numeric|digits:4',
        // 'card_cvc' => 'required_if:payment_method,stripe|numeric|digits_between:3,4',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'error' => $validator->errors()
        ]);
    }

    // Update or create customer address
    $user = Auth::user();
    CustomerAddress::updateOrCreate(
        ['user_id' => $user->id],
        [
            'user_id'    => $user->id,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'mobile'     => $request->mobile,
            'appartment' => $request->appartment,
            'address'    => $request->address,
            'country_id' => $request->country,
            'state'      => $request->state,
            'city'       => $request->city,
            'zip'        => $request->zip,
            'notes'      => $request->notes
        ]
    );

    $totalQty = 0;
    $coupenCode = '';
    $coupenCodeId = null;
    $discount = 0;

    $subTotal = Cart::subtotal(2, '.', '');

    if (session()->has('code')) {
        $code = session()->get('code');
        if ($code->type == 'percent') {
            $discount = ($code->discount_amount / 100) * $subTotal;
        } else {
            $discount = $code->discount_amount;
        }

        $coupenCodeId = $code->id;
        $coupenCode = $code->code;
    }

  

    // Handle Stripe Payment
    if ($request->payment_method == 'stripe') {
        Stripe::setApiKey('sk_test_51McDOELy0oAF9eHjXvu3NgWbqnQTD67nDUh57RelzEprX2AiEQ4mQtuThooSAMA8F40WACGca39mX1fH57H5AtDS000GCj3aUV');
       
         
          $minimumChargeAmountInPKR = 110; 
          if (($subTotal - $discount) < $minimumChargeAmountInPKR) {
              return response()->json([
                  'status' => false,
                  'error' => 'Order amount is too low to process payment. Please add more items to your cart.',
              ]);
          }
        try {
         

            $charge = Charge::create([  
                'amount' => ($subTotal - $discount) * 100, 
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Order description',
            ]); 

            Log::info('Stripe Charge:', $charge->toArray());
dd($charge->toArray());
            if ($charge->status != 'succeeded') {
                return response()->json([
                    'status' => false,
                    'error' => 'Stripe payment failed',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Stripe Error:', ['error' => $e->getMessage()]);
            dd($e->getMessage()); 
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ]);
        } 
    }


    // Calculate shipping charges and grand total
    $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();
    $totalShippingCharges = 0;

    foreach (Cart::content() as $item) {
        $totalQty += $item->qty;
    }

    if ($shippingInfo != null) {
        $totalShippingCharges = $totalQty * $shippingInfo->amount;
    } else {
        $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();
        $totalShippingCharges = $totalQty * $shippingInfo->amount;
    }

    $grandTotal = ($subTotal - $discount) + $totalShippingCharges;

    // Create order
    $order = new Order();
    $order->subtotal = $subTotal;
    $order->shipping = $totalShippingCharges;
    $order->grand_total = $grandTotal;
    $order->coupen_code = $coupenCode;
    $order->coupen_code_id = $coupenCodeId;
    $order->discount = $discount;
    $order->user_id = $user->id;
    $order->first_name = $request->first_name;
    $order->last_name = $request->last_name;
    $order->email = $request->email;
    $order->mobile = $request->mobile;
    $order->appartment = $request->appartment;
    $order->address = $request->address;
    $order->country_id = $request->country;
    $order->state = $request->state;
    $order->city = $request->city;
    $order->zip = $request->zip;
    $order->notes = $request->notes;
    $order->payment_method = $request->payment_method;
    $order->save();

    // Create order items
    foreach (Cart::content() as $item) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $item->id;
        $orderItem->name = $item->name;
        $orderItem->price = $item->price;
        $orderItem->qty = $item->qty;
        $orderItem->total = $item->price * $item->qty;
        $orderItem->save();

        // Update product quantity
        $productData = Product::find($item->id);
        if ($productData->track_qty == 'Yes') {
            $currentQty = $productData->qty;
            $updatedQty = $currentQty - $item->qty;
            $productData->qty = $updatedQty;
            $productData->save();
        }
    }

    // Send Order Email
    orderEmail($order->id, 'customer');

    // Clear cart and session
    Cart::destroy();
    session()->forget('code');


    return response()->json([
        'message' => 'Order Created Successfully',
        'orderId' => $order->id,
        'status' => true,
    ]);
}


// private function get_SecureHash($data_array)
// 	{
// 		ksort($data_array);
		
// 		$str = '';
// 		foreach($data_array as $key => $value){
// 			if(!empty($value)){
// 				$str = $str . '&' . $value;
// 			}
// 		}
		
// 		$str = Config::get('constants.jazzcash.INTEGERITY_SALT').$str;
		
// 		$pp_SecureHash = hash_hmac('sha256', $str, Config::get('constants.jazzcash.INTEGERITY_SALT'));
		
		
		
// 		return $pp_SecureHash;
// 	}
    public function thankyou($id)
    { 
        SEOMeta::setTitle('Thankyou Page');
        $order = Order::find($id); 
        return view('frontend.thankyou', compact('order'));
    }

    public function getShippingAmount(Request $request)
    {
        $discount = 0;
        $subTotal = Cart::subtotal(2, '.', '');
        $discountString = '';

        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {

                $discount = $code->discount_amount;
            }

            $discountString = ' <div class="mt-4 d-flex justify-content-between border border-5 align-items-center" id="remove-coupen"> <div>
                                        <strong> ' . session()->get('code')->code . ' </strong>
                                        is applied! <br> <small> Art_wings Coupen </small>
                                    </div>
                                    <a class="btn btn-sm " id="remove-discount">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>';
        }

        if ($request->country_id > 0) {
            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
            $totalQty = 0;
            $totalShippingCharges = 0;
            $grandTotal = 0;


            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $totalShippingCharges = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharges;

                return response()->json([
                    "status" => true,
                    "totalShippingCharges" => number_format($totalShippingCharges, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    "grandTotal" => number_format($grandTotal, 2)
                ]);
            } else {
                $shippingInfo = ShippingCharge::where('country_id', "rest_of_world")->first();
                $totalShippingCharges = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharges;

                return response()->json([
                    "status" => true,
                    "totalShippingCharges" => number_format($totalShippingCharges, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    "grandTotal" => number_format($grandTotal, 2)
                ]);
            }
        } else {

            return response()->json([
                "status" => true,
                "totalShippingCharges" => number_format(0, 2),
                'discount' => $discount,
                'discountString' => $discountString,
                "grandTotal" => number_format(($subTotal - $discount), 2),
            ]);
        }
    }

    public function applyDiscount(Request $request)
    {
        $code = DiscountCode::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                "status" => false,
                "message" => "Invalid Coupen Code."
            ]);
        }

        // Check Date is valid or not
        $now = Carbon::now();
        if ($code->starts_at != "") {
            $startsDate = Carbon::createFromFormat("Y-m-d H:i:s", $code->starts_at);

            if ($now->lt($startsDate)) {
                return response()->json([
                    "status" => false,
                    "message" => "Start date must be less than current date"
                ]);
            }
        }

        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat("Y-m-d H:i:s", $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    "status" => false,
                    "message" => "End Date Must be greater than current date"
                ]);
            }
        }

        if ($code->max_uses > 0) {
            $coupenUsed = Order::where("coupen_code_id", $code->id)->count();
            if ($coupenUsed > $code->max_uses) {
                return response()->json([
                    "status" => false,
                    "message" => "Coupen Code limit exceeded"
                ]);
            }
        }

        if ($code->max_uses_users > 0) {
            $coupenUsed = Order::where(["coupen_code_id" => $code->id, "user_id" => Auth::user()])->count();
            if ($coupenUsed > $code->max_uses_users) {
                return response()->json([
                    "status" => false,
                    "message" => "Coupen Code Already Exists"
                ]);
            }
        }

        session()->put('code', $code);
        return $this->getShippingAmount($request);
    }

    public function removeCoupen(Request $request)
    {
        session()->forget('code');
        return $this->getShippingAmount($request);
    }
}
