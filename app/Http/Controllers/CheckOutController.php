<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\UserInfo;
use App\Models\OderProduct;
use App\Models\CouponCode;
use App\Models\OderItems;
use App\Models\User;
use Mail;
use App\Mail\CouponMailSend;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CheckOutController extends Controller
{


    // cart view 

    public function cart()
    {
        return view('cart');
    }

    // cart view end

    // check out page view start
    public function CheckOutPage()
    {
        $data = Session::get('cart');
        if ($data) {
            $sum = 0;
            foreach ($data as $item) {
                $single = $item['quantity'] * $item['price'];
                $sum = $sum + $single;
            }
            return view('CheckOutPage', compact('data', 'sum'));
        } else {
            return redirect()->route('product.page');
        }
    }
    // check out page view end

    // AddT o Cart page view start
    public function AddToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "product_id" => $product->id,
                "product_name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
            ];
        }
        session()->put('cart', $cart);
        $cartItems = session()->get('cart');
        return response()->json(['success' => $cartItems]);
    } // end mehtod 
    // AddT o Cart page view end


    // Get Cart Product page view start
    public function GetCartProduct()
    {
        $data = Session::get('cart');
        return response()->json(array(
            'carts' => $data,
        ));
    } //end method 
    public function GetCartTotalPrice()
    {
        $data = Session::get('cart');
        $sum = 0;
        foreach ($data as $item) {
            $single = $item['quantity'] * $item['price'];
            $sum = $sum + $single;
        }
        return response()->json(['total' => $sum]);
    } //end method 
    // Get Cart Product page view end



    // Cart Increment 
    public function CartIncrement($rowId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity']++;
        }
        session()->put('cart', $cart);
        return response()->json('increment');
    } // end mehtod


    // Cart Decrement  
    public function CartDecrement($rowId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity']--;
        }
        session()->put('cart', $cart);
        return response()->json('increment');
    } // end mehtod


    // Remove Cart Product start
    public function RemoveCartProduct($rowId)
    {
        if ($rowId) {
            $cart = session()->get('cart');
            if (isset($cart[$rowId])) {
                unset($cart[$rowId]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
        return response()->json(['success' => $rowId]);
    }
    // Remove Cart Product end


    // Coupon Apply start
    public function CouponApply(Request $request)
    {
        $code = $request->coupon_name;
        $findCode = CouponCode::where('couponCode', $code)->first();
        if ($findCode) {
            $data = Session::get('cart');
            $sum = 0;
            foreach ($data as $item) {
                $single = $item['quantity'] * $item['price'];
                $sum = $sum + $single;
            }
            $discount = session()->get('discount', []);
            if ($discount === []) {
                $discount[] = [
                    "coupon_name" => $request->coupon_name,
                    "total" => $sum - 5,
                ];
                session()->put('discount', $discount);
                $cartItems = session()->get('discount');
                return response()->json([
                    'status' => true,
                    'total' => $cartItems
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => "Already used"
                ]);
            }
        } else {
            return response()->json([
                'error' => true,
                'message' => "Code invalid"
            ]);
        }
    }
    // Coupon Apply end


    // Get Checkout Total Price start
    public function GetCheckoutTotalPrice()
    {
        $data = Session::get('cart');
        $sum = 0;
        foreach ($data as $item) {
            $single = $item['quantity'] * $item['price'];
            $sum = $sum + $single;
        }
        $discount = Session::get('discount');
        if ($discount === null) {
            return response()->json(['total' => $sum]);
        } else {
            return response()->json(['total' => $discount]);
        }
    } //end method 
    // Get Checkout Total Price end

    // Coupon Remove start
    public function CouponRemove()
    {
        Session::flash('discount');
        return response()->json(['total' => "okk"]);
    } //end method 
    // Coupon Remove end

    // User Information start
    public function UserInformation(Request $request)
    {
        if (!$request->firstName) {
            return response()->json([
                'success' => false,
                'message' => "First Name is Required",
            ]);
        }
        if (!$request->lastName) {
            return response()->json([
                'success' => false,
                'message' => "Last Name is Required",
            ]);
        }
        if (!$request->email) {
            return response()->json([
                'success' => false,
                'message' => "Last Name is Required",
            ]);
        }

        if(!$request->password){
            $userInfo = session()->get('userInfo', []);
            $userInfo[] = [
                "userInfoId" => 1,
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "email" => $request->email,
                "phone" => $request->phone,
                "password" => null,
            ];
            session()->put('userInfo', $userInfo);
            $userInfoItem = session()->get('userInfo');
            return response()->json(['success' => $userInfoItem]);
        }else{
            $userInfo = session()->get('userInfo', []);
            $userInfo[] = [
                "userInfoId" => 1,
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "email" => $request->email,
                "phone" => $request->phone,
                "password" => $request->password,
            ];
            session()->put('userInfo', $userInfo);
            $userInfoItem = session()->get('userInfo');
            return response()->json(['success' => $userInfoItem]);
        }

       
    }
    // User Information end

    // Shipping start
    public function Shipping()
    {
        $data = Session::get('cart');
        $userInfo = Session::get('userInfo');
        if ($data && $userInfo) {
            $sum = 0;
            foreach ($data as $item) {
                $single = $item['quantity'] * $item['price'];
                $sum = $sum + $single;
            }

            return view('shipping', compact('data', 'sum', 'userInfo'));
        } else {
            return redirect()->route('product.page');
        }
    }
    // Shipping end

    // User Info Show start
    public function UserInfoShow()
    {
        $data = Session::get('userInfo');
        return response()->json(['userInfo' => $data]);
    }
    // User Info Show end


    // User Info Edit start
    public function UserInfoEdit()
    {
        $userInfo = Session::get('userInfo');
        return response()->json([
            'success' => true,
            'data' => $userInfo,
        ]);
    }
    // User Info Edit end

    // User Info Update start
    public function UserInfoUpdate(Request $request)
    {
        if (!$request->firstName) {
            return response()->json([
                'success' => false,
                'message' => "First Name is Required",
            ]);
        }

        if (!$request->lastName) {
            return response()->json([
                'success' => false,
                'message' => "Last Name is Required",
            ]);
        }
        if (!$request->email) {
            return response()->json([
                'success' => false,
                'message' => "Last Name is Required",
            ]);
        }


        $userInfo = session()->get('userInfo', []);
        if (isset($userInfo[0])) {
            $userInfo[0]['firstName'] = $request->firstName;
            $userInfo[0]['lastName'] = $request->lastName;
            $userInfo[0]['email'] = $request->email;
            $userInfo[0]['phone'] = $request->phone;
            session()->put('userInfo', $userInfo);
            $userInfoItems = session()->get('userInfo');
            return response()->json(['success' => $userInfo[0]['lastName']]);
        }
    } // end mehtod 
    // User Info Update end






    // Shipping Store start
    public function ShippingStore(Request $request)
    {
        if (!$request->address) {
            return response()->json([
                'success' => false,
                'message' => "address  is Required",
            ]);
        }
        if (!$request->city) {
            return response()->json([
                'success' => false,
                'message' => "city is Required",
            ]);
        }
        if (!$request->state) {
            return response()->json([
                'success' => false,
                'message' => "state is Required",
            ]);
        }
        if (!$request->country) {
            return response()->json([
                'success' => false,
                'message' => "country is Required",
            ]);
        }
        $shippingInfo = session()->get('shippingInfo', []);
        $shippingInfo[] = [
            "shippingInfoId" => 1,
            "aptnum" => $request->aptnum,
            "address" => $request->address,
            "city" => $request->city,
            "state" => $request->state,
            "country" => $request->country,
        ];
        session()->put('shippingInfo', $shippingInfo);
        $shippingInfoItem = session()->get('shippingInfo');
        return response()->json(['success' => $shippingInfoItem]);
    }
    // Shipping Store end





    // Summary start
    public function Summary()
    {
        $data = Session::get('cart');
        $userInfo = Session::get('userInfo');
        $shippingInfoItem = session()->get('shippingInfo');
        if ($data && $userInfo && $shippingInfoItem) {
            $sum = 0;
            foreach ($data as $item) {
                $single = $item['quantity'] * $item['price'];
                $sum = $sum + $single;
            }

            return view('summary', compact('data', 'sum', 'userInfo'));
        } else {
            return redirect()->route('product.page');
        }
    }
    // Summary end


    // Shipping Info Show start
    public function ShippingInfoShow()
    {
        $data = Session::get('shippingInfo');
        return response()->json(['shippingInfo' => $data]);
    }
    // Shipping Info Show end

    // Shipping Info Edit start
    public function ShippingInfoEdit()
    {
        $data = Session::get('shippingInfo');
        return response()->json(['shippingInfo' => $data]);
    }
    // Shipping Info Edit end



    // Shipping Info Update start
    public function ShippingInfoUpdate(Request $request)
    {
        if (!$request->address) {
            return response()->json([
                'success' => false,
                'message' => "address  is Required",
            ]);
        }

        if (!$request->city) {
            return response()->json([
                'success' => false,
                'message' => "city is Required",
            ]);
        }
        if (!$request->state) {
            return response()->json([
                'success' => false,
                'message' => "state is Required",
            ]);
        }
        if (!$request->country) {
            return response()->json([
                'success' => false,
                'message' => "country is Required",
            ]);
        }

        $shippingInfo = session()->get('shippingInfo', []);
        if (isset($shippingInfo[0])) {

            $shippingInfo[0]['shippingInfoId'] = 1;
            $shippingInfo[0]['address'] = $request->address;
            $shippingInfo[0]['aptnum'] = $request->aptnum;
            $shippingInfo[0]['city'] = $request->city;
            $shippingInfo[0]['state'] = $request->state;
            $shippingInfo[0]['country'] = $request->country;
            session()->put('shippingInfo', $shippingInfo);
            $shippingInfoItems = session()->get('shippingInfo');
            return response()->json(['success' => $shippingInfoItems]);
        }
    } // end mehtod 
    // Shipping Info Update end



    public function UserOderConfirm(Request $request)
    {



        $shippingInfo = session()->get('shippingInfo', []);
        $cart = Session::get('cart');
        $userInfo = Session::get('userInfo');
        $discount = Session::get('discount');
        
        if ($userInfo[0]['password'] === null) {

            if (Auth::id()) {

                if ($discount === null) {
                    $shippingInfo = session()->get('shippingInfo', []);
                    $cart = Session::get('cart');
                    $userInfo = Session::get('userInfo');
                    $discount = Session::get('discount');

                    $sum = 0;
                    foreach ($cart as $item) {
                        $single = $item['quantity'] * $item['price'];
                        $sum = $sum + $single;
                    }

                    $user = array();
                    $user['userId'] = Auth::id();
                    $user['firstName'] = $userInfo[0]['firstName'];
                    $user['lastName'] = $userInfo[0]['lastName'];
                    $user['phone'] = $userInfo[0]['phone'];
                    $user['email'] = $userInfo[0]['email'];
                    $user['created_at'] = Carbon::now()->addMinutes(15);
                    UserInfo::insert($user);

                    $oder = array();
                    $oder['userId'] = Auth::id();
                    $oder['total'] = $sum;
                    $oder['address'] = $shippingInfo[0]['address'];
                    $oder['aptnum'] = $shippingInfo[0]['aptnum'];
                    $oder['city'] = $shippingInfo[0]['city'];
                    $oder['state'] = $shippingInfo[0]['state'];
                    $oder['country'] = $shippingInfo[0]['country'];
                    $oder['created_at'] = Carbon::now();
                    $getGderId =   OderProduct::insertGetId($oder);

                    foreach ($cart as $item) {
                        $oderItem = array();
                        $oderItem['userId'] = Auth::id();
                        $oderItem['oder_productsId'] = $getGderId;
                        $oderItem['productId'] = $item['product_id'];
                        $oderItem['price'] = $item['price'];
                        $oderItem['qty'] = $item['quantity'];
                        $oderItem['created_at'] = Carbon::now();
                        OderItems::insert($oderItem);
                    }

                    $couponCode = Str::upper(Str::random(10));

                    $coupon = array();
                    $coupon['couponCode'] = $couponCode;
                    $coupon['useremail'] = $userInfo[0]['email'];
                    $coupon['created_at'] = Carbon::now()->addMinutes(15);
                    CouponCode::insert($coupon);
                    Session::flush();

                    return redirect()->route('product.page');
                } else {

                    $user = array();
                    $user['userId'] = Auth::id();
                    $user['firstName'] = $userInfo[0]['firstName'];
                    $user['lastName'] = $userInfo[0]['lastName'];
                    $user['phone'] = $userInfo[0]['phone'];
                    $user['email'] = $userInfo[0]['email'];
                    $user['created_at'] = Carbon::now()->addMinutes(15);
                      UserInfo::insert($user);

                    $oder = array();
                    $oder['userId'] = Auth::id();
                    $oder['total'] = $discount[0]['total'];
                    $oder['address'] = $shippingInfo[0]['address'];
                    $oder['aptnum'] = $shippingInfo[0]['aptnum'];
                    $oder['city'] = $shippingInfo[0]['city'];
                    $oder['state'] = $shippingInfo[0]['state'];
                    $oder['country'] = $shippingInfo[0]['country'];
                    $oder['created_at'] = Carbon::now();
                    $getGderId =   OderProduct::insertGetId($oder);

                    foreach ($cart as $item) {
                        $oderItem = array();
                        $oderItem['userId'] = Auth::id();
                        $oderItem['oder_productsId'] = $getGderId;
                        $oderItem['productId'] = $item['product_id'];
                        $oderItem['price'] = $item['price'];
                        $oderItem['qty'] = $item['quantity'];
                        $oderItem['created_at'] = Carbon::now();
                        OderItems::insert($oderItem);
                    }

                    $couponCode = Str::upper(Str::random(10));

                    $coupon = array();
                    $coupon['couponCode'] = $couponCode;
                    $coupon['useremail'] = $userInfo[0]['email'];
                    $coupon['created_at'] = Carbon::now()->addMinutes(15);
                    CouponCode::insert($coupon);
                    Session::flush();

                    return redirect()->route('product.page');
                }
            } else {
                Session::flush();
                return redirect()->route('register');
            }
        } else {
            $newUser = array();
            $newUser['name'] = $userInfo[0]['firstName'] . ' ' . $userInfo[0]['lastName'];
            $newUser['email'] = $userInfo[0]['email'];
            $newUser['password'] = Hash::make($userInfo[0]['password']);
            $newUser['created_at'] = Carbon::now();
            $userId =  User::insertGetId($newUser);


            if ($discount === null) {

                $shippingInfo = session()->get('shippingInfo', []);
                $cart = Session::get('cart');
                $userInfo = Session::get('userInfo');
                $discount = Session::get('discount');

                $sum = 0;
                foreach ($cart as $item) {
                    $single = $item['quantity'] * $item['price'];
                    $sum = $sum + $single;
                }

                $user = array();
                $user['userId'] = $userId;
                $user['firstName'] = $userInfo[0]['firstName'];
                $user['lastName'] = $userInfo[0]['lastName'];
                $user['phone'] = $userInfo[0]['phone'];
                $user['email'] = $userInfo[0]['email'];
                $user['created_at'] = Carbon::now()->addMinutes(15);
                UserInfo::insert($user);

                $oder = array();
                $oder['userId'] = $userId;
                $oder['total'] = $sum;
                $oder['address'] = $shippingInfo[0]['address'];
                $oder['aptnum'] = $shippingInfo[0]['aptnum'];
                $oder['city'] = $shippingInfo[0]['city'];
                $oder['state'] = $shippingInfo[0]['state'];
                $oder['country'] = $shippingInfo[0]['country'];
                $oder['created_at'] = Carbon::now();
                $getGderId =   OderProduct::insertGetId($oder);

                foreach ($cart as $item) {
                    $oderItem = array();
                    $oderItem['userId'] = $userId;
                    $oderItem['oder_productsId'] = $getGderId;
                    $oderItem['productId'] = $item['product_id'];
                    $oderItem['price'] = $item['price'];
                    $oderItem['qty'] = $item['quantity'];
                    $oderItem['created_at'] = Carbon::now();
                    OderItems::insert($oderItem);
                }

                $couponCode = Str::upper(Str::random(10));

                $coupon = array();
                $coupon['couponCode'] = $couponCode;
                $coupon['useremail'] = $userInfo[0]['email'];
                $coupon['created_at'] = Carbon::now()->addMinutes(15);
                CouponCode::insert($coupon);
                Session::flush();

                return redirect()->route('product.page');
            } else {

                $user = array();
                $user['userId'] = $userId;
                $user['firstName'] = $userInfo[0]['firstName'];
                $user['lastName'] = $userInfo[0]['lastName'];
                $user['phone'] = $userInfo[0]['phone'];
                $user['email'] = $userInfo[0]['email'];
                $user['created_at'] = Carbon::now()->addMinutes(15);
                UserInfo::insert($user);

                $oder = array();
                $oder['userId'] = $userId;
                $oder['total'] = $discount[0]['total'];
                $oder['address'] = $shippingInfo[0]['address'];
                $oder['aptnum'] = $shippingInfo[0]['aptnum'];
                $oder['city'] = $shippingInfo[0]['city'];
                $oder['state'] = $shippingInfo[0]['state'];
                $oder['country'] = $shippingInfo[0]['country'];
                $oder['created_at'] = Carbon::now();
                $getGderId =   OderProduct::insertGetId($oder);

                foreach ($cart as $item) {
                    $oderItem = array();
                    $oderItem['userId'] = $userId;
                    $oderItem['oder_productsId'] = $getGderId;
                    $oderItem['productId'] = $item['product_id'];
                    $oderItem['price'] = $item['price'];
                    $oderItem['qty'] = $item['quantity'];
                    $oderItem['created_at'] = Carbon::now();
                    OderItems::insert($oderItem);
                }

                $couponCode = Str::upper(Str::random(10));
                $coupon = array();
                $coupon['couponCode'] = $couponCode;
                $coupon['useremail'] = $userInfo[0]['email'];
                $coupon['created_at'] = Carbon::now()->addMinutes(15);
                CouponCode::insert($coupon);
                Session::flush();

                return redirect()->route('product.page');
            }
        }
    }


    public function PasswordShow(Request $request)
    {
        $data = $request->createUser;
        return response()->json(['success' => $data]);
    }

    public function PasswordHide(Request $request)
    {
        $data = $request->alreadyUser;
        return response()->json(['success' => $data]);
    }
}
