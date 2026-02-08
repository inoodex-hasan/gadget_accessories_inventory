<?php

namespace App\Http\Controllers;

use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyDetailController extends Controller
{
    public function index()
    {
        $companies = CompanyDetail::latest()->get();
        return view('frontend.pages.company-details.index', compact('companies'));
    }

    public function create()
    {
        return view('frontend.pages.company-details.create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'signatory_name' => 'required|string|max:255',
    //         'signatory_designation' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:255',
    //         'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'email' => 'nullable|email|max:255',
    //         'website' => 'nullable|string|max:255',
    //         'address' => 'nullable|string',
    //         'is_default' => 'sometimes|boolean',
    //         'is_active' => 'sometimes|boolean',
    //     ]);

    //         $data = $request->all();

    //     if ($request->hasFile('photo')) {
    //         $data['photo'] = $request->file('photo')->store('company_details', 'public');
    //     }


    //     if ($request->is_default) {
    //         CompanyDetail::where('is_default', true)->update(['is_default' => false]);
    //     }

    //     CompanyDetail::create($request->all());

    //     return redirect()->route('company-details.index')
    //         ->with('success', 'Company details created successfully.');
    // }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'signatory_name' => 'required|string|max:255',
        'signatory_designation' => 'required|string|max:255',
        'phone' => 'nullable|string|max:255',
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'email' => 'nullable|email|max:255',
        'website' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'is_default' => 'sometimes|boolean',
        'is_active' => 'sometimes|boolean',
    ]);

    $data = $request->all();

    if ($request->hasFile('photo')) {

        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads/company_details'), $filename);

        $data['photo'] = 'uploads/company_details/' . $filename;
    }

    CompanyDetail::create($data);

    return redirect()->route('company-details.index')
        ->with('success', 'Company details created successfully.');
}


    public function edit(CompanyDetail $companyDetail)
    {
        return view('frontend.pages.company-details.edit', compact('companyDetail'));
    }

    // public function update(Request $request, CompanyDetail $companyDetail)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'signatory_name' => 'required|string|max:255',
    //         'signatory_designation' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:255',
    //         'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         'email' => 'nullable|email|max:255',
    //         'website' => 'nullable|string|max:255',
    //         'address' => 'nullable|string',
    //         'is_default' => 'sometimes|boolean',
    //         'is_active' => 'sometimes|boolean',
    //     ]);

    //     if ($request->hasFile('photo')) {
    //         $photo = $request->file('photo');
    //         $photoName = time() . '.' . $photo->getClientOriginalExtension();
    //         $photo->move(public_path('uploads'), $photoName);
    //         $request->merge(['photo' => $photoName]);
    //     }

    //     // If setting as default, remove default from others
    //     if ($request->is_default) {
    //         CompanyDetail::where('is_default', true)->where('id', '!=', $companyDetail->id)->update(['is_default' => false]);
    //     }

    //     $companyDetail->update($request->all());

    //     return redirect()->route('company-details.index')
    //         ->with('success', 'Company details updated successfully.');
    // }

    public function update(Request $request, CompanyDetail $companyDetail)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'signatory_name' => 'required|string|max:255',
        'signatory_designation' => 'required|string|max:255',
        'phone' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'email' => 'nullable|email|max:255',
        'website' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'is_default' => 'sometimes|boolean',
        'is_active' => 'sometimes|boolean',
    ]);

    $data = $request->all();

    if ($request->hasFile('photo')) {

        // Delete old image
        if ($companyDetail->photo && file_exists(public_path($companyDetail->photo))) {
            unlink(public_path($companyDetail->photo));
        }

        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('uploads/company_details'), $filename);

        $data['photo'] = 'uploads/company_details/' . $filename;
    }

    $companyDetail->update($data);

    return redirect()->route('company-details.index')
        ->with('success', 'Updated successfully.');
}

    public function destroy(CompanyDetail $companyDetail)
    {
        // Check if this company is used in any bills
        if ($companyDetail->bills()->exists()) {
            return redirect()->route('company-details.index')
                ->with('error', 'Cannot delete company details that are used in bills.');
        }

        // If deleting default, set another as default
        if ($companyDetail->is_default) {
            $newDefault = CompanyDetail::where('id', '!=', $companyDetail->id)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $companyDetail->delete();

        return redirect()->route('company-details.index')
            ->with('success', 'Company details deleted successfully.');
    }

    public function setDefault(CompanyDetail $companyDetail)
    {
        CompanyDetail::where('is_default', true)->update(['is_default' => false]);
        $companyDetail->update(['is_default' => true]);

        return redirect()->route('company-details.index')
            ->with('success', 'Default company details updated successfully.');
    }
}