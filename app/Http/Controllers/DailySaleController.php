<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySale;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Attendance;
use App\Models\SalesTarget;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SalseTargetFillupMail;

class DailySaleController extends Controller
{
    public function index(Request $request)
    {

        $sales = DailySale::query();

        $defaultFilter = true;
        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d', strtotime($request->from));
            $to = date('Y-m-d', strtotime($request->to));
            $sales = $sales->whereBetween('daily_sales.date', [$from, $to]);
            $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
            $sales = $sales->whereBetween('daily_sales.date', [$startOfMonth, $endOfMonth]);
        }

        $sales = $sales->select('daily_sales.*')->get();

        $users = User::pluck('name','id')->toArray();

        if($request->search_for == 'pdf'){
            $pdf = Pdf::loadView('pdf.daily_sales', compact('sales','users','request'));
            return $pdf->download('Dailysales.pdf');
        }

        return view('frontend.pages.daily_sales.index', compact('sales','users','request'));
    }

    public function create()
    {
        $users = User::get();
        return view('frontend.pages.daily_sales.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'description' => 'nullable',
            'card_amount' => 'nullable|numeric',
            'cash_amount' => 'nullable|numeric',
            'others_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'assigned_person_id' => 'required'
        ]);

        
        $dailysales = new DailySale;
        $dailysales->date = $request->date;
        $dailysales->description = $request->description;
        $dailysales->card_amount = $request->card_amount??0;
        $dailysales->cash_amount = $request->cash_amount??0;
        $dailysales->others_amount = $request->others_amount??0;
        $dailysales->total_amount = max(0, (($request->card_amount??0) + ($request->cash_amount??0) + ($request->others_amount??0)));
        $dailysales->assigned_person_id = implode(',', $request->assigned_person_id);
        $dailysales->save();

        $personIds = $request->assigned_person_id;
        foreach ($personIds as $uid){
            $attendance = Attendance::where('date', $request->date)->where('user_id', $uid)->first();
            if(!$attendance){
                $user = User::where('id',$uid)->first();
                $user->days = ($user->days ?? 0) + 1;
                $user->balance = ($user->balance ?? 0) + ($user->salary ?? 0);
                $user->update();
                
                $attendance = new Attendance;
                $attendance->user_id = $uid;
                $attendance->date = $request->date;
                $attendance->salary = $user->salary??0;
                $attendance->days = 1;
                $attendance->save();
            }
        }

        $date = Carbon::parse($request->date);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $monthlySalesTarget = SalesTarget::where('month', $date->format('Y-m'))->first();
        $monthlySalesTarget = $monthlySalesTarget?->amount;
        $thisMonthsDailySalesRevenue = DailySale::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', '1')->sum('total_amount');

        if($thisMonthsDailySalesRevenue >= $monthlySalesTarget){
            $users = User::where('type','1')->get();
            foreach($users as  $user){
                Mail::to($user->email)->send(new SalseTargetFillupMail($user->name, $date->format('F Y')));
            }
        }


        return redirect()->route('dailySales.create')->with(['success' => getNotify(1)]);
    }

    public function show(DailySale $dailySale)
    {
        return view('frontend.pages.daily_sales.show', compact('dailySale'));
    }

    public function edit($id)
    {
        $dailySale = DailySale::where('id', $id)->first();
        $users = User::get();
        if(!$dailySale)abort(0);
        return view('frontend.pages.daily_sales.edit', compact('dailySale','users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'description' => 'nullable',
            'card_amount' => 'nullable|numeric',
            'cash_amount' => 'nullable|numeric',
            'others_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'assigned_person_id' => 'required'
        ]);

        $dailysales = DailySale::where('id', $id)->first();
        if(!$dailysales)abort(0);
        $dailysales->date = $request->date;
        $dailysales->description = $request->description;
        $dailysales->card_amount = $request->card_amount??0;
        $dailysales->cash_amount = $request->cash_amount??0;
        $dailysales->others_amount = $request->others_amount??0;
        $dailysales->total_amount = max(0, (($request->card_amount??0) + ($request->cash_amount??0) + ($request->others_amount??0)));
        $dailysales->assigned_person_id = implode(',', $request->assigned_person_id);
        $dailysales->update();

        $personIds = $request->assigned_person_id;
        foreach ($personIds as $uid){
            $attendance = Attendance::where('date', $request->date)->where('user_id', $id)->first();
            if(!$attendance){
                $user = User::where('id',$uid)->first();
                $user->days = ($user->days ?? 0) + 1;
                $user->balance = ($user->balance ?? 0) + $user->salary;
                $user->update();

                $attendance = new Attendance;
                $attendance->user_id = $uid;
                $attendance->date = $request->date;
                $attendance->salary = $user->salary;
                $attendance->days = 1;
                $attendance->save();
            }
        }

        

        return redirect()->route('dailySales.index')->with(['success' => getNotify(2)]);
    }

    public function destroy(Request $request,$id)
    {
        $dailySale = DailySale::where('id', $id)->first();
        if(!$dailySale)abort(0);

        $dailySale->delete();
        return redirect()->route('dailySales.index')->with(['success' => getNotify(3)]);
    }
}
