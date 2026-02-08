@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid">

        <div class="row justify-content-center">


            <!-- Page Header -->
            <div class="page-header mb-3">
                <div class="content-page-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Brands</h5>
                    <a class="btn btn-primary" href="{{ route('brands.create') }}">
                        <i class="fa fa-plus-circle me-2"></i>Add Brand
                    </a>
                </div>
            </div>

            <!-- Card with Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <div class="table-fluid">
                        <table id="brandTable" class="table table-hover align-middle text-center w-100">
                            <thead class="bg-light">
                                <tr>
                                    <th class="w-auto">#</th>
                                    <th class="w-auto">Name</th>
                                    <th class="w-auto">Status</th>
                                    <th class="w-auto">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $brand->name }}</td>

                                        <td>
                                            @if ($brand->status == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="d-flex justify-content-center align-items-center">
                                            <div class="dropdown">
                                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>

                                                <div class="dropdown-menu dropdown-menu-end shadow-sm rounded-3">

                                                    <a class="dropdown-item" href="{{ route('brands.edit', $brand->id) }}">
                                                        <i class="far fa-edit me-2"></i>Edit
                                                    </a>

                                                    <a onclick="if (confirm('Are you sure to delete this brand?')) { document.getElementById('deleteBrand{{ $brand->id }}').submit(); }"
                                                        class="dropdown-item" href="javascript:void(0)">
                                                        <i class="far fa-trash-alt me-2"></i>Delete
                                                    </a>

                                                    <form id="deleteBrand{{ $brand->id }}"
                                                        action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

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
@endsection