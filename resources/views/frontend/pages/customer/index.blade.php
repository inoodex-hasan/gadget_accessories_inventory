@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid">

        <div class="page-header">
            <h5>Customers</h5>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <form action="{{ route('customers.index') }}" method="GET" class="d-flex gap-2">

                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search Name / Phone / ID" style="width: 260px;">

                <select name="status" class="form-select" style="width: 150px;">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button class="btn btn-primary">Search</button>
            </form>

            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                + Add customer
            </a>

        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    {{-- <th>Image</th> --}}
                    {{-- <th>Status</th> --}}
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $key => $customer)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        {{-- <td>
                            @if ($customer->image ?? 'N/A')
                            <img src="{{ asset('uploads/customers/' . $customer->image) }}" width="50">
                            @endif
                        </td> --}}
                        {{-- <td>
                            @if ($customer->status == 'active')
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td> --}}
                        <td class="d-flex align-items-center">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}">
                                                <i class="far fa-edit me-2"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a onclick="if (confirm('Are you sure to delete the customer?')) { document.getElementById('delete{{ $customer->id }}').submit(); }"
                                                class="dropdown-item" href="javascript:void(0)">
                                                <i class="far fa-trash-alt me-2"></i>Delete
                                            </a>
                                            <form id="delete{{ $customer->id }}"
                                                action="{{ route('customers.destroy', $customer->id) }}" method="POST"
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