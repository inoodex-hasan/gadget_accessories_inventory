@extends('frontend.layouts.app')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="content-page-header">
            <h5>Payment List</h5>
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#payModal" class="btn btn-info" style="background: #fe3727 !important; color: #fff; border:none;">Pay Salary</a>

            <div id="payModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{route('staff.create_payment')}}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Pay Salary</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="user_id" value="{{ $id }}">
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

                <div class="card-header align-items-center">
                    
                       <form action="{{route('staff.payment_list', $id)}}" post="get">
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

                <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="dataTbl" style="margin-bottom: 300px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($salaries as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->date }}</td>
                                <td>${{ $item->amount }}</td>
                                <td>{{ $item->remarks }}</td>
                                <td>
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class=" btn-action-icon " data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                        <ul>
                                           
                                            <li>
                                            <a class="dropdown-item" href="javascript:void(0)" style="font-weight: 500;font-size: 13px;color: #878A99;padding: 10px 20px 10px;"  data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                                <i class="far fa-edit me-2"></i>Edit </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" style="font-weight: 500;font-size: 13px;color: #878A99;padding: 10px 20px 10px;" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                                    <i class="far fa-edit me-2"></i>Delete </a>
                                            </li>
                                        </ul>
                                        </div>
                                    </div>

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
                                            <form action="{{ route('staff.delete_payment', $item->id) }}" method="POST">
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
                            <div id="editModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('staff.update_payment', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Update Payment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label">Date*</label>
                                                    <input type="date" class="form-control" value="{{$item->date }}" name="date" placeholder="Date" required>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Amount*</label>
                                                    <input type="number" class="form-control" value="{{  $item->amount }}" name="amount" placeholder="Amount" required>
                                                </div>                                  

                                                <div class="col-12">
                                                    <label class="form-label">Remarks</label>
                                                    <input type="text" class="form-control" value="{{  $item->remarks }}" name="remarks" placeholder="Remarks">
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
                        @endforeach
                        </tbody>
                    </table>
                    
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
