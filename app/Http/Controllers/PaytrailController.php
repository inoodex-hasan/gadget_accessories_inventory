<?php

namespace App\Http\Controllers;

use Exception;
// use GuzzleHttp\Client;
use Paytrail\SDK\Client;
use Illuminate\Http\Request;
use Paytrail\SDK\Model\Customer;
use Paytrail\SDK\Model\CallbackUrl;
use Paytrail\SDK\Request\PaymentRequest;
use App\Models\Order;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlaceOrderMail;
use App\Models\Notification;
use Pusher\Pusher;
use Illuminate\Support\Facades\DB;

class PaytrailController extends Controller
{
    public function createPayment(Request $request, $newOrderNumber=null)
    {
        $result = getRootURL();
        $auth = auth()->user();


        $merchantId = 1077442;
        $merchantPass = '06d6fe111d8fc325f50ba97379f34693fdb964e0b8617ce4f8f5af5c836ffdc1928e5f78ee9b64f3';

        
        $platformName = 'Pizza-Pitsa';
        $amount = $request->grandTotal*100; // Amount is in cents, 15.00 €
        $stamp = microtime(); // Use any unique value
        $reference = $auth->name.'('.$auth->id.'), Order Id: '.$newOrderNumber;
        $currency = 'EUR';
        $language = 'FI';
        $email = $auth->email;
        //$groups = ['creditcard'];
        $successUrl = 'https://pizzapitsa.fi/success?order_id='.$newOrderNumber;
        $cancelledUrl = 'https://pizzapitsa.fi/success?order_id='.$newOrderNumber;

        
        // Create customer with minimum values
        $customer = (new Customer())
        ->setEmail($email);

        
        // Set minimum required callback urls
        $redirectUrls = (new CallbackUrl())
        ->setSuccess($successUrl)
        ->setCancel($cancelledUrl);

        
        // Instantiate Paytrail Client
        $client = new Client($merchantId, $merchantPass, $platformName);

        
        // Create payment request with values
        $paymentRequest = new PaymentRequest();
        // Set minimum required values
        $paymentRequest->setAmount($amount)
        ->setStamp($stamp)
        ->setReference($reference)
        ->setCurrency($currency)
        ->setLanguage($language)
        ->setCustomer($customer)
        // ->setGroups($groups)
        ->setRedirectUrls($redirectUrls);

        
        // Wrap payment creation to try catch block. In case failed payment, response contains error message.
        try {
            $payment = $client->createPayment($paymentRequest);
        } catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }
        
        $url = $payment->getHref();
        // return redirect($url);
        return $url;

    }

    public function success(Request $request){

        DB::beginTransaction();
        try{
            $data = $request->all();

            $order = Order::where('order_number', $request->order_id)->first();
            if($data['checkout-status']=='ok'){
                $order->is_order_valid = 1;
                $order->is_paid = 1;
                $order->transaction_id=$data['checkout-transaction-id'];
                $order->update();
            }
            
            $payment = new PaymentHistory;
            $payment->customer_id = $order->customer_id;
            $payment->order_id = $order->id;
            $payment->order_number = $order->order_number;
            $payment->payment_type = $order->payment_type;
            $payment->amount = $order->paid_amount;
            $payment->checkout_account = $data['checkout-account'];
            $payment->checkout_algorithm = $data['checkout-algorithm'];
            $payment->checkout_stamp = $data['checkout-stamp'];
            $payment->checkout_reference = $data['checkout-reference'];
            $payment->checkout_status = $data['checkout-status'];
            $payment->checkout_provider = $data['checkout-provider'];
            $payment->transaction_id = $data['checkout-transaction-id'];
            $payment->signature = $data['signature'];
            $payment->save();


            if($data['checkout-status']=='ok'){
                $user = auth()->user();
                Mail::to($user->email)->send(new PlaceOrderMail($request->order_id, $data));
                Mail::to("dev.pizzapitsa@gmail.com")->send(new PlaceOrderMail($request->order_id, $data));
                
                $notification = new Notification;
                $notification->message = "New Order Placed";
                $notification->url = route('orders.index');
                $notification->save();

                $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'encrypted' => true
                ]);
                $item = $order;
                $data2 = [];
                $data3['order'] = (string) view('admin.pages.order.singleOrder', compact('item'));
                $pusher->trigger('order', 'place-order', $data2);
                $data2 = [
                    'notification' => $notification,
                    'notification_time' => displayNotificationTime($notification->created_at),
                    'unSeenNotifications' => unSeenNotifications(),
                ];
                $pusher->trigger('order', 'place-order-notification', $data2);
            }

            DB::commit();
            $clear = $data['checkout-status']=='ok' ? true : false;
            // return redirect("/dashboard")->with('clear-cart', $clear);
            $message = "orders";
            return redirect("/dashboard?tab=$message")->with('clear-cart', $clear);


        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }  
    }
    public function cancel(Request $request){
        return redirect("/");
    }
    public function pending(Request $request){

    }
    public function notification(Request $request){

    }
}