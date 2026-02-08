<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $bookings = Booking::join('customers','customers.id','=','bookings.customer_id')
        // ->select('bookings.*','customers.name','customers.phone','customers.email','customers.address')->get();
        // return response()->json($bookings, 200);
        $apiUrl = 'https://quickphonefixandmore.com/wp-json/jet-cct/booking_form_data';
        $response = Http::get($apiUrl);
        
        // Check if the response is successful
        if ($response->successful()) {
            $bookingData = $response->json(); // Decode the JSON data
        } else {
            $bookingData = []; // Handle error or fallback
        }

        // return $bookingData;

        $bookings = Booking::pluck('id')->toArray();

        $users = lib_serviceMan();
        $products = Product::where('status','1')->where('type','1')->get();
        return view('frontend.pages.booking.index',compact('bookingData','products','users','bookings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'product_name' => 'required|string|max:255',
            'product_number' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        $customerByPhone = Customer::where('phone', $request->phone)->first();
        $customerByEmail = Customer::where('email', $request->email)->first();
        $customer =  new Customer;

        if((!$customerByPhone && $customerByEmail)){
            $customer = $customerByEmail;
        }elseif(($customerByPhone && !$customerByEmail)){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id == $customerByEmail->id){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id != $customerByEmail->id){
            return response()->json([
                'error' => 'The email is added for another customer.',
            ], 422);
        }

        $customer->name = $request->name;
        if($request->email != "" )$customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        $booking = new Booking;
        $booking->customer_id = $customer->id;
        $booking->product_name = $request->product_name;
        $booking->product_number = $request->product_number;
        $booking->details = $request->details;
        $booking->save();

        return response()->json($booking, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $booking = Booking::join('customers','customers.id','=','bookings.customer_id')
                    ->where('bookings.id',$id)
                    ->select('bookings.*','customers.name','customers.phone','customers.email','customers.address')
                    ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'product_name' => 'required|string|max:255',
            'product_number' => 'nullable|string|max:255',
            'details' => 'nullable|string',
        ]);

        $customerByPhone = Customer::where('phone', $request->phone)->first();
        $customerByEmail = Customer::where('email', $request->email)->first();
        $customer =  new Customer;

        if((!$customerByPhone && $customerByEmail)){
            $customer = $customerByEmail;
        }elseif(($customerByPhone && !$customerByEmail)){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id == $customerByEmail->id){
            $customer = $customerByPhone;
        }elseif($customerByPhone && $customerByEmail && $customerByPhone->id != $customerByEmail->id){
            return response()->json([
                'error' => 'The email is added for another customer.',
            ], 422);
        }

        $customer->name = $request->name;
        if($request->email != "" )$customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        $booking->customer_id = $customer->id;
        $booking->product_name = $request->product_name;
        $booking->product_number = $request->product_number;
        $booking->details = $request->details;
        $booking->update();

        return response()->json($booking, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
