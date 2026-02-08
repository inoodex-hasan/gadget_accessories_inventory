@extends('frontend.layouts.app') 
@section('content')

<style>
  th, td{
    padding: 5px 0px 0px 10px !important;
  }
</style>

<div class="content container-fluid">
  
  <!-- Page Header -->
  <div class="page-header">
    <div class="content-page-header">
      <h5>Services</h5>
     
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
            <a class="btn btn-primary" href="{{route('service.create')}}">
              <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Service </a>
          </li>
        </ul>
      </div>
    </div>
    <form action="{{route('service.index')}}" method="get">
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
          <label for="">Service Type</label><br>
          <select name="service_type" id="" class="form-select">
            <option value="">--Select--</option>
            <option value="paid" {{ (isset($request) && $request->service_type == 'paid') ? 'selected' : ''}} >Paid</option>
            <option value="due" {{ (isset($request) && $request->service_type == 'due') ? 'selected' : ''}}>Due</option>
          </select>
        </div>
        <div class="col-12 col-md-2">
          <label for="">Search By</label><br>
          <select name="serach_by" id="" class="form-select">
            <option value="">--Select--</option>
            <option value="name" {{ (isset($request) && $request->serach_by == 'name') ? 'selected' : ''}} >Name</option>
            <option value="phone" {{ (isset($request) && $request->serach_by == 'phone') ? 'selected' : ''}}>Phone</option>
            <option value="email" {{ (isset($request) && $request->serach_by == 'email') ? 'selected' : ''}}>Email</option>
            <option value="product_name" {{ (isset($request) && $request->serach_by == 'product_name') ? 'selected' : ''}}>Product Name</option>
            <option value="product_number" {{ (isset($request) && $request->serach_by == 'product_number') ? 'selected' : ''}}>Product Number</option>
            <option value="repaired_by" {{ (isset($request) && $request->serach_by == 'repaired_by') ? 'selected' : ''}}>Repaired By</option>
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
                    <th class="no-sort sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">Actions</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Date</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                    <!-- <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Email</th> -->
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Phone</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Product Name</th>
                    <!-- <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">EMEI Number</th> -->
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Price</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Discount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Bill</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Paid Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Due Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Warranty</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Remaining Days</th>
                    <!-- <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Repaired By</th> -->
                  </tr>
                </thead>
                <tbody>
                @foreach ($services as $service)
                  <tr role="row" class="odd">
                    <td class="sorting_1">{{$loop->index+1}}</td>
                    <td class="d-flex align-items-center">
                      <div class="dropdown dropdown-action">
                        <a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                          <ul>
                            <li>
                              <a class="dropdown-item" href="{{route('payments', ['id' => $service->id, 'payment_for' => '1'])}}">
                                <i class="far fa-edit me-2"></i>Get Payments </a>
                            </li>
                            <li>
                              <a class="dropdown-item" target="_blank" href="{{route('service.invoice', $service->id)}}">
                                <i class="far fa-edit me-2"></i>Invoice </a>
                            </li>
                            <li>
                              <a onclick="if (confirm('Are you sure to complete the service?')) { document.getElementById('serviceConfirm{{$service->id}}').submit(); }" class="dropdown-item" href="javascript:void(0)">
                                <i class="far fa-edit me-2"></i>Completed </a>
                                <form id="serviceConfirm{{$service->id}}" action="{{route('service.makecomplate', $service->id)}}" method="post">
                                  @csrf
                                </form>
                            </li>
                            <li>
                              <a onclick="if (confirm('Are you sure to delete the service?')) { document.getElementById('serviceDelete{{$service->id}}').submit(); }" class="dropdown-item" href="javascript:void(0)">
                                <i class="far fa-edit me-2"></i>Delete </a>
                                <form id="serviceDelete{{$service->id}}" action="{{route('service.destroy', $service->id)}}" method="post">
                                  @csrf
                                  @method('DELETE')
                                </form>
                            </li>
                            <li>
                              <a class="dropdown-item" href="{{route('service.edit', $service->id)}}">
                                <i class="far fa-edit me-2"></i>Edit </a>
                            </li>
                            <li class="d-none">
                              <a class="dropdown-item" href="">
                                <i class="far fa-eye me-2"></i>View </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <h2 class="table-avatar"> <span>{{$service->created_at->format('Y-m-d')}}</span></h2>
                    </td>
                    <td>
                      <h2 class="table-avatar">
                        <a href="profile.html" class="avatar avatar-md me-2 d-none">
                          <img class="avatar-img rounded-circle" src="assets/img/profiles/avatar-14.jpg" alt="User Image">
                        </a>
                        <a href="javascript:void(0)">{{$service->name}}
                        </a>
                      </h2>
                    </td>
                    <!-- <td><h2 class="table-avatar"> <span>{{$service->email}}</span></h2></td> -->
                    <td>
                      <h2 class="table-avatar"> <span>{{$service->phone}}</span></h2>
                    </td>
                    <td> {{$service->product_name}} </td>
                    <!-- <td> {{$service->product_number}} </td> -->
                    <td> ${{$service->total}} </td>
                    <td> ${{$service->discount}} </td>
                    <td> ${{$service->bill}} </td>
                    <td> ${{$service->paid_amount}} </td>
                    <td> ${{$service->due_amount}} </td>
                    <td> {{$service->warranty_duration}} </td>
                      @php
                          $warrantyPeriod = $service->warranty_duration; 
                          $createdAt = \Carbon\Carbon::parse($service->created_at);
                          $warrantyExpiresAt = $createdAt->addDays($warrantyPeriod);
                          $remainingDays = now()->diffInDays($warrantyExpiresAt, false);
                      @endphp
                    <td> {{ max(0, $remainingDays) }} </td>

                    <!-- <td> {{$service->repaired_by}} </td> -->
                    
                    
                    
                  </tr>
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

@endsection