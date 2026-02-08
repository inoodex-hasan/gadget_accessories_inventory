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
      <h5>Daily Sales</h5>
     
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
            <a class="btn btn-primary" href="{{route('salesTarget.index')}}">
             Sales Target List</a>
          </li>
          <li class="">
            <a class="btn btn-primary" href="{{route('salesTarget.create')}}">
              <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Sales Target</a>
          </li>
          <li class="">
            <a class="btn btn-primary" href="{{route('dailySales.create')}}">
              <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Daily Sales </a>
          </li>
        </ul>
      </div>
    </div>
    <form action="{{route('dailySales.index')}}" method="get">
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
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Description</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Card Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Cash Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Other Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Total Amount</th>
                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Assign Person</th>

                  </tr>
                </thead>
                <tbody>
                @foreach ($sales as $service)
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
                              <a class="dropdown-item" href="{{route('dailySales.edit', $service->id)}}">
                                <i class="far fa-edit me-2"></i>Edit </a>
                            </li>
                            <li>
                              <a onclick="if (confirm('Are you sure to delete the daily Sales?')) { document.getElementById('serviceDelete{{$service->id}}').submit(); }" class="dropdown-item" href="javascript:void(0)">
                                <i class="far fa-edit me-2"></i>Delete </a>
                                <form id="serviceDelete{{$service->id}}" action="{{route('dailySales.destroy', $service->id)}}" method="post">
                                  @csrf
                                  @method('DELETE')
                                </form>
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
                      <h2 class="table-avatar"> <span>{{$service->date}}</span></h2>
                    </td>
                    <td><span>{{$service->description}}</span></td>
                    <td> ${{$service->card_amount}} </td>
                    <td> ${{$service->cash_amount}} </td>
                    <td> ${{$service->others_amount}} </td>
                    <td> ${{$service->total_amount}} </td>
                    <td> {{get_names($users,$service->assigned_person_id)}} </td>
                    
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

@endsection