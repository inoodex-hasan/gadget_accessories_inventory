<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salary;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            //  ->where('users.type', '2');

            $from = $request->from;
            $to = $request->to;

            // if (!empty($from) && !empty($to)) {
            //     $from = date('Y-m-d', strtotime($from));
            //     $to = date('Y-m-d', strtotime($to));
            //     $users = $users->whereBetween('users.joining_date', [$from, $to]);
            // }

            if (empty($from) || empty($to)) {
                $from = date('Y-m-01');
                $to = date('Y-m-t');  
            }

            $salarySummary = Attendance::whereBetween('date', [$from, $to])
                            ->groupBy('user_id')
                            ->select('user_id', DB::raw('SUM(salary) as total_salary'))
                            ->pluck('total_salary', 'user_id')
                            ->toArray();

            $SalaryPaymentSummary = Salary::whereBetween('date', [$from, $to])
                                    ->groupBy('user_id')
                                    ->select('user_id', DB::raw('SUM(amount) as total_amount'))
                                    ->pluck('total_amount', 'user_id')
                                    ->toArray();
    
            if ($request->serach_by != "" && $request->key != "") {
               $users = $users->where('users.'.$request->serach_by, 'like', '%' . $request->key . '%');
            }

            $users = $users->select('users.*', 'roles.name as roleName')
            ->orderBy('users.id', 'desc')
            ->get();


            if($request->search_for == 'pdf'){
                $pdf = Pdf::loadView('pdf.employee', compact('users','request','salarySummary', 'SalaryPaymentSummary'));
                return $pdf->download('Employee.pdf');
            }

        return view('frontend.pages.staff.index', compact('users','request','salarySummary', 'SalaryPaymentSummary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get();
        return view('frontend.pages.staff.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [            
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'unique:users,phone',
            'status' => 'required|in:0,1',
            'department' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'images' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
        ];
    
        $validatedData = $request->validate($rules);
    
        // Handle Image Upload
        $imageName = null;
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $destinationPath = public_path('frontend/users/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }
    
        // Handle Multiple Document Uploads
        $documentNames = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $docName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('frontend/documents/'), $docName);
                $documentNames[] = $docName;
            }
        }
    
        // Create a new user instance
        $user = new User();
        $user->type = '2';
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->images = $imageName;
        $user->status = $validatedData['status'];
        $user->department = $validatedData['department'];
        $user->designation = $validatedData['designation'];
        $user->joining_date = $validatedData['joining_date'];
        $user->salary = $validatedData['salary'];
        $user->documents = json_encode($documentNames); // Store as JSON array
        $user->save();
    
       
    
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'User added successfully!',
        ]);
    
        return redirect()->route('staff.index')->with('success', 'Staff created successfully');
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
        $roles = Role::get();
        $user = User::where('id', $id)->first();
        return view('frontend.pages.staff.edit', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id); // Find the user by ID
    
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'unique:users,phone,' . $user->id,
            'status' => 'required|in:0,1',
            'department' => 'required|string',
            'designation' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'images' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'documents.*' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:2048',
        ];
    
        $validatedData = $request->validate($rules);
    
        // Handle Image Upload (Keep old if not replaced)
        $imageName = $user->images;
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $destinationPath = public_path('frontend/users/');
            $imageName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
        }
    
        // Handle Multiple Document Uploads (Keep old if not replaced)
        $documentNames = json_decode($user->documents, true) ?? [];
        if ($request->hasFile('documents')) {
            $documentNames = []; // Clear old documents
            foreach ($request->file('documents') as $document) {
                $docName = now()->format('YmdHis') . '_' . Str::random(10) . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('frontend/documents/'), $docName);
                $documentNames[] = $docName;
            }
        }
    
        // Update user details
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->images = $imageName;
        $user->status = $validatedData['status'];
        $user->department = $validatedData['department'];
        $user->designation = $validatedData['designation'];
        $user->joining_date = $validatedData['joining_date'];
        $user->salary = $validatedData['salary'];
        $user->documents = json_encode($documentNames); // Store documents as JSON
        $user->update();
    
    
        session()->flash('sweet_alert', [
            'type' => 'success',
            'title' => 'Success!',
            'text' => 'User updated successfully!',
        ]);
    
        return redirect()->route('staff.index')->with('success', 'Staff updated successfully');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully');
    }


    public function createPayment(Request $request){
        $rules = [
            'user_id' => 'required|numeric',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ];
    
        $validatedData = $request->validate($rules);

        $user = User::where('id',$request->user_id)->first();
        $user->paid = ($user->paid ?? 0) + $request->amount;
        $user->balance = ($user->balance ?? 0) - $request->amount;
        $user->update();

        $salary = new Salary;
        $salary->user_id = $request->user_id;
        $salary->date = $request->date;
        $salary->amount = $request->amount;
        $salary->remarks = $request->remarks;
        $salary->save();

        return redirect()->back()->with('success', 'Payment Created successfully');

    }

    public function updatePayment(Request $request, $id){
        $rules = [
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'remarks' => 'nullable|string',
        ];
    
        $validatedData = $request->validate($rules);

        $salary = Salary::where('id',$id)->first();
        if(!$salary)abort(404);

        $exUser = User::where('id',$salary->user_id)->first();
        $exUser->paid = max(0, (($exUser->paid ?? 0) - $salary->amount));
        $exUser->balance = ($exUser->balance ?? 0) + $salary->amount;
        $exUser->update();

        $user = User::where('id',$salary->user_id)->first();
        $user->paid = ($user->paid ?? 0) + $request->amount;
        $user->balance = ($user->balance ?? 0) - $request->amount;
        $user->update();

        $salary->date = $request->date;
        $salary->amount = $request->amount;
        $salary->remarks = $request->remarks;
        $salary->save();

        return redirect()->back()->with('success', 'Payment Updated Successfully');

    }

    public function deletePayment(Request $request, $id){
        

        $salary = Salary::where('id',$id)->first();
        if(!$salary)abort(404);

        $exUser = User::where('id',$salary->user_id)->first();
        $exUser->paid = max(0, (($exUser->paid ?? 0) - $salary->amount));
        $exUser->balance = ($exUser->balance ?? 0) + $salary->amount;
        $exUser->update();

        $salary->delete();

        return redirect()->back()->with('success', 'Payment Deleted Successfully');

    }

    public function paymentList(Request $request, $id){

        $salaries = Salary::where('user_id', $id);

        $defaultFilter = true;

        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d', strtotime($request->from));
            $to = date('Y-m-d', strtotime($request->to));
            $salaries = $salaries->whereBetween('salaries.date', [$from, $to]);
            $defaultFilter = false;
        }

        if($defaultFilter){
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
            $salaries = $salaries->whereBetween('salaries.date', [$startOfMonth, $endOfMonth]);
        }

        $salaries = $salaries->get();

        return view('frontend.pages.staff.payment_list',compact('salaries', 'id', 'request'));
    }
}
