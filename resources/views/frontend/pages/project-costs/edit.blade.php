@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-sm-8">

                <!-- Page Header -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Brand</h4>
                    <a href="{{ route('project-costs.index') }}" class="btn btn-secondary">Back</a>
                </div>

                <!-- Card -->
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="{{ route('project-costs.update', $projectCost->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Project <span class="text-danger">*</span></label>
                                        <select name="project_id"
                                            class="form-control @error('project_id') is-invalid @enderror" required>
                                            <option value="">Select Project</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ old('project_id', $projectCost->project_id) == $project->id ? 'selected' : '' }}>
                                                    {{ $project->project_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Cost Category <span class="text-danger">*</span></label>
                                        <select name="cost_category_id"
                                            class="form-control @error('cost_category_id') is-invalid @enderror" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('cost_category_id', $projectCost->cost_category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('cost_category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="amount"
                                            class="form-control @error('amount') is-invalid @enderror" step="0.01"
                                            value="{{ old('amount', $projectCost->amount) }}" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="cost_date"
                                            class="form-control @error('cost_date') is-invalid @enderror"
                                            value="{{ old('cost_date', $projectCost->cost_date->format('Y-m-d')) }}"
                                            required>
                                        @error('cost_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $projectCost->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                                <a href="{{ route('project-costs.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Cost Summary Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Cost Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Created At</label>
                        <p class="mb-0">{{ $projectCost->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Updated At</label>
                        <p class="mb-0">{{ $projectCost->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Current Project</label>
                        <p class="mb-0 fw-semibold">{{ $projectCost->project->project_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Current Category</label>
                        <p class="mb-0">{{ $projectCost->category->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('project-costs.show', $projectCost->id) }}" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i> View Details
                        </a>
                        <form action="{{ route('project-costs.destroy', $projectCost->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this cost?')">
                                <i class="fas fa-trash me-1"></i> Delete Cost
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    </div>
@endsection
