@extends('frontend.layouts.app') 
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<style>
.select2-container--default .select2-selection--single {
    outline: none;
}

.select2-container .select2-search--inline .select2-search__field {
    outline: none !important;
    border: none;
    box-shadow: none;
    width: 100% !important;
}

  th, td{
    padding: 5px 0px 0px 10px !important;
  }

</style>
<div class="content container-fluid">
  <!-- Page Header -->
  <div class="page-header">
    <div class="content-page-header">
      <h5>Bookings</h5>
      <div class="list-btn">
        <ul class="filter-list">
          <li class="d-none">
            <a class="btn btn-filters w-auto popup-toggle" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Filter">
              <span class="me-2">
                <img src="assets/img/icons/filter-icon.svg" alt="filter">
              </span>Filter </a>
          </li>          
          <li>
            <a class="btn btn-primary" href="https://quickphonefixandmore.com/adminLogin" target="_blank">
              <i class="fa fa-trash me-2" aria-hidden="true"></i>Clear Booking </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /Page Header -->
  <!-- Search Filter -->
  <div id="filter_inputs" class="card filter-card">
    <div class="card-body pb-0">
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="input-block mb-3">
            <label>Name</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="input-block mb-3">
            <label>Email</label>
            <input type="text" class="form-control">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="input-block mb-3">
            <label>Phone</label>
            <input type="text" class="form-control">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Search Filter -->
  <div class="row">
    <div class="col-sm-12">
      <div class="card-table">
        <div class="card-body">
          <div class="table-responsive">
            <div id="DataTables_Table_0_wrapper" class=" no-footer">
                <table class="table table-center table-hover no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                    <thead class="thead-light">
                      <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending">#</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending">Actions</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending">Name</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone Number: activate to sort column ascending">Phone</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone Number: activate to sort column ascending">Email</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Message: activate to sort column ascending">Message</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Message: activate to sort column ascending">Device Name</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="EMI/Serial Number: activate to sort column ascending">EMI/Serial Number</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Address</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookingData as $index => $entry)
                        @if(!in_array($entry['_ID'] , $bookings))
                          <tr role="row" class="{{ $loop->odd ? 'odd' : 'even' }}">
                            <td class="sorting_1">{{ $index + 1 }}</td> <!-- Dynamically generating row number -->
                            <td class="d-flex align-items-center">
                              <div class="dropdown dropdown-action">
                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                  <ul>
                                    <li>
                                      <a class="dropdown-item " href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#signup-modal{{ $entry['_ID'] }}">
                                        <i class="far fa-eye me-2"></i>View Details
                                      </a>
                                    </li>
                                    <!-- Add more actions if needed -->
                                  </ul>
                                </div>
                              </div>
                              <div id="signup-modal{{ $entry['_ID'] }}" class="modal fade" style="display: none;" aria-hidden="true">
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
                                                <h4>Add to Service</h4>
                                            </div>

                                            @if(in_array($entry['_ID'] , $bookings))

                                              <form class="px-3" action="javascript:void(0)" method="post">
                                                  <div class="row">
                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalFullName" class="form-label">Name</label>
                                                        <input class="form-control" type="text" id="modalFullName" name="name" placeholder="Enter Name" value="{{ $entry['full_name'] }}" required readonly>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalPhoneNumber" class="form-label">Phone</label>
                                                        <input class="form-control" type="text" id="modalPhoneNumber" placeholder="Phone Number" name="phone" value="{{ $entry['phone_number'] }}" required readonly>
                                                    </div>

                                                    

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalAddress" class="form-label">Address</label>
                                                        <textarea type="text"  class="form-control" placeholder="Address" id="modalAddress" name="address" readonly>{{ $entry['address'] }}</textarea>

                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Product Name </label><br>
                                                      <input class="form-control" type="text" id="product_name" placeholder="Phone Number" name="product_name" value="{{ $entry['select_device'] }}" required readonly>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalEmiNumber" class="form-label">EMI/Serial Number</label>
                                                        <input type="text"  class="form-control" placeholder="Product EMEI or Serial number" name="product_number" value="{{ $entry['emi_number_or_serial_number'] }}" readonly>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalMessage" class="form-label">Service Details</label>
                                                        <textarea class="form-control" id="modalMessage" name="details" readonly>{{ $entry['message'] }}</textarea>
                                                    </div>
                                                  </div>
                                              </form>

                                            @else
                                              <form class="px-3" action="{{route('service.store')}}" method="post">
                                                  @csrf

                                                  <input type="text" name="is_booking" value="true" hidden>
                                                  <input type="text" name="booking_id" value="{{ $entry['_ID'] }}" hidden>

                                                  <div class="row">
                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalFullName" class="form-label">Name<span class="text-danger">*</span></label>
                                                        <input class="form-control" type="text" id="modalFullName" name="name" placeholder="Enter Name" value="{{ $entry['full_name'] }}" required>	
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalPhoneNumber" class="form-label">Phone <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                          <select name="country_code" id="country_code" class="form-select phoneCode" style="max-width:110px;">
                                                            @foreach (country_codes() as $key => $data)
                                                              <option  value="{{$key}}" data-show="{{$data['flag'].' '.$data['code']}}" data-showdefault="{{$data['flag'].' '.$data['code'].' '.$data['name']}}">{{$data['flag'].' '.$data['code']}}</option>
                                                            @endforeach
                                                          </select>
                                                          <input type="text"  class="form-control" id="modalPhoneNumber" placeholder="Phone Number" name="phone" value="{{ $entry['phone_number'] }}"  required autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Email </label>
                                                      <input type="email" name="email" class="form-control" placeholder="Enter Email Address" value="{{ $entry['email_address'] }}">
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalAddress" class="form-label">Address</label>
                                                        <textarea type="text"  class="form-control" placeholder="Address" id="modalAddress" name="address">{{ $entry['address'] }}</textarea>

                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Product Name <span class="text-danger">*</span></label><br>
                                                      <select name="product_name" id="" class="form-control js-example-basic-single" tabindex="0" required>
                                                        <option value=""></option>
                                                        
                                                        @foreach ($products as $product)
                                                          <option value="{{$product->id}}" {{ $entry['select_device'] ==  $product->name ? 'selected' : ''}}>{{$product->name}}</option>
                                                        @endforeach
                                                        

                                                        @if ($entry['select_device'] != "" && $entry['select_device'] != null && !in_array($entry['select_device'], $products->pluck('name')->toArray()))
                                                            <option value="{{ $entry['select_device'] }}" class="add-new-option" selected>{{ $entry['select_device'] }}</option>
                                                        @endif
                                                      </select>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalEmiNumber" class="form-label">EMI/Serial Number</label>
                                                        <input type="text"  class="form-control" placeholder="Product EMEI or Serial number" name="product_number" value="{{ $entry['emi_number_or_serial_number'] }}" >
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                        <label for="modalMessage" class="form-label">Service Details</label>
                                                        <textarea class="form-control" id="modalMessage" name="details" >{{ $entry['message'] }}</textarea>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Warranty Duration (In days) <span class="text-danger">*</span></label>
                                                      <input type="number"  class="form-control" placeholder="Warranty Duration" name="warranty_duration" value="{{ old('warranty_duration') }}" required>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Repaired By <span class="text-danger">*</span></label>
                                                      <Select class="form-select" name="repaired_by" required>
                                                          <option value="">--Select--</option>
                                                          @foreach ($users as $key => $user)
                                                            <option value="{{$key}}" {{ old('repaired_by') == $key ? 'selected' : '' }}>{{$user}}</option>
                                                          @endforeach
                                                      </Select>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="input-block mb-3">
                                                          <label>Total <span class="text-danger">*</span></label>
                                                          <input onchange="calculateDue('{{ $entry['_ID'] }}')" type="number"  class="form-control" placeholder="Price" id="total{{ $entry['_ID'] }}" name="total" value="{{ old('total') }}" required autocomplete="off" >
                                                        </div>
                                                      </div>

                                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="input-block mb-3">
                                                          <label>Discount </label>
                                                          <input type="number" onchange="calculateDue('{{ $entry['_ID'] }}')" class="form-control" placeholder="Discount" id="discount{{ $entry['_ID'] }}" name="discount" value="{{ old('discount') }}" autocomplete="off">
                                                        </div>
                                                      </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Price <span class="text-danger">*</span></label>
                                                      <input onchange="calculateDue('{{ $entry['_ID'] }}')" type="number"  class="form-control" placeholder="Price" id="bill{{ $entry['_ID'] }}" name="bill" value="{{ old('bill') }}" required readonly>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Paid Amount</label>
                                                      <input onchange="calculateDue('{{ $entry['_ID'] }}')" type="number"  class="form-control" placeholder="Paid Amount" id="paid_amount{{ $entry['_ID'] }}" name="paid_amount" value="{{ old('paid_amount') }}" >
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Due Amount</label>
                                                      <input type="number"  class="form-control" placeholder="Due Amount" id="due_amount{{ $entry['_ID'] }}" name="due_amount" value="{{ old('due_amount') }}" readonly>
                                                    </div>

                                                    <div class="mb-3 col-12 col-md-4">
                                                      <label>Payment Method </label>
                                                      <Select class="form-select" name="payment_method_id">
                                                          <option value="">--Select--</option>
                                                          @foreach (paymentMethods() as $key => $name)
                                                          <option value="{{$key}}" {{ old('payment_method_id') == $key ? 'selected' : '' }}>{{$name}}</option>
                                                          @endforeach
                                                      </Select>
                                                    </div>

                                                    <div class="mb-3 col-12 justify-content-left">
                                                      <button class="btn btn-primary" type="submit">Submit</button>
                                                    </div>
                                                  </div>
                                                
                                              </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </td>
                            <td>{{ $entry['full_name'] }}</td>
                            <td>{{ $entry['phone_number'] }}</td>
                            <td>{{ $entry['email_address'] }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($entry['message'], 20, '...') }}</td>
                            <td>{{ $entry['select_device'] }}</td>
                            <td>{{ $entry['emi_number_or_serial_number'] }}</td>
                            <td>{{ $entry['address'] }}</td>
                            @if(in_array($entry['_ID'] , $bookings))
                            <td><span class="badge bg-success">Completed</span></td>
                            @else
                            <td><span class="badge bg-primary">Pending</span></td>
                            @endif
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                  
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- jQuery (make sure it's loaded before Select2 JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.js-example-basic-single').select2({
		  tags: true,
	  });
    $('.js-example-basic-single').each(function() { 
        $(this).select2({ 
          dropdownParent: $(this).parent(),
          tags: true,
        });
    });
    $('.js-example-basic-single-no-new-value').select2({
	  });
    $('.js-example-basic-single-no-new-value').each(function() { 
        $(this).select2({ 
          dropdownParent: $(this).parent(),
        });
    })
  });

  function calculateDue(id){
    // var bill = (document.getElementById("bill"+id).value.trim() * 1)??0;
    // var paid_amount = (document.getElementById("paid_amount"+id).value.trim() * 1)??0;
    // document.getElementById("due_amount"+id).value = Math.max(0, bill-paid_amount);

    // var bill = (document.getElementById("bill").value.trim() * 1)??0;
    var total = (document.getElementById("total"+id).value.trim() * 1)??0;
    var discount = (document.getElementById("discount"+id).value.trim() * 1)??0;
    document.getElementById("bill"+id).value = Math.max(total-discount, 0);
    var bill = (document.getElementById("bill"+id).value.trim() * 1)??0;
    var paid_amount = (document.getElementById("paid_amount"+id).value.trim() * 1)??0;
    
    document.getElementById("due_amount"+id).value = Math.max(0, bill-paid_amount);
  }
</script>

@endsection