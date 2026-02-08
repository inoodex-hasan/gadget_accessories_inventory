@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="content-page-header d-flex justify-content-between">
            <h5>Advance Salary Requests</h5>
            {{-- <a href="{{ route('advance.create') }}" class="btn btn-primary">+ New Request</a> --}}
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Amount</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($advances as $key => $advance)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $advance->employee->name ?? 'N/A' }}</td>
                        <td>{{ number_format($advance->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($advance->request_date)->format('d M, Y') }}</td>
                        <td>
                            @if ($advance->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($advance->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $advance->notes ?? '—' }}</td>
                        <td>
                            <a href="{{ route('advance.edit', $advance->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('advance.destroy', $advance->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
