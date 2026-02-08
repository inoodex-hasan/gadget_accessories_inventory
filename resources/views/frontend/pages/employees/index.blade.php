@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <div class="content-page-header d-flex justify-content-between align-items-center">
                <h5>Employees</h5>

                {{-- <a href="{{ route('employees.create') }}" class="btn btn-primary mb-3">+ Add Employee</a> --}}
            </div>

            {{-- Search & Filter --}}
            <form action="{{ route('employees.index') }}" method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search by Name, Phone, or ID">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="all">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-success w-100">Filter</button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        {{-- <th>Image</th> --}}
                        <th>Status</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($employees as $key => $employee)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->phone }}</td>
                            {{-- <td class="align-middle">
                                @if ($employee->image)
                                    <img src="{{ asset('uploads/employees/' . $employee->image) }}" width="50"
                                        class="rounded">
                                @endif
                            </td> --}}

                            <td>
                                @if ($employee->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="d-flex align-items-center">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul>
                                            {{-- <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('employees.view', $employee->id) }}">
                                                    <i class="far fa-eye me-2"></i>View
                                                </a>
                                            </li> --}}
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('employees.edit', $employee->id) }}">
                                                    <i class="far fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $employee->id }}').submit(); }"
                                                    class="dropdown-item" href="javascript:void(0)">
                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                </a>
                                                <form id="serviceDelete{{ $employee->id }}"
                                                    action="{{ route('employees.destroy', $employee->id) }}" method="POST"
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
    </div>
@endsection
