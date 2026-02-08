<?php

namespace App\Http\Controllers;

use Input;
use Validator;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use Twilio\Rest\Client;
use App\Models\Customer;
use App\Models\DailySale;
use App\Mail\PlaceOrderMail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ServiceController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // return $request->all();


        $services = Service::leftjoin('users','users.id','=','services.repaired_by');
        
        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $services = $services->whereBetween('services.created_at', [$from, $to]);
        }

        if ($request->service_type != "") {
            if($request->service_type=="paid"){
                $services = $services->where('services.due_amount', '=', '0');
            }
            if($request->service_type=="due"){
                $services = $services->where('services.due_amount', '>', '0');
            }
        }

        if ($request->serach_by != "" && $request->key != "") {
           $services = $services->where('services.'.$request->serach_by, 'like', '%' . $request->key . '%');
        }


        $services = $services->where('services.status','0');
        $services = $services->select('services.*','users.name as repaired_by')->orderBy('id','desc')->get();

        $users = lib_serviceMan();
        if($request->search_for == 'pdf'){
            $pdf = Pdf::loadView('pdf.services', compact('services','users','request'));
            return $pdf->download('Services.pdf');
        }

        return view('frontend.pages.service.index',compact('services','users','request'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users  = User::get();
        $products = Product::where('status','1')->where('type','1')->get();
        return view('frontend.pages.service.create',compact('users','products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
       $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'country_code' => 'required',
            'phone' => 'required|numeric',
            'address' => 'nullable',
            'product_name' => 'required',
            'product_number' => 'nullable',
            'total' => 'required|numeric',
            'bill' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'payment_method_id' => [function ($attribute, $value, $fail) use ($request) {
                if ($request->paid_amount > 0 && !$value) {
                    $fail('The payment method is required when the paid amount is greater than 0.');
                }
            }],
            'warranty_duration' => 'required|numeric',
            'repaired_by' => 'required|numeric',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }
        // return $request->all();
        if(!is_numeric($request->product_name)){
            $product = new Product;
            $product->name = $request->product_name;
            $product->type = '1';
            $product->save();
        }else{
            $product = Product::where('id', $request->product_name)->first();
            if($product)$request->product_name =  $product->name;
        }

        $customerByPhone = Customer::where('phone', $request->phone)->first();
        $customerByEmail = Customer::where('email', $request->email)->first();
        if($request->email == "") $customerByEmail = null;
        $customer =  new Customer;

        if((!$customerByPhone && $customerByEmail)){
            $customer = $customerByEmail;
        }elseif(($customerByPhone && !$customerByEmail)){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id == $customerByEmail->id){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id != $customerByEmail->id){
            return redirect()->back()->with(['error' => 'The email is added for another customer.'])->withInput();
        }

        $customer->name = $request->name;
        if($request->email != "" )$customer->email = $request->email;
        $customer->country_code = $request->country_code;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        $service = new Service;
        $service->customer_id = $customer->id;
        $service->name = $customer->name;
        $service->country_code = $request->country_code;
        $service->phone = $customer->phone;
        $service->email = $customer->email;
        $service->address = $customer->address;
        $service->product_name = $request->product_name;
        $service->product_number = $request->product_number;
        $service->total = $request->total??0;
        $service->discount = $request->discount??0;
        $service->bill = $request->bill??0;
        $service->paid_amount = $request->paid_amount??0;
        $service->due_amount = max(0,$request->bill-$request->paid_amount);
        $service->details = $request->details;
        $service->warranty_duration = $request->warranty_duration;
        $service->repaired_by = $request->repaired_by;
        $service->status = '0';
        $service->save();

        if($request->is_booking == 'true'){
            $booking = Booking::where('id', $request->booking_id)->first();
            if(!$booking) $booking = new Booking;
            $booking->id = $request->booking_id;
            $booking->customer_id = $customer->id;
            $booking->country_code = $request->country_code;
            $booking->name = $customer->name;
            $booking->phone = $customer->phone;
            $booking->email = $customer->email;
            $booking->address = $customer->address;
            $booking->product_name = $request->product_name;
            $booking->product_number = $request->product_number;
            $booking->details = $request->details;
            $booking->save();
        }

        if($request->paid_amount > 0){
            $payment = new Payment;
            $payment->payment_for = '1';
            $payment->customer_id = $customer->id;
            $payment->bill_id = $service->id;
            $payment->payment_method_id = $request->payment_method_id;
            $payment->amount = $request->paid_amount;
            $payment->save();
        }


        $service = Service::join('customers','customers.id','=','services.customer_id')
                    ->where('services.id',$service->id)
                    ->select('services.*')
                    ->first();
        $serviceMans = lib_serviceMan();

        // return view('layouts.placeOrderMail', compact('service','serviceMans'));

        if($request->email){
            Mail::to($request->email)->send(new PlaceOrderMail($service, $serviceMans));
        }
        if($request->phone){
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            $man = getArrayData($serviceMans,$service->repaired_by);

            $message  = "Quick Phone Fix N More\n";
            $message .= "7157 Ogontaz Ave, Philadelphia PA 19138\n";
            $message .= "Hotline: +234 901 791 9699\n\n";

            $message .= "Service Confirmation\n\n";

            $message .= "Customer Info:\n";
            $message .= "Name: {$service->name}\n";
            $message .= "Phone: {$service->phone}\n";
            $message .= "Email: {$service->email}\n";
            $message .= "Address: {$service->address}\n";

            $message .= "Service Info:\n";
            $message .= "Product: {$service->product_name}\n";
            $message .= "IMEI: {$service->product_number}\n";
            if($service->details)$message .= "Details: {$service->details}\n";
            $message .= "Warranty: {$service->warranty_duration} days\n";
            $message .= "Price: \${$service->bill}\n";
            $message .= "Paid: \${$service->paid_amount}\n";
            $message .= "Due: \${$service->due_amount}\n";
            $message .= "Repaired By: {$man}\n";
            $message .= "Date: " . now()->format('Y-m-d g:i A') . "\n\n";
            $message .= "Thank You. Please come again.\n\n";

            $message .= "Note: Warranty For {$service->warranty_duration} Days. Warranty Does Not Cover Broken Or Water Damage. No Refund, Exchange Only.";

            try{
                $twilio->messages->create(
                    $request->country_code . $request->phone,
                    [
                        'from' => env('TWILIO_PHONE_NUMBER'),
                        'body' => $message
                    ]
                );
            } catch (\Twilio\Exceptions\RestException $e) {}
        }
        


        return redirect()->back()->with(['success' => getNotify(1)]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $service = Service::join('customers','customers.id','=','services.customer_id')
                    ->where('services.id',$id)
                    ->select('services.*')
                    ->first();
        if(!$service)abort(404);
        $products = Product::where('status','1')->where('type','1')->get();
        $serviceMans = lib_serviceMan();

        return view('frontend.pages.service.edit',compact('service','serviceMans','products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::where('id',$id)->first();
        if(!$service)abort(404);

        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'country_code' => 'required',
            'phone' => 'required|numeric',
            'address' => 'nullable',
            'product_name' => 'required',
            'product_number' => 'nullable',
            'total' => 'required|numeric',
            'bill' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'warranty_duration' => 'required|numeric',
            'repaired_by' => 'required|numeric',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4)])->withErrors($validation)->withInput();
        }

        if(!is_numeric($request->product_name)){
            $product = new Product;
            $product->name = $request->product_name;
            $product->save();
        }else{
            $product = Product::where('id', $request->product_name)->first();
            if($product)$request->product_name =  $product->name;
        }

        $customerByPhone = Customer::where('phone', $request->phone)->first();
        $customerByEmail = Customer::where('email', $request->email)->first();
        if($request->email == "") $customerByEmail = null;
        $customer =  new Customer;

        if((!$customerByPhone && $customerByEmail)){
            $customer = $customerByEmail;
        }elseif(($customerByPhone && !$customerByEmail)){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id == $customerByEmail->id){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id != $customerByEmail->id){
            return redirect()->back()->with(['error' => 'The email is added for another customer.'])->withInput();
        }

        $customer->name = $request->name;
        if($request->email != "" )$customer->email = $request->email;
        $customer->country_code = $request->country_code;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        $service->customer_id = $customer->id;
        $service->name = $customer->name;
        $service->country_code = $request->country_code;
        $service->phone = $customer->phone;
        $service->email = $customer->email;
        $service->address = $customer->address;
        $service->product_name = $request->product_name;
        $service->product_number = $request->product_number;
        $service->total = $request->total??0;
        $service->discount = $request->discount??0;
        $service->bill = $request->bill??0;
        $service->due_amount = max(0,$request->bill-$service->paid_amount);
        $service->details = $request->details;
        $service->warranty_duration = $request->warranty_duration;
        $service->repaired_by = $request->repaired_by;
        $service->update();

        return redirect()->back()->with(['success' => getNotify(2)]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::where('id',$id)->first();
        if(!$service)abort(404);
        $service->delete();

        return redirect()->back()->with(['success' => getNotify(3)]);
    }

    public function makeInvoice(Request $request, $serviceId){
        $service = Service::join('customers','customers.id','=','services.customer_id')
                    ->where('services.id',$serviceId)
                    ->select('services.*')
                    ->first();
        if(!$service)abort(404);
        $serviceMans = lib_serviceMan();

        return view('frontend.pages.service.invoice',compact('service','serviceMans'));
    }

    public function complatedService(Request $request){
        $services = Service::leftjoin('users','users.id','=','services.repaired_by');

        $defaultFilter = true;

        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $services = $services->whereBetween('services.created_at', [$from, $to]);
            $defaultFilter = false;
        }

        if ($request->service_type != "") {
            if($request->service_type=="paid"){
                $services = $services->where('services.due_amount', '=', '0');
                $defaultFilter = false;
            }
            if($request->service_type=="due"){
                $services = $services->where('services.due_amount', '>', '0');
                $defaultFilter = false;
            }
        }

        if ($request->serach_by != "" && $request->key != "") {
            $services = $services->where('services.'.$request->serach_by, 'like', '%' . $request->key . '%');
            $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $services = $services->whereBetween('services.created_at', [$startOfMonth, $endOfMonth]);
        }

        $services = $services->where('services.status','1');
        $services = $services->select('services.*','users.name as repaired_by')->orderBy('id','desc')->get();

        $users = lib_serviceMan();

        if($request->search_for == 'pdf'){
            $pdf = Pdf::loadView('pdf.services', compact('services','users','request'));
            return $pdf->download('Services.pdf');
        }
        //Report
        $todaysRevenue = Service::whereDate('created_at', Carbon::today())->where('status','1')->sum('bill');
        $thisWeeksRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('bill');
        $thisMonthsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('bill');
        $thisYearsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('bill');
        $totalServiceDues = Service::where('status','1')->where('due_amount', '>', 0)->sum('due_amount');

        $todaysSalesRevenue = Sale::whereDate('created_at', Carbon::today())->where('status','1')->sum('bill');
        $thisWeeksSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('bill');
        $thisMonthsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('bill');
        $thisYearsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('bill');
        $totalSalesDues = Sale::where('due_amount', '>', 0)->sum('due_amount');

        $todaysDailySalesRevenue = DailySale::whereDate('date', Carbon::today())->where('status','1')->sum('total_amount');
        $thisWeeksDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status','1')->sum('total_amount');
        $thisMonthsDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status','1')->sum('total_amount');
        $thisYearsDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status','1')->sum('total_amount');

        $monthlyRevenue = Service::selectRaw('MONTH(created_at) as month, SUM(bill) as total')
        ->whereYear('created_at', Carbon::now()->year)
        ->where('status','1')
        ->groupBy('month')
        ->pluck('total', 'month')
        ->mapWithKeys(function ($total, $month) {
            $monthName = Carbon::createFromFormat('m', $month)->format('M');
            return [$monthName => $total];
        });

        $yearlyRevenue = Service::selectRaw('YEAR(created_at) as year, SUM(bill) as total')
        ->whereRaw('YEAR(created_at) >= YEAR(CURDATE()) - 9')
        ->where('status','1')
        ->groupBy('year')
        ->pluck('total', 'year');
        return view('frontend.pages.service.complated',compact('services','users','request','todaysRevenue','thisWeeksRevenue','thisMonthsRevenue','thisYearsRevenue','monthlyRevenue','yearlyRevenue','todaysSalesRevenue','thisWeeksSalesRevenue','thisMonthsSalesRevenue','thisYearsSalesRevenue','totalServiceDues','totalSalesDues','todaysDailySalesRevenue','thisWeeksDailySalesRevenue','thisMonthsDailySalesRevenue','thisYearsDailySalesRevenue'));
    }

    public function makeComplate(Request $request, string $id){
        $service = Service::where('id',$id)->first();
        if(!$service)abort(404);
        $service->status = '1';
        $service->complated_date = date('Y-m-d');
        $service->update();


        $serviceMans = lib_serviceMan();

        if($service->email){
            Mail::to($service->email)->send(new PlaceOrderMail($service, $serviceMans));
        }
        if($service->phone){
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            $man = getArrayData($serviceMans,$service->repaired_by);

            $message  = "Quick Phone Fix N More\n";
            $message .= "7157 Ogontaz Ave, Philadelphia PA 19138\n";
            $message .= "Hotline: +234 901 791 9699\n\n";

            $message .= "Service Completed\n\n";

            $message .= "Customer Info:\n";
            $message .= "Name: {$service->name}\n";
            $message .= "Phone: {$service->phone}\n";
            $message .= "Email: {$service->email}\n";
            $message .= "Address: {$service->address}\n";

            $message .= "Service Info:\n";
            $message .= "Product: {$service->product_name}\n";
            $message .= "IMEI: {$service->product_number}\n";
            if($service->details)$message .= "Details: {$service->details}\n";
            $message .= "Warranty: {$service->warranty_duration} days\n";
            $message .= "Price: \${$service->bill}\n";
            $message .= "Paid: \${$service->paid_amount}\n";
            $message .= "Due: \${$service->due_amount}\n";
            $message .= "Repaired By: {$man}\n";
            $message .= "Date: " . now()->format('Y-m-d g:i A') . "\n\n";
            $message .= "Thank You. Please come again.\n\n";

            $message .= "Note: Warranty For {$service->warranty_duration} Days. Warranty Does Not Cover Broken Or Water Damage. No Refund, Exchange Only.";


            try{
                $twilio->messages->create(
                    $service->country_code . $service->phone,
                    [
                        'from' => env('TWILIO_PHONE_NUMBER'),
                        'body' => $message
                    ]
                );
            } catch (\Twilio\Exceptions\RestException $e) {}
        }

        return redirect()->back()->with(['success' => getNotify(2)]);
    } 

    public function payments(Request $request){ 

        $payments = Payment::where('payment_for', 1);

        $defaultFilter = true;

        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $payments = $payments->whereBetween('payments.created_at', [$from, $to]);
            $defaultFilter = false;
        }

        if ($request->payments_method != "") {
            $payments = $payments->where('payments.payment_method_id', $request->payments_method);
            $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $payments = $payments->whereBetween('payments.created_at', [$startOfMonth, $endOfMonth]);
        }

        $payments = $payments->get();

        if($request->search_for == 'pdf'){
            $pdf = Pdf::loadView('pdf.service_payments', compact('payments', 'request'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('service Payments.pdf');
        }

        return view('frontend.pages.service.payments',compact('payments','request'));
    }

    public function storeRating(Request $request){
        // return $request->all();
        $service = Service::where('id',$request->service_id)->first();
        if(!$service)abort(404);
        $service->rating = $request->rating;
        $service->review_comments = $request->comments;
        $service->update();
        return redirect()->back()->with(['success' => getNotify(2)]);
    }

}
