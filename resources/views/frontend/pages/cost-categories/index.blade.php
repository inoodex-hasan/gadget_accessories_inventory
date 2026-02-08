@extends('frontend.layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-start mt-5">
        <div class="col-12 col-md-10">
            <div class="card shadow-sm rounded-3">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Cost Categories</h4>
                    <a href="{{ route('cost-categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Category
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-fluid">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->description ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="d-flex justify-content-center align-items-center">
                                            <div class="dropdown">
                                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">

                                                    <a class="dropdown-item"
                                                        href="{{ route('cost-categories.edit', $category->id) }}">
                                                        <i class="far fa-edit me-2"></i>Edit
                                                    </a>

                                                    <a onclick="if (confirm('Are you sure to delete this brand?')) { document.getElementById('deleteBrand{{ $category->id }}').submit(); }"
                                                        class="dropdown-item" href="javascript:void(0)">
                                                        <i class="far fa-trash-alt me-2"></i>Delete
                                                    </a>

                                                    <form id="deleteBrand{{ $category->id }}"
                                                        action="{{ route('cost-categories.destroy', $category->id) }}"
                                                        method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($categories->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No categories found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
