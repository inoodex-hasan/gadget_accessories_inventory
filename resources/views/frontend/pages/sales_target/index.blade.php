@extends('frontend.layouts.app') 
@section('content')

<div class="content container-fluid">
  <!-- Page Header -->
  <div class="page-header">
    <div class="content-page-header d-flex justify-content-between align-items-center">
        <h5>Sales Target Lists</h5>
        <a class="btn btn-primary" href="{{route('salesTarget.create')}}">
          <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Sales Target</a>
    </div>
   
  </div>

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
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending">Month</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Amount: activate to sort column ascending">Amount</th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $target)
                        <tr role="row" class="odd">
                            <td class="sorting_1">{{ $loop->index + 1 }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($target->month)->format('F, Y') }} <!-- Display the month and year -->
                            </td>
                            <td> ${{ number_format($target->amount, 2) }} </td> <!-- Display amount formatted to 2 decimal places -->
                            <td class="d-flex align-items-center">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('salesTarget.edit', $target->id) }}">
                                                    <i class="far fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a onclick="if (confirm('Are you sure to delete this target?')) { document.getElementById('targetDelete{{$target->id}}').submit(); }" class="dropdown-item" href="javascript:void(0)">
                                                    <i class="far fa-edit me-2"></i>Delete
                                                </a>
                                                <form id="targetDelete{{$target->id}}" action="{{ route('salesTarget.destroy', $target->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
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