<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DailySale;
use App\Models\SalesTarget;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesTargetController extends Controller
{
    public function index(Request $request)
    {
        $sales = SalesTarget::orderBy('id','desc')->get();
        return view('frontend.pages.sales_target.index', compact('sales'));
    }

    public function create()
    {
        $users = User::get();
        return view('frontend.pages.sales_target.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'nullable',            
        ]);

        $dailysales = new SalesTarget;
        $dailysales->month = $request->date;     
        $dailysales->amount = $request->amount;     
        $dailysales->save();

        return redirect()->route('salesTarget.create')->with(['success' => getNotify(1)]);
    }

    public function show(DailySale $dailySale)
    {
        return view('frontend.pages.daily_sales.show', compact('dailySale'));
    }

    public function edit($id)
    {
        $salesTarget = SalesTarget::where('id', $id)->first();       
        return view('frontend.pages.sales_target.edit', compact('salesTarget'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'nullable',            
        ]);

        $dailysales = SalesTarget::where('id', $id)->first();
        if(!$dailysales)abort(0);
        $dailysales->month = $request->date;     
        $dailysales->amount = $request->amount;  
        $dailysales->update();

        return redirect()->route('salesTarget.index')->with(['success' => getNotify(2)]);
    }

    public function destroy(Request $request,$id)
    {
        $dailySale = SalesTarget::where('id', $id)->first();
        if(!$dailySale)abort(0);

        $dailySale->delete();
        return redirect()->route('salesTarget.index')->with(['success' => getNotify(3)]);
    }
}
