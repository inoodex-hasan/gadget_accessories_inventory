@extends('frontend.layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
            border-width: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 0 !important;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }
    </style>

    <div class="content container-fluid">

        <div class="row justify-content-center">
            <div class="col-sm-10">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="content-page-header d-flex justify-content-between align-items-center">
                        <h5>All Project Costs</h5>
                        <div class="list-btn">
                            <ul class="filter-list mb-0">
                                <li>
                                    <a class="btn btn-primary" href="{{ route('project-costs.create') }}">
                                        <i class="fa fa-plus-circle me-2"></i> Add Cost
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Modal: Add Category -->
                <div id="add-expense-category-modal" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form method="POST" action="{{ route('expense-categories.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter category name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select" required>
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Table -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-table">
                            <div class="card-body">
                                <div class="table-fluid">
                                    <table class="table table-center table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th class="no-sort">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenseCategories as $category)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $category->name }}</td>
                                                    <td>
                                                        @if ($category->status)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="dropdown dropdown-action">
                                                            <a href="#" class="btn-action-icon"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul>
                                                                    <li>
                                                                        <a class="dropdown-item" data-bs-toggle="modal"
                                                                            data-bs-target="#edit-expense-category-modal{{ $category->id }}">
                                                                            <i class="far fa-edit me-2"></i>Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a onclick="if(confirm('Are you sure?')) document.getElementById('deleteForm{{ $category->id }}').submit();"
                                                                            class="dropdown-item" href="javascript:void(0)">
                                                                            <i class="far fa-trash-alt me-2"></i>Delete
                                                                        </a>
                                                                        <form id="deleteForm{{ $category->id }}"
                                                                            method="POST"
                                                                            action="{{ route('expense-categories.destroy', $category->id) }}"
                                                                            style="display: none;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Modal: Edit Category -->
                                                <div id="edit-expense-category-modal{{ $category->id }}" class="modal fade"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <form method="POST"
                                                                    action="{{ route('expense-categories.update', $category->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Category Name <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" name="name"
                                                                            class="form-control"
                                                                            value="{{ $category->name }}" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Status</label>
                                                                        <select name="status" class="form-select" required>
                                                                            <option value="1"
                                                                                {{ $category->status == 1 ? 'selected' : '' }}>
                                                                                Active</option>
                                                                            <option value="0"
                                                                                {{ $category->status == 0 ? 'selected' : '' }}>
                                                                                Inactive</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
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
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
