<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\errorMessage;
use function App\Helpers\sucessMessage;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        if($request->get('table_search')){ 
            $discountCoupens = DiscountCode::where('name', 'like', '%'.$request->get('table_search').'%')->paginate(10); 
        }else{
            $discountCoupens = DiscountCode::paginate(10);
        }
        return view('admin.coupencode.index', compact('discountCoupens'));
        // return view('admin.coupencode.index');
    }

    public function create(){
        return view('admin.coupencode.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required', 
            'type' => 'required',
            'discount_amount' => 'required | numeric', 
            'status' => 'required', 
        ]);

        if($validator->passes()){

            $discountCode = $request->id ? DiscountCode::find($request->id) : new DiscountCode();

            if(!empty($request->starts_at)){
                $now = Carbon::now();
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if($startsAt->lte($now) == true){
                    return response()->json([
                        "status" => false,
                        "error" => ['starts_at' => "Start date can not be less than current date time" ] 
                    ]);
                }
            }

            if(!empty($request->expires_at) && !empty($request->expires_at)){
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if($expiresAt->gt($startsAt) == false){ 
                    return response()->json([
                        "status" => false,
                        "error" => "Expiry date must be greater than Start Date" 
                    ]);
                }
            } 
            
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_users = $request->max_uses_users;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $msg = $request->id ? "Discount coupen  Updated successfully" : "Discount coupen  added successfully";
            sucessMessage($msg);

            return response()->json([
                "status" => true,
            ]);
        } else { 
            return response()->json([
                'status' => false,
                'error'=>$validator->errors()
            ]);
        }

    }

    public function edit($id){
        $coupon = DiscountCode::find($id);
        // dd($coupon);
        if($coupon == null){
            errorMessage("Records not found");
            return redirect()->route('coupon.index');
        }

        return view('admin.coupencode.create', compact('coupon'));
    }
 

    public function destroy(){

    }

}
