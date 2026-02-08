@extends('frontend.layouts.app')

@section('content')
    <div class="content container-fluid col-sm-10">

        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5>TA/DA List</h5>
                <a href="{{ route('ta-da.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-circle me-2"></i> Create TA/DA
                </a>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Purpose</th>
                    <th>Payment Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($taDas as $ta)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ta->employee->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($ta->date)->format('d M Y') }}</td>
                        <td>{{ $ta->type }}</td>
                        <td>{{ $ta->amount }}</td>
                        <td>{{ $ta->purpose }}</td>
                        <td>{{ $ta->payment_type }}</td>
                        <td class="d-flex align-items-center">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('ta-da.edit', $ta->id) }}">
                                                <i class="far fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a onclick="if (confirm('Are you sure to delete the customer?')) { document.getElementById('delete{{ $ta->id }}').submit(); }"
                                                class="dropdown-item" href="javascript:void(0)">
                                                <i class="far fa-trash-alt me-2"></i>Delete
                                            </a>
                                            <form id="delete{{ $ta->id }}"
                                                action="{{ route('ta-da.destroy', $ta->id) }}" method="POST"
                                                style="display:none;">
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
@endsection
