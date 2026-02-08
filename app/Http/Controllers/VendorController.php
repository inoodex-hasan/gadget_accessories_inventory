<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor;
use Input;
use Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Vendor::latest()->get();
        return view('frontend.pages.vendor.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('frontend.pages.vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'phone' => 'required|numeric|unique:customers,phone',
            'email' => 'nullable|email',
            'address' => 'required|string',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'edit'])->withErrors($validation)->withInput();
        }

        $customer = new Vendor;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->status = '1';
        $customer->save();
    
        // return redirect()->back()->with(['success' => getNotify(1)]);
        return redirect()->route('vendors.index')->with('success', 'Vendor added successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Vendor::findOrFail($id);
        return view('frontend.pages.vendor.edit',compact('customer'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attributes = $request->all();
        $rules = [
            'name' => 'required',
            'phone' => 'required|numeric|unique:customers,phone,'. $id,
            'email' => 'nullable|email',
            'address' => 'required|string',
        ];
        $validation = Validator::make($attributes, $rules);
        if ($validation->fails()) {
            return redirect()->back()->with(['error' => getNotify(4), 'error_code' => 'edit'])->withErrors($validation)->withInput();
        }

        $customer = Vendor::findOrFail($id);
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->save();
    
        // return redirect()->back()->with(['success' => getNotify(2)]);
        return redirect()->route('vendors.index')->with('success', 'Vendor data updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $customer = Vendor::findOrFail($id);
       $customer->delete();
       return redirect()->back()->with(['success' => getNotify(3)]);
    }
}