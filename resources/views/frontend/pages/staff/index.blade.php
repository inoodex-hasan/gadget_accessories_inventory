@extends('frontend.layouts.app')

@section('content')

@if(session('employeListAuthenticated'))
<div class="content container-fluid">
    <div class="page-header">
        <div class="content-page-header">
            <h5>Staff</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if(session('sweet_alert'))
                    <script>
                        Swal.fire({
                            icon: '{{ session('sweet_alert.type') }}',
                            title: '{{ session('sweet_alert.title') }}',
                            text: '{{ session('sweet_alert.text') }}',
                        });
                    </script>
                @endif

                <div class="card-header align-items-center d-flex">
                    <div class="flex-shrink-0">
                        <a href="javascript:void(0)" onclick="autoLogout()"  class="btn btn-info" style="background: #fe3727 !important; color: #fff; border:none;">Exit</a>
                        <a href="{{ route('staff.create') }}"  class="btn btn-info" style="background: #fe3727 !important; color: #fff; border:none;">Create Staff</a>
                    </div>
                </div>


                <form action="{{route('staff.index')}}" method="get">
                    <div class="row">
                        <div class="col-12 col-md-2">
                            <label for="">From</label>
                            <input type="date" name="from" class="form-control" value="{{isset($request) ? $request->from : ''}}">
                        </div>
                        <div class="col-12 col-md-2">
                        <label for="">To</label><br>
                        <input type="date" name="to" class="form-control" value="{{isset($request) ? $request->to : ''}}">
                        </div>
                        
                        <div class="col-12 col-md-2">
                            <label for="">Search By</label><br>
                            <select name="serach_by" id="" class="form-select">
                                <option value="">--Select--</option>
                                <option value="name" {{ (isset($request) && $request->serach_by == 'name') ? 'selected' : ''}} >Name</option>
                                <option value="phone" {{ (isset($request) && $request->serach_by == 'phone') ? 'selected' : ''}}>Phone</option>
                                <option value="email" {{ (isset($request) && $request->serach_by == 'email') ? 'selected' : ''}}>Email</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                        <label for="">Search Key</label><br>
                        <input type="text" name="key" class="form-control" value="{{isset($request) ? $request->key : ''}}">
                        </div>
                        <div class="col-12 col-md-2">
                        <label for=""></label>
                        <button type="submit" name="search_for" value="filter" class="btn btn-primary" style="margin-top:25px;">Search</button>
                        <label for=""></label>
                        <button type="submit" name="search_for" value="pdf" class="btn btn-primary" style="margin-top:25px;"><i class="fe fe-download"></i></button>
                        </div>
                    </div>
                </form>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTbl" style="margin-bottom: 300px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Joining Date</th>
                                    <th>Earning Amount</th>
                                    <th>received Amount</th>
                                    <th>Salary</th>
                                    <th>Days</th>
                                    <th>Balance</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalsalary = 0; $totalDays = 0; $total = 0; $totalPaid = 0; $totalEarn = 0; $totalReceived = 0; @endphp
                                @foreach ($users as $item)
                                    @php 
                                        $totalsalary += $item->salary;
                                        $total += $item->balance;
                                        $totalDays += $item->days;
                                        $totalPaid += $item->paid;
                                        $totalEarn += (isset($salarySummary[$item->id]) ? $salarySummary[$item->id] : 0);
                                        $totalReceived += (isset($SalaryPaymentSummary[$item->id]) ? $SalaryPaymentSummary[$item->id] : 0);
                                     @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>

                                            <div class="dropdown dropdown-action">
                                                <a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                <ul>
                                                    <li>
                                                    <a class="dropdown-item" style="font-weight: 500;font-size: 13px;color: #878A99;padding: 10px 20px 10px;" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#payModal{{ $item->id }}">
                                                        <i class="far fa-edit me-2"></i>Make Payment </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" target="_blank" href="{{route('staff.payment_list', $item->id)}}">
                                                            <i class="far fa-edit me-2"></i>Payment List </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#documentModal{{ $item->id }}">
                                                            <i class="far fa-edit me-2"></i>View Documents</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('attendance.index', ['user_id' => $item->id])}}">
                                                            <i class="far fa-edit me-2"></i>View Attendance</a>
                                                    </li>
                                                    <li>
                                                    <a href="javascript:void(0)" style="font-weight: 500;font-size: 13px;color: #878A99;padding: 10px 20px 10px;" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                        <i class="far fa-edit me-2"></i>Delete </a>
                                                    </li>
                                                    <li>
                                                    <a class="dropdown-item" href="{{ route('staff.edit', $item->id) }}">
                                                        <i class="far fa-edit me-2"></i>Edit </a>
                                                    </li>
                                                </ul>
                                                </div>
                                            </div>

                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <img width="20px" 
                                                src="{{ $item->images ? asset('frontend/users/' . $item->images) : asset('frontend/default-user.png') }}" 
                                                alt="User Image">
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $item->name }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $item->email }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $item->phone }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $item->department ?? 'N/A' }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $item->designation ?? 'N/A' }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ $item->joining_date ? \Carbon\Carbon::parse($item->joining_date)->format('d M Y') : 'N/A' }}</td>

                                        <td style="text-align: center; vertical-align: middle;">${{ number_format((isset($salarySummary[$item->id]) ? $salarySummary[$item->id] : 0), 2) }}</td>
                                        <td style="text-align: center; vertical-align: middle;">${{ number_format((isset($SalaryPaymentSummary[$item->id]) ? $SalaryPaymentSummary[$item->id] : 0), 2) }}</td>

                                        <td style="text-align: center; vertical-align: middle;">${{ number_format($item->salary, 2) }}</td>
                                        <td style="text-align: center; vertical-align: middle;">{{ number_format($item->days) }}</td>
                                        <td style="text-align: center; vertical-align: middle;">${{ number_format($item->balance) }}</td>
                                        <td style="text-align: center; vertical-align: middle;">${{ number_format($item->paid) }}</td>
                                    
                                        <td style="text-align: center; vertical-align: middle;">
                                            <span class="badge {{ $item->status == '1' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $item->status == '1' ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        
                                    </tr>
                            
                                    <!-- Delete Confirmation Modal -->
                                    <div id="deleteModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete <strong style="color: darkorange">{{ $item->name }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('staff.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pay Confirmation Modal -->
                                    <div id="payModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{route('staff.create_payment')}}" method="post">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Pay Salary</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="user_id" value="{{ $item->id }}">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label class="form-label">Date*</label>
                                                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="date" placeholder="Date" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Amount*</label>
                                                                <input type="number" class="form-control" value="{{ old('amount') }}" name="amount" placeholder="Amount" required>
                                                            </div>                                  

                                                            <div class="col-12">
                                                                <label class="form-label">Remarks</label>
                                                                <input type="text" class="form-control" value="{{ old('remarks') }}" name="remarks" placeholder="Remarks">
                                                            </div>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @if($item->documents || true)
                                        <button type="button" class="btn btn-sm btn-info d-none" data-bs-toggle="modal" data-bs-target="#documentModal{{ $item->id }}">
                                            View
                                        </button>
                                
                                        <!-- Document Modal -->
                                        <div class="modal fade" id="documentModal{{ $item->id }}" tabindex="-1" aria-labelledby="documentModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="documentModalLabel{{ $item->id }}">Documents for {{ $item->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $documents = json_decode($item->documents, true); // Decode stored JSON array
                                                        @endphp
                                
                                                        @if(is_array($documents) && count($documents) > 0)
                                                            @foreach ($documents as $document)
                                                                @php
                                                                    $fileExtension = pathinfo($document, PATHINFO_EXTENSION);
                                                                @endphp
                                
                                                                <div class="mb-3 text-center">
                                                                    @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                                                                        <img src="{{ asset('frontend/documents/' . $document) }}" class="img-fluid" alt="Document Image">
                                                                    @elseif($fileExtension == 'pdf')
                                                                        <iframe src="{{ asset('frontend/documents/' . $document) }}" width="100%" height="400px"></iframe>
                                                                    @else
                                                                        <p>Download the document:</p>
                                                                        <a href="{{ asset('frontend/documents/' . $document) }}" download class="btn btn-primary">Download</a>
                                                                    @endif
                                                                </div>
                                                                <hr>
                                                            @endforeach
                                                        @else
                                                            <p>No documents available.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="9" style="text-align: right;">Total</td>
                                    <td>{{$totalEarn}}</td>
                                    <td>{{$totalReceived}}</td>
                                    <td>{{$totalsalary}}</td>
                                    <td >{{$totalDays}}</td>
                                    <td >${{$total}}</td>
                                    <td >${{$totalPaid}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@else
<div class="content container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-5">
                <div class="card-body">
                    <div class="d-flex justify-content-center">

                    
                    <form action="{{route('verify-pin')}}" method="post">
                        @csrf
                        <div class="input-block d-block">
                            <input  type="password" class="from-control mr-3" name="pin" id="pin_code" style="padding:10px; border-radius:001rem; margin-right: 10px;" placeholder="Enter PIN">
                        </div><br>
                        <div class="add-customer-btns text-left d-block">
                            <button type="submit"   class="btn customer-btn-save">Submit</button>
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endif


<script>
    let inactivityTimeout;
    const timeoutDuration = 2 * 60 * 1000; // 5 minutes

    function resetInactivityTimer() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(autoLogout, timeoutDuration);
    }

    function autoLogout() {
        window.location.href = "{{route('employe_list_logout')}}"; // Redirect to logout route
    }

    // Reset timer on user activity
    document.addEventListener("mousemove", resetInactivityTimer);
    document.addEventListener("keydown", resetInactivityTimer);
    resetInactivityTimer(); // Initialize timer

    // PIN verification
    function verifyPIN() {
        let pin = $("#pin_code").val();

        $.ajax({
            url: "/verify-pin",
            type: "POST",
            data: {
                pin: pin,
                _token: "{{ csrf_token() }}" // CSRF token for security
            },
            success: function(response) {
                console.log(response);
                if (response == "success") {
                    location.reload();
                } else {
                    alert("Invalid PIN. Try again.");
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    }


</script>

@endsection
