<?php

namespace App\Http\Controllers;

use App\Models\AdvanceSalary;
use App\Models\Employee;
use Illuminate\Http\Request;

class AdvanceSalaryController extends Controller
{
    public function index()
    {
        $advances = AdvanceSalary::with('employee')->latest()->get();
        return view('frontend.pages.advance_salaries.index', compact('advances'));
    }

    public function create()
    {
        $employees = Employee::where('status','active')->get();
        return view('frontend.pages.advance_salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric',
            'request_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        AdvanceSalary::create($request->all());

        return redirect()->route('advance.index')->with('success','Advance salary request created.');
    }

    public function edit(AdvanceSalary $advance)
    {
        $employees = Employee::where('status','active')->get();
        return view('frontend.pages.advance_salaries.edit', compact('advance','employees'));
    }

    public function update(Request $request, AdvanceSalary $advance)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric',
            'request_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $advance->update($request->all());

        return redirect()->route('advance.index')->with('success','Advance salary updated.');
    }

    public function destroy(AdvanceSalary $advance)
    {
        $advance->delete();
        return back()->with('success','Advance salary deleted.');
    }
}