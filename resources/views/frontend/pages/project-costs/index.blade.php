@extends('frontend.layouts.app')

@section('content')
    <div class="content container-fluid">

        <div class="row justify-content-center">
            <div class="col-sm-10">
                <!-- Page Header -->
                <div class="content-page-header d-flex justify-content-between align-items-center">
                    <h3 class="page-title mb-0">All Project Costs</h3>
                    <a href="{{ route('project-costs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Cost
                    </a>
                </div>
            </div>

            <!-- Centered Table/Card -->
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-fluid">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projectCosts as $cost)
                                            <tr>
                                                <td>{{ $cost->project->project_name }}</td>
                                                <td>{{ $cost->category->name }}</td>
                                                <td>{{ number_format($cost->amount, 2) }}</td>
                                                <td>{{ $cost->cost_date->format('M d, Y') }}</td>
                                                <td class="d-flex align-items-center">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('project-costs.edit', $cost->id) }}">
                                                                        <i class="far fa-edit me-2"></i>Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $cost->id }}').submit(); }"
                                                                        class="dropdown-item" href="javascript:void(0)">
                                                                        <i class="far fa-trash-alt me-2"></i>Delete
                                                                    </a>
                                                                    <form id="serviceDelete{{ $cost->id }}"
                                                                        action="{{ route('project-costs.destroy', $cost->id) }}"
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

                            <div class="mt-3">
                                {{ $projectCosts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
