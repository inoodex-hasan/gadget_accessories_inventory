<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    public function index(Request $request){


       $users = User::where('status','1')->get();

       $attendances = Attendance::join('users','users.id','=','attendances.user_id');


       $defaultFilter = true;

        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d', strtotime($request->from));
            $to = date('Y-m-d', strtotime($request->to));
            $attendances = $attendances->whereBetween('attendances.date', [$from, $to]);
            $defaultFilter = false;
        }

        $user = null;
        if ($request->user_id != "") {
            $attendances = $attendances->where('attendances.user_id', $request->user_id);
            $defaultFilter = false;
            $user = User::where('id',$request->user_id)->first();
        }
       
        if($defaultFilter){
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
            $attendances = $attendances->whereBetween('attendances.date', [$startOfMonth, $endOfMonth]);
        }
        $attendances = $attendances->select('attendances.*','users.name')->get();

        if($request->search_for == 'pdf'){
            $pdf = Pdf::loadView('pdf.attendance', compact('users', 'user', 'attendances', 'request'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('Attendance.pdf');
        }

        return view('frontend.pages.attendance.index', compact('users','attendances','request'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'check_out' => 'required|date_format:H:i',
            'remarks' => 'nullable|string|max:255',
        ]);

        $user = User::where('id',$request->user_id)->first();
        $user->days = ($user->days ?? 0) + 1;
        $user->balance = ($user->balance ?? 0) + $user->salary;
        $user->update();

        $attendance = new Attendance;
        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->check_in = $request->check_in;
        $attendance->check_out = $request->check_out;
        $attendance->salary = $user->salary;
        $attendance->days = 1;
        $attendance->remarks = $request->remarks;
        $attendance->save();

       return redirect()->back()->with(['success' => getNotify(2)]);
    }

    public function update(Request $request, $id)
    {

        // return $request->all();

        // Validate the incoming request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'check_in' => 'required',
            'check_out' => 'required',
            'remarks' => 'nullable|string|max:255',
        ]);



        // Store attendance data
        $attendance = Attendance::where('id', $id)->first();
        if(!$attendance) abort(404);

        $exUser = User::where('id',$attendance->user_id)->first();
        $exUser->days = max(0, (($exUser->days ?? 0) - $attendance->days));
        $exUser->balance = ($exUser->balance ?? 0) - $attendance->salary;
        $exUser->update();

        $user = User::where('id',$request->user_id)->first();
        $user->days = ($user->days ?? 0) + 1;
        $user->balance = ($user->balance ?? 0) + $user->salary;
        $user->update();

        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->check_in = $request->check_in;
        $attendance->check_out = $request->check_out;
        $attendance->salary = $user->salary;
        $attendance->days = 1;
        $attendance->remarks = $request->remarks;
        $attendance->save();


       return redirect()->back()->with(['success' => getNotify(2)]);
    }

    public function destroy(Request $request, $id){
        $attendance = Attendance::where('id', $id)->first();
        if(!$attendance) abort(404);

        $exUser = User::where('id',$attendance->user_id)->first();
        $exUser->days = max(0, (($exUser->days ?? 0) - $attendance->days));
        $exUser->balance = ($exUser->balance ?? 0) - $attendance->salary;
        $exUser->update();

        $attendance->delete();

        return redirect()->back()->with(['success' => getNotify(3)]);
    }
}
