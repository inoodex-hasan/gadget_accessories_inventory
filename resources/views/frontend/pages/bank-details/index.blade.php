@extends('layouts.app')

@section('content')
    <div class="container-fluid col-sm-10 p-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Bank Details</h4>
                        <a href="{{ route('bank-details.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Bank Details
                        </a>
                    </div>
                    <div class="card-body">

                        <div class="table-fluid">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Account Name</th>
                                        <th>Bank Name</th>
                                        <th>Branch</th>
                                        <th>Account Number</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Default</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($banks as $bank)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $bank->account_name }}</td>
                                            <td>{{ $bank->bank_name }}</td>
                                            <td>{{ $bank->branch }}</td>
                                            <td>{{ $bank->account_number }}</td>
                                            <td>{{ $bank->account_type }}</td>
                                            <td>
                                                <span class="badge bg-{{ $bank->is_active ? 'success' : 'danger' }}">
                                                    {{ $bank->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($bank->is_default)
                                                    <span class="badge bg-success">Default</span>
                                                @else
                                                    <form action="{{ route('bank-details.set-default', $bank->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-s btn-outline-primary">Set
                                                            Default</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td class="d-flex align-items-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul>

                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('bank-details.edit', $bank->id) }}">
                                                                    <i class="far fa-edit me-2"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $bank->id }}').submit(); }"
                                                                    class="dropdown-item" href="javascript:void(0)">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                                <form id="serviceDelete{{ $bank->id }}"
                                                                    action="{{ route('bank-details.destroy', $bank->id) }}"
                                                                    method="POST" style="display:none;">
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
@endsection
