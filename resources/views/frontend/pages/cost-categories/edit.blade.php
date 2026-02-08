@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-sm-8">

                <!-- Page Header -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Cost Categpry</h4>
                    <a href="{{ route('cost-categories.index') }}" class="btn btn-secondary">Back</a>
                </div>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">
                        <form action="{{ route('cost-categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $category->name) }}" placeholder="Enter category name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                    placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-select" required>
                                    <option value="1" {{ $category->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-center gap-3">
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
                                    Update
                                </button>
                                <a href="{{ route('cost-categories.index') }}"
                                    class="btn btn-secondary px-5 py-2 rounded-pill">
                                    Cancel
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
