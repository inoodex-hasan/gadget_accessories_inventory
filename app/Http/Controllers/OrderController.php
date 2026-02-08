<?php

namespace App\Http\Controllers;

use Validator;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderItem;
use App\Mail\PlaceOrderMail;
use App\Models\Admin\Toping;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\ProductTag;
use App\Models\Admin\ProductSize;
use App\Mail\sendStatusChangeMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\sendPaymentStatusChangeMail;
use Spatie\Permission\Models\Role;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {

        // return $request->all();

        $validation = Validator::make($request->all(), [
            'createaccount' => 'nullable|numeric',
            'account_password' => 'required_if:createaccount,1',
            'billing_first_name' => 'required',
            'billing_phone' => 'required',
            'billing_alternative_phone' => 'nullable|numeric',
            'billing_email' => 'required|email',
            'billing_state' => 'required',
            'billing_area' => 'required',
            'billing_address_1' => 'required',
            'payment_method' => 'required',
            'order_comments' => 'nullable',
            'ship_to_different_address' => 'nullable|numeric',
            'shipping_first_name' => 'required_if:ship_to_different_address,1',
            'shipping_phone' => 'required_if:ship_to_different_address,1',
            'shipping_alternative_phone' => 'nullable|numeric',
            'shipping_email' => 'nullable|email',
            'shipping_state' => 'required_if:ship_to_different_address,1',
            'shipping_area' => 'required_if:ship_to_different_address,1',
            'shipping_address_1' => 'required_if:ship_to_different_address,1',
        ]);

        if ($validation->fails() || !cartCount() || !isPermitedForOrder()) {

            // dd($validation->errors());
            return redirect()->back()->withErrors($validation)->withInput()->with(['error' => getNotify(4),'error_code' => 'add',]);
        }

        $user = auth()->user();

        if (!$user) {
            $user = User::where('email', $request->billing_email)->first();

            if ($user) {
                if ($user->is_guest == '0' && $request->createaccount == '1') {
                    return redirect()->back()->with(['error' => getNotify(4)]);
                }

                if ($user->is_guest == '1' && $request->createaccount == '1') {
                    $user->password = Hash::make($request->account_password);
                    $user->is_guest = '0';
                    $user->save();
                }
            } else {
                $user = new User;
                $user->name = $request->billing_first_name;
                $user->phone = $request->billing_phone;
                $user->email = $request->billing_email;
                $user->is_guest = $request->createaccount == '1' ? '0' : '1';
                if ($request->createaccount == '1') {
                    $user->password = Hash::make($request->account_password);
                }
                $user->save();
                $role = Role::where('name', 'Customer')->first();
                $user->assignRole($role);
            }

            if ($request->createaccount == '1') {
                $credentials = [
                    'email' => $request->input('billing_email'),
                    'password' => $request->input('account_password')
                ];
                if (!Auth::attempt($credentials)) {
                    return redirect()->back()->with(['error' => getNotify(4)]);
                }
            }
        }

        $billing = new Address;
        $billing->customer_id = $user->id;
        $billing->name = $request->billing_first_name;
        $billing->phone = $request->billing_phone;
        $billing->email = $request->billing_email;
        $billing->alternative_phone = $request->billing_alternative_phone;
        $billing->state = $request->billing_state;
        $billing->area = $request->billing_area;
        $billing->address_details = $request->billing_address_1;
        $billing->address_type = '1';
        $billing->save();

        $shipping = new Address;
        $shipping->customer_id = $user->id;
        $shipping->name = $request->billing_first_name;
        $shipping->phone = $request->billing_phone;
        $shipping->email = $request->billing_email;
        $shipping->alternative_phone = $request->billing_alternative_phone;
        $shipping->state = $request->billing_state;
        $shipping->area = $request->billing_area;
        $shipping->address_details = $request->billing_address_1;
        $shipping->comment = $request->order_comments;
        $shipping->address_type = '2';

        if($request->ship_to_different_address == '1'){
            $shipping->name = $request->shipping_first_name;
            $shipping->phone = $request->shipping_phone;
            $shipping->email = $request->shipping_email;
            $shipping->alternative_phone = $request->shipping_alternative_phone;
            $shipping->state = $request->shipping_state;
            $shipping->area = $request->shipping_area;
            $shipping->address_details = $request->shipping_address_1;
        }

        $shipping->save();

        $user->billing_address =  $billing->id;
        $user->shipping_address = $shipping->id;
        $user->save();

        $latestOrder = Order::latest()->first();
        $lastOrderNumber = $latestOrder ? $latestOrder->order_number : 0;
        $lastOrderNumber = (int)$lastOrderNumber;
        $newOrderNumber = ++$lastOrderNumber;
        $newOrderNumber = str_pad($newOrderNumber, 6, '0', STR_PAD_LEFT);

        $totalPrice = 0;
        $totalNormalPrice = 0;
        $deliveryType = getSelectedDeliveryType();
        $selectedCoupon = getSelectedCoupon();

        foreach (cartItems() as $key => $item){
            $product = Product::where('id', $item['product_id'])->first();
            $productSize = ProductSize::join('sizes','sizes.id','=','product_sizes.size_id')
                            ->where('product_sizes.id', $item['size_id'])->select('product_sizes.*','sizes.name')->first();
            
            $productId = $product->id;
            $productName = $product->name;
            $productImage = $product->image;
            $productImage = $product->image;
            $sizeName = '';
            $price = 0;
            $normalPrice = 0;
            $quantity = $item['quantity'];

            if($product->is_size == '1' && $productSize && $productSize->product_id == $product->id){
                $sizeName = $productSize->name;
            }

            if($product->is_size == '1' && $product->size_wise_price == '1' && $productSize && $productSize->product_id == $product->id){
                $price = isOffer($productSize) ? $productSize->offer_price : $productSize->price;
                $normalPrice = $productSize->price;
            }else{
                $price = isOffer($product) ? $product->offer_price : $product->price;
                $normalPrice = $product->price;
            }

            $totalPrice += $price * $quantity;
            $totalNormalPrice += $normalPrice * $quantity;

            $is_discount = $normalPrice > $price;
            $discount = $normalPrice - $price;

            $orderItem = new OrderItem;
            $orderItem->order_number = $newOrderNumber;
            $orderItem->product_id = $productId;
            $orderItem->product_name = $productName;
            $orderItem->quantity = $item['quantity'];
            $orderItem->sale_price = $price;
            $orderItem->price = $product->price;
            $orderItem->offer_price = $product->offer_price;
            $orderItem->offer_from = $product->offer_from;
            $orderItem->offer_to = $product->offer_to;
            $orderItem->is_book_or_product = $product->is_book_or_product;
            $orderItem->is_package = $product->is_package;
            $orderItem->package_item_ids = $product->package_item_ids;
            $orderItem->is_size = $product->is_size;
            $orderItem->is_size_wise_price = $product->is_size_wise_price;
            if($productSize){
                $orderItem->size_id = $productSize->id;
                $orderItem->size_name = $productSize->name;
                $orderItem->sz_price =  $productSize->price;
                $orderItem->sz_offer_price = $productSize->offer_price;
                $orderItem->sz_offer_from = $productSize->offer_from;
                $orderItem->sz_offer_to = $productSize->offer_to;
            }
            $orderItem->save();
        }

        $discount_type = null;
        $discount_type_amount = 0;
        $discount = 0;
        if($selectedCoupon['response_code'] == '3'){
            $discount_type = $selectedCoupon['coupon']['discount_type'];
            $discount_type_amount = $selectedCoupon['coupon']['discount'];
          if($selectedCoupon['coupon']['discount_type']=='1'){
            $discount = ($selectedCoupon['coupon']['discount'] / 100) * $totalPrice;
          }else{
            $discount = $selectedCoupon['coupon']['discount'];
          }
        }

        $order = new Order;
        $order->order_number = $newOrderNumber;
        $order->customer_id =  $user->id;
        $order->total_item = cartCount();
        $order->discount_type = $discount_type;
        $order->discount_type_amount = $discount_type_amount;
        $order->discount = round($discount);
        $order->total_amount = $totalPrice;
        $order->paid_amount = getTotalcartValueWithAll();
        $order->delivery_charge =  $deliveryType['amount'];
        $order->billing_address =  $billing->id;
        $order->shipping_address = $shipping->id;
        $order->is_packaging = $request->gift_paper??'0';
        $order->packaging_cost = 0;
        $order->delivery_method = $deliveryType['id'];
        $order->payment_method = $request->payment_method;
        $order->is_paid = 0;
        $order->is_order_valid = true;
        $order->transaction_id = null;
        $order->save();

        Mail::to($user->email)->send(new PlaceOrderMail($order->order_number));
        // Mail::to("order@pizzapitsa.fi")->send(new PlaceOrderMail($order->order_number, $data));

        clearCart();

        return redirect()->route('order_received', $order->order_number);

    }

    public function orderReceived(Request $request, $order_number){
        $order = Order::where('order_number', $order_number)->first();
        if(!$order) abort(404);
        $billing = Address::where('id',  $order->billing_address)->first();
        $shipping = Address::where('id',  $order->shipping_address)->first();
        $orderItems = OrderItem::where('order_number',$order->order_number)->get();
        $lib_districts = lib_districts();
        $lib_areas = lib_areas();
        return view('frontend.pages.order_received', compact('order','orderItems','billing','shipping','lib_districts','lib_areas'));
    }

    public function getOrders()
    {
       
        $orders = Order::orderBy('id', 'DESC')->get();
        return view("admin.pages.order.index", compact('orders'));
    }

    public function filterOrders(Request $request){

        $orders = Order::query();
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $orders->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        if ($request->order_number!="" && $request->order_number != null) {
            $orders->where('order_number', $request->order_number);
        }
        if ($request->order_status!="" && $request->order_status != null) {
            $orders->where('order_status', $request->order_status);
        }
        if ($request->is_paid!="" && $request->is_paid != null) {
            $orders->where('is_paid', $request->is_paid);
        }
        $orders = $orders->get();

        return view('admin.pages.order.filter_order', compact('orders'));
    }

    public function getOrderDetails($id)
    {
        $order = Order::where('order_number', $id)->where('is_order_valid', 1)->first();
        if(!$order) abort(404);
        $orderItems = OrderItem::where('order_number',$order->order_number)->get();
        $billing = Address::where('id',  $order->billing_address)->first();
        $shipping = Address::where('id',  $order->shipping_address)->first();
        $lib_districts = lib_districts();
        $lib_areas = lib_areas();
      
        return view("admin.pages.order.details", compact('orderItems', 'order','billing','shipping','lib_districts','lib_areas'));
    }
    public function updateStatus(Request $request)
    {
        $data =  $request;
        $newStatus = $request->newStatus;
        $orderId = $request->orderId;
        $sendMail = $request->sendMail;
        $getOrder = Order::where('order_number', $orderId)->first();
        $getCustomerMail = User::where('id', $getOrder->customer_id)->first();
        $order = Order::where('order_number', $orderId)->where('is_order_valid', 1)->first();
        $order->order_status = $newStatus;
        $order->update();

        if ($sendMail == true) {
            // return $getCustomerMail->email;
            Mail::to($getCustomerMail->email)->send(new sendStatusChangeMail($orderId, $data));
        }
        return response()->json('Success');
    }

    public function updatePaymentStatus(Request $request)
    {
        $data =  $request;
        $newStatus = $request->newStatus;
        $orderId = $request->orderId;
        $sendMail = $request->sendMail;
        $getOrder = Order::where('order_number', $orderId)->first();
        $getCustomerMail = User::where('id', $getOrder->customer_id)->first();

        $order = Order::where('order_number', $orderId)->where('is_order_valid', 1)->first();
        $order->is_paid = $newStatus;
        $order->update();
        if ($sendMail == true) {
            // return $getCustomerMail->email;
            Mail::to($getCustomerMail->email)->send(new sendPaymentStatusChangeMail($orderId, $data));
        }
        return response()->json('Success');
    }

    public function updateAddress(Request $request)
    {
        $selectedAddress = $request->selectedAddress;
        $address = Address::where('id', $request->addressId)->first();
        $address->selectedAddress = $selectedAddress;
        $address->entrance = $request->entrance;
        $address->door_code = $request->door_code;
        $address->floor = $request->floor;
        $address->apartment = $request->apartment;
        $address->comment = $request->comment;
        $address->update();
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'Address update success',
        ]);
        return redirect()->back();
    }

    public function updateQty(Request $request)
    {
        $orderItem = OrderItem::where('order_number', $request->order_id)->where('product_id', $request->product_id)->first();
        if ($orderItem) {

            $oldTotalProductPrice = $orderItem->total_price;
            $order = Order::where('order_number', $request->order_id)->first();
            $order->total_amount -= $oldTotalProductPrice;
            $order->paid_amount -= $oldTotalProductPrice;
            $order->update();

            $orderItem->quantity  = $request->qty;
            $orderItem->total_price = $orderItem->price * $request->qty;
            $orderItem->update();

            $newTotalProductPrice = $orderItem->total_price;
            $order->total_amount += $newTotalProductPrice;
            $order->paid_amount += $newTotalProductPrice;
            $order->update();

            $order->update();
            session()->flash('sweet_alert', [
                'type' => 'success',
                'title' => 'Success!',
                'text' => 'Quantity update success',
            ]);
            return redirect()->back();
        }
    }

    public function getOrderStatus()
    {
        return orderStatuses();
    }

    public function getCustomerOrderInfo(Request $request)
    {
        $request;
        $id =  $request->orderNumber;
        $orderDetails = Order::leftJoin('addresses', 'addresses.id', '=', 'orders.delivery_address_id')
            ->leftJoin('users', 'users.id', '=', 'orders.customer_id')
            ->select('orders.*', 'addresses.selectedAddress', 'addresses.selectedAddress', 'addresses.entrance', 'addresses.door_code', 'addresses.apartment', 'addresses.comment', 'addresses.floor', 'users.name', 'users.email', 'addresses.id as AddId')
            ->where('orders.order_number', $id)->where('orders.is_order_valid', 1)->first();
        $products = OrderItem::join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('sizes', 'sizes.id', '=', 'order_items.size_id')
            ->select('order_items.*', 'products.name as proName', 'products.image', 'sizes.name as sizeName')
            ->where('order_items.order_number', $id)
            ->get();

        $products->each(function ($product) {
            $topingIds = explode(',', $product->toping_ids);
            $topingNames = Toping::whereIn('id', $topingIds)->pluck('name')->toArray();
            $product->topingNames = implode(', ', $topingNames);

            $optionIds = explode(',', $product->option_ids);
            $optionNames = Toping::whereIn('id', $optionIds)->pluck('name')->toArray();
            $product->optionNames = implode(', ', $optionNames);

            $removedTags = explode(',', $product->removed_tags);
            $tagNames = ProductTag::whereIn('id', $removedTags)->pluck('tag_name')->toArray();
            $product->tagNames = implode(', ', $tagNames);
        });

        $user = User::where('id', auth()->user()->id)->first();
        return response()->json(['message' => 'success', 'products' => $products, 'orderDetails' => $orderDetails, 'user' => $user]);
    }

    public function assignDeliveryBoy(Request $request)
    {
        $value = $request->value;
        $orderId = $request->orderId;
        $order = Order::where('order_number', $orderId)->first();
        $order->delivery_boy = $value;
        $order->update();
        return response()->json('Success');
    }

    public function addToCart(Request $request) {
        $product_id = $request->product_id;
        $size_id = $request->size_id;
        $quantity = $request->quantity >0 ? $request->quantity : 1;

        $product = Product::find($product_id);

        if(!$product){
            return response()->json([
                'result' => 'error',
                'message' => getNotify(10),
            ]);
        }

        $selectedSize = null;
        $price = PHP_INT_MAX;
        if($product->is_size == '1'){
            $size = ProductSize::where('id',$size_id)->first();
            if(!$size){
                $sizes = ProductSize::where('product_id',$product->id)->get();
                foreach($sizes as $size){
                    $pric = $size->price;
                    if(isOffer($size)){$pric = $size->offer_price;}
                    if($price > $pric){$price = $pric;$selectedSize = $size;}
                }
            }else{$selectedSize = $size;}
        }

        $cart = Session::get('cart', []);
        $key = $product_id."_".($selectedSize ? $selectedSize->id : null);
        if(isset($cart[$key])){
            $cart[$key]['quantity'] += $quantity;
        }else{
            $cart[$key] = [
                'product_id' => $product_id,
                'size_id' => $selectedSize ? $selectedSize->id : null,
                'quantity' => $quantity,
            ];
        }
        Session::put('cart', $cart);

        $totalItem = 0;
        $normalPrice = 0;
        $currectPrice = 0;
        foreach($cart as $item){
            $pro = Product::where('id', $item['product_id'])->first();
            $proSize = ProductSize::where('id', $item['size_id'])->first();
            if($pro && !($pro->is_size == '1' && !$proSize) && (!$proSize || ($proSize && $pro->id == $proSize->product_id))){

                if($pro->is_size == '1' && $pro->size_wise_price == '1'){
                    $normalPrice += $proSize->price * $item['quantity'];
                    if(isOffer($proSize)) $currectPrice +=  $proSize->offer_price * $item['quantity'];
                    else $currectPrice +=  $proSize->price * $item['quantity'];
                }else{
                    $normalPrice += $pro->price * $item['quantity'];
                    if(isOffer($pro)) $currectPrice +=  $pro->offer_price * $item['quantity'];
                    else $currectPrice +=  $pro->price * $item['quantity'];
                }

                $totalItem++;
            }
        }

        $data = [
            'product_name' => $product->name,
            'totalItem' => $totalItem,
            'normalPrice' => $normalPrice,
            'currectPrice' => $currectPrice,
            'discount' => $normalPrice - $currectPrice,
            'is_discount' => $normalPrice - $currectPrice > 0 ? true : false,
            'cart' => (string)cartMiniView(),
        ];
    
        return response()->json([
            'result' => 'success',
            'message' => getNotify(1),
            'cart_data' => $data,
        ]);
    }

    public function updateCartQty(Request $request){
        $key = $request->key;
        $qty = $request->value;
        $cart = Session::get('cart', []);
        if(isset($cart[$key])) $cart[$key]['quantity'] = $qty;
        Session::put('cart', $cart);
        
        return [
            'html' => (string)cartView(),
            'miniCartHtml' => (string)cartMiniView(),
            'currectPrice'=> getTotalcartValue(),
        ];
    }

    public function updateCartDeliveryCharge(Request $request){
        $deliveryType = $request->deliveryCharge;
        $details = cartDetails();
        if(!isset($details['delivery_type'])) $details['delivery_type'] = null;
        $details['delivery_type'] = $deliveryType;
        Session::put('cart_details', $details);
        return [
            'html' => (string)cartView(),
            'miniCartHtml' => (string)cartMiniView(),
            'currectPrice'=> getTotalcartValue(),
            'checkout_page'=> (string)generateCheckOutPage(),
        ];
    }

    public function applyCoupon(Request $request){
        $coupon = $request->couponCode;
        $details = cartDetails();
        if(!isset($details['coupon'])) $details['coupon'] = null;
        $details['coupon'] = $coupon;
        Session::put('cart_details', $details);
        return [
            'html' => (string)cartView(),
            'miniCartHtml' => (string)cartMiniView(),
            'currectPrice'=> getTotalcartValue(),
        ];
    }

    function removeCartItem(Request $request){
        $key = $request->key;
        $cart = Session::get('cart', []);
        if(isset($cart[$key])) unset($cart[$key]);
        Session::put('cart', $cart);
        return response()->json([
            'currectPrice' => getTotalcartValue(),
            'cart' => (string)cartMiniView(),
        ]);
    }

    public function savePaymentInfo(Request $request){
        // return $request->all();
        $order = Order::where('order_number', $request->order_number)->first();
        $return = false;
        if($order){
            $order->account_number = $request->ac_no;
            $order->transaction_id = $request->ref;
            $order->update();
            $return = true;
        }

        if($request->from_submit=='1'){
            return redirect()->back();
        }else{
            return $return;
        }
    }

    public function editOrder(Request $request,$id){
        $order = Order::where('order_number', $id)->first();
        if(!$order) abort(404);
        $billing = Address::where('id', $order->billing_address)->first();
        $shipping = Address::where('id', $order->billing_address)->first();
        $orderItems = OrderItem::where('order_number', $id)->get();
        $lib_districts = lib_districts();
        $lib_areas = lib_areas();
        $products = Product::where('status','1')->get();
        return view('admin.pages.order.edit', compact('products','order','orderItems','billing','shipping','billing','shipping','lib_districts','lib_areas'));
    }

    function addItemToOrder(Request $request){
        abort(404);
        $order = Order::where('order_number', $request->order_number)->first();
        $product = Product::where('id', $request->product_id)->first();
        $productSize = ProductSize::where('id', $request->size_id)->first();
        if(!$order || !$product) abort(404);
        if($product->is_size == '1' && !$productSize) abort(404);

        $sale_price = 0;
        if($product->is_size == '1' && $product->is_size_wise_price == '1'){
            $sale_price = isOffer($productSize) ? getOfferPrice($productSize) : $productSize->price;
        }else{
            $sale_price = isOffer($product) ? getOfferPrice($product) : $product->price;
        }
        $totalPrice = $sale_price * $request->quantity;

        $discount = 0;
        if($order->discount_type=='1'){
            $discount = (($order->discount_type_amount / 100) * $totalPrice);
        }
        
        $order->total_item++;
        $order->discount = round($order->discount + $discount);
        $order->total_amount += $totalPrice;
        $order->paid_amount += ($totalPrice - round($discount));
        $order->update();

        $orderItem = new OrderItem;
        $orderItem->order_number = $order->order_number;
        $orderItem->product_id = $product->id;
        $orderItem->product_name = $product->name;
        $orderItem->quantity = $request->quantity;
        $orderItem->sale_price = $sale_price;
        $orderItem->price = $product->price;
        $orderItem->offer_price = $product->offer_price;
        $orderItem->offer_from = $product->offer_from;
        $orderItem->offer_to = $product->offer_to;
        $orderItem->is_book_or_product = $product->is_book_or_product;
        $orderItem->is_package = $product->is_package;
        $orderItem->package_item_ids = $product->package_item_ids;
        $orderItem->is_size = $product->is_size;
        $orderItem->is_size_wise_price = $product->is_size_wise_price;
        if($productSize){
            $orderItem->size_id = $productSize->id;
            $orderItem->size_name = $productSize->name;
            $orderItem->sz_price =  $productSize->price;
            $orderItem->sz_offer_price = $productSize->offer_price;
            $orderItem->sz_offer_from = $productSize->offer_from;
            $orderItem->sz_offer_to = $productSize->offer_to;
        }
        $orderItem->save();

        return redirect()->route('edit-order',$request->order_number);

    }
}
