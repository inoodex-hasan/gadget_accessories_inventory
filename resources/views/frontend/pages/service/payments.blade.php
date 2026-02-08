@extends('frontend.layouts.app') 
@section('content')

<div class="content container-fluid">
  <!-- Page Header -->
  <div class="page-header">
    <div class="content-page-header">
      <h5>Payments</h5>
     
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
          
        </ul>
      </div>
    </div>
    <form action="{{route('service.payments')}}" method="get">
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
          <label for="">Payment Method</label><br>
          <select name="payments_method" id="" class="form-select">
            <option value="">--Select--</option>
            @foreach (paymentMethods() as $key => $value)
                <option value="{{$key}}" {{ (isset($request) && $request->payments_method == $key) ? 'selected' : ''}}>{{$value}}</option>
            @endforeach
          </select>
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
          <div >
            <div>
              <table class="table table-center table-hover ">
                <thead class="thead-light">
                  <tr role="row">
                    <th>#</th>
                    <th>Date</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>

                @foreach ($payments as $payment)
                  <tr role="row" >
                    <td >{{$loop->index+1}}</td>
                    <td>{{$payment->created_at->format('Y-m-d')}}</td>
                    <td>{{getArrayData(paymentMethods(), $payment->payment_method_id)}}</td>
                    <td>{{$payment->amount}}</td>
                  </tr>
                  @php
                      if(!isset($methodWise[$payment->payment_method_id])) $methodWise[$payment->payment_method_id] = 0;
                      $methodWise[$payment->payment_method_id] += $payment->amount;
                      if(!isset($total)) $total = 0;
                      $total += $payment->amount;
                  @endphp
                @endforeach

                @if(isset($methodWise))
                    @foreach ($methodWise as $key => $value)
                        <tr>
                            <th colspan="3" style="text-align:right;">{{getArrayData(paymentMethods(), $key)}}</th>
                            <th>{{$value}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="3" style="text-align:right;">Total</th>
                        <th>{{$total}}</th>
                    </tr>

                @endif
                    
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