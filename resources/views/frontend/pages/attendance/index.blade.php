
@extends('frontend.layouts.app') 
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
	.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
  border-color: transparent transparent #888 transparent;
  border-width: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 0 !important;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}
</style>

<div class="content container-fluid">
    <div class="card mb-0">
        <div class="card-body">
            <!-- Page Header -->
            <div class="page-header">
                <div class="content-page-header">
                <h5>Attendance</h5>
                    <div class="list-btn">
                        <ul class="filter-list">
                        <li class="d-none">
                            <a class="btn btn-filters w-auto popup-toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Filter">
                            <span class="me-2">
                                <img src="assets/img/icons/filter-icon.svg" alt="filter">
                            </span>Filter </a>
                        </li>
                        <li class="d-none">
                            <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Download">
                            <a href="#" class="btn-filters" data-bs-toggle="dropdown" aria-expanded="false">
                                <span>
                                <i class="fe fe-download"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <ul class="d-block">
                                <li>
                                    <a class="d-flex align-items-center download-item" href="javascript:void(0);" download="">
                                    <i class="far fa-file-pdf me-2"></i>PDF </a>
                                </li>
                                <li>
                                    <a class="d-flex align-items-center download-item" href="javascript:void(0);" download="">
                                    <i class="far fa-file-text me-2"></i>CVS </a>
                                </li>
                                </ul>
                            </div>
                            </div>
                        </li>
                        <li class="d-none">
                            <a class="btn-filters" href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Print" data-bs-original-title="Print">
                            <span>
                                <i class="fe fe-printer"></i>
                            </span>
                            </a>
                        </li>
                        <li class="d-none">
                            <a class="btn btn-import" href="javascript:void(0);">
                            <span>
                                <i class="fe fe-check-square me-2"></i>Import Customer </span>
                            </a>
                        </li>
                        <li>
                            <a class="btn btn-primary " href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Attendance </a>
                        </li>
                        </ul>

                        <div id="addModal" class="modal fade" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="text-center mt-2 mb-4">
                                            <div class="auth-logo">
                                                <a href="{{ route('index') }}" class="logo logo-dark">
                                                    <span class="logo-lg">
                                                        <img src="{{asset('assets/img/logo.png')}}" alt="Logo" height="42">
                                                    </span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="text-left mt-2 mb-4">
                                            <h4>Add Attendance</h4>
                                        </div>
                                        <form class="px-3" action="{{route('attendance.store')}}" method="post">
                                            @csrf

                                            <div class="row">

                                            <div class="mb-3 col-12 col-md-4">
                                                <label>User <span class="text-danger">*</span></label><br>
                                                <select name="user_id" id="" class="form-control js-example-basic-single" tabindex="0" required>
                                                    <option value=""></option>
                                                    @foreach($users as $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                                <div class="mb-3 col-12 col-md-4">
                                                    <label for="modalFullName" class="form-label">Date<span class="text-danger">*</span></label>
                                                    <input class="form-control" type="date" id="modalFullName" name="date" value="{{date('Y-m-d')}}" required>
                                                </div>

                                                <div class="mb-3 col-12 col-md-4">
                                                    <label for="check_in" class="form-label">Check In<span class="text-danger">*</span></label>
                                                    <input class="form-control" type="time" id="check_in" name="check_in" required>
                                                </div>

                                                <div class="mb-3 col-12 col-md-4">
                                                    <label for="check_out" class="form-label">Check Out<span class="text-danger">*</span></label>
                                                    <input class="form-control" type="time" id="check_out" name="check_out" required>
                                                </div>

                                                <div class="mb-3 col-12 col-md-4">
                                                    <label for="remarks" class="form-label">Remarks</label>
                                                    <input class="form-control" type="text" id="remarks" name="remarks">
                                                </div>

                                            </div>

                                                <div class="mb-3 col-12 justify-content-left">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
                                                </div>
                                            </div>
                                        
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <form action="{{route('attendance.index')}}" method="get">
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
                            <label for="">User</label><br>
                            <select name="user_id" id="" class="form-control js-example-basic-single" tabindex="0">
                                <option value="">--Select--</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ isset($request) ? ($request->user_id == $user->id ? 'selected' : '') : '' }}>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-2">
                            <label for=""></label>
                            <button type="submit" name="search_for" value="filter" class="btn btn-primary" style="margin-top:25px;">Search</button>
                            <button type="submit" name="search_for" value="pdf" class="btn btn-primary" style="margin-top:25px;"><i class="fe fe-download"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Page Header -->
             
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-table">
                        <div class="card-body">
                        <div class="table-responsive">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-center table-hover datatable dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead class="thead-light">
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending">#</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending">Name</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone Number: activate to sort column ascending">Date</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Message: activate to sort column ascending">Check In</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Message: activate to sort column ascending">Check Out</th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr role="row" class="">
                                            <td class="sorting_1">{{ $loop->index + 1 }}</td> 
                                            <td>{{ $attendance->name }}</td>
                                            <td>{{ $attendance->date }}</td>
                                            <td>{{ $attendance->check_in }}</td>
                                            <td>{{ $attendance->check_out }}</td>
                                            <td class="d-flex align-items-center">
                                            
                                                <a class="btn btn-primary " href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editModal{{$attendance->id}}">
                                                    <i class="fa fa-edit me-2 text-white" aria-hidden="true"></i> 
                                                </a>

                                                <a class="btn btn-primary ml-2" style="margin-left:5px !important;" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal{{$attendance->id}}">
                                                    <i class="fas fa-trash-alt ml-2 text-white"></i>
                                                </a>

                                                <div id="deleteModal{{$attendance->id}}" class="modal fade" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="text-center mt-2 mb-4">
                                                                    <div class="auth-logo">
                                                                        <a href="{{ route('index') }}" class="logo logo-dark">
                                                                            <span class="logo-lg">
                                                                                <img src="{{asset('assets/img/logo.png')}}" alt="Logo" height="42">
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="text-left mt-2 mb-4">
                                                                    <h6>Delete Attendance</h4>
                                                                </div>
                                                                <form class="px-3" action="{{ route('attendance.destroy', $attendance->id) }}" method="post">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                        <div class="mb-3 col-12 d-flex justify-content-end">
                                                                            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                

                                                <div id="editModal{{$attendance->id}}" class="modal fade" style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="text-center mt-2 mb-4">
                                                                    <div class="auth-logo">
                                                                        <a href="{{ route('index') }}" class="logo logo-dark">
                                                                            <span class="logo-lg">
                                                                                <img src="{{asset('assets/img/logo.png')}}" alt="Logo" height="42">
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="text-left mt-2 mb-4">
                                                                    <h4>Edit Attendance</h4>
                                                                </div>
                                                                    <form class="px-3" action="{{route('attendance.update', $attendance->id)}}" method="post">
                                                                        @csrf
                                                                        @method('put')

                                                                        <div class="row">

                                                                        <div class="mb-3 col-12 col-md-4">
                                                                            <label for="user_id{{$attendance->id}}">User <span class="text-danger">*</span></label><br>
                                                                            <select name="user_id" id="user_id{{$attendance->id}}" class="form-control js-example-basic-single" tabindex="0" required>
                                                                                <option value=""></option>
                                                                                @foreach($users as $user)
                                                                                    <option value="{{$user->id}}" {{$attendance->user_id == $user->id ? 'selected' : '' }}>{{$user->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                            <div class="mb-3 col-12 col-md-4">
                                                                                <label for="modalFullName{{$attendance->id}}" class="form-label">Date<span class="text-danger">*</span></label>
                                                                                <input class="form-control" type="date" id="modalFullName{{$attendance->id}}" name="date" value="{{$attendance->date}}" required>
                                                                            </div>

                                                                            <div class="mb-3 col-12 col-md-4">
                                                                                <label for="check_in{{$attendance->id}}" class="form-label">Check In<span class="text-danger">*</span></label>
                                                                                <input class="form-control"type="time" id="check_in{{$attendance->id}}" value="{{$attendance->check_in}}" name="check_in" required>
                                                                            </div>

                                                                            <div class="mb-3 col-12 col-md-4">
                                                                                <label for="check_out{{$attendance->id}}" class="form-label">Check Out<span class="text-danger">*</span></label>
                                                                                <input class="form-control" type="time" id="check_out{{$attendance->id}}" value="{{$attendance->check_out}}" name="check_out" required>
                                                                            </div>

                                                                            <div class="mb-3 col-12 col-md-4">
                                                                                <label for="remarks{{$attendance->id}}" class="form-label">Remarks</label>
                                                                                <input class="form-control" type="text" id="remarks{{$attendance->id}}" value="{{$attendance->remarks}}"  name="remarks">
                                                                            </div>

                                                                        </div>

                                                                            <div class="mb-3 col-12 justify-content-left">
                                                                                <button class="btn btn-primary" type="submit">Submit</button>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                    </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                
                            <div class="dataTables_length" id="DataTables_Table_0_length">
                                <label>
                                <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                </label>
                            </div>
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                <ul class="pagination">
                                <li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous">
                                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">
                                    <i class="fa fa-angle-double-left me-2"></i> Previous </a>
                                </li>
                                <li class="paginate_button page-item active">
                                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                </li>
                                <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next">
                                    <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0" class="page-link">Next <i class=" fa fa-angle-double-right ms-2"></i>
                                    </a>
                                </li>
                                </ul>
                            </div>
                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 6 of 6 entries</div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
	
    $('.js-example-basic-single').select2({
		tags: true,
	});

	$('.js-example-basic-single-no-new-value').select2({
		
	});

	
  });

  function calculateDue(){
	var bill = (document.getElementById("bill").value.trim() * 1)??0;
	var paid_amount = (document.getElementById("paid_amount").value.trim() * 1)??0;
	
	document.getElementById("due_amount").value = Math.max(0, bill-paid_amount);
  }

  	function updateSelected(selectId,type) {
		console.log(type)
		// const selectedOption = select.options[select.selectedIndex];
		// select.setAttribute('data-flag', selectedOption.getAttribute('data-flag'));

		const select = document.getElementById(selectId);
		const options = select.options;
		Array.from(options).forEach(option => {
			if(type=="reset")option.text = option.dataset.showdefault;
			if(type=="modify")option.text = option.dataset.show;
		});
	}
</script>

<script>
  const selectElements = document.querySelectorAll('.phoneCode');
  selectElements.forEach(selectElement => {
    selectElement.addEventListener('focus', function() {
		// console.log('open');
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.showdefault;
		});
    });
    selectElement.addEventListener('blur', function() {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
    });

	selectElement.addEventListener('change', function() {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
		selectElement.blur();
    });

	selectElement.addEventListener('mousedown', function(event) {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
		selectElement.blur();
  	});

	// Handling touchend event for touch devices
    selectElement.addEventListener('touchend', function(event) {
        // console.log('close');
        const options = selectElement.options;
        Array.from(options).forEach(option => {
            option.text = option.dataset.show;
        });
        selectElement.blur();
    });
  });
</script>

@endsection