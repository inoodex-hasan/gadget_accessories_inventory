@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Company Details</h4>
                        <a href="{{ route('company-details.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Company Details
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-fluid">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Logo</th>
                                        <th>Company Name</th>
                                        <!--<th>Signatory</th>-->
                                        <th>Designation</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Default</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($company->photo)
                                                    <img src="{{ asset($company->photo) }}"alt="Photo" width="40"
                                                         >
                                                @else
                                                    <span>No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($company->name, 15) }}
                                            </td>
                                            <!--<td>{{ Str::limit($company->signatory_name, 15) }}-->
                                            </td>
                                            <td>{{ Str::limit($company->signatory_designation, 15) }}</td>
                                            <td>{{ $company->phone ?? 'N/A' }}</td>
                                            <td>{{ Str::limit($company->email, 15) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $company->is_active ? 'success' : 'danger' }}">
                                                    {{ $company->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($company->is_default)
                                                    <span class="badge bg-success">Default</span>
                                                @else
                                                    <form action="{{ route('company-details.set-default', $company->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">Set
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
                                                                    href="{{ route('company-details.edit', $company->id) }}">
                                                                    <i class="far fa-edit me-2"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $company->id }}').submit(); }"
                                                                    class="dropdown-item" href="javascript:void(0)">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                                <form id="serviceDelete{{ $company->id }}"
                                                                    action="{{ route('company-details.destroy', $company->id) }}"
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
