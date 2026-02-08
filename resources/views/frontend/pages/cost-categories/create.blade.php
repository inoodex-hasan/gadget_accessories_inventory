@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid d-flex justify-content-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-3 my-5">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center">{{ isset($category) ? 'Edit' : 'Add' }} Cost Category</h3>

                    <form
                        action="{{ isset($category) ? route('cost-categories.update', $category->id) : route('cost-categories.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($category))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter category name"
                                value="{{ old('name', $category->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" placeholder="Enter description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('cost-categories.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                {{ isset($category) ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
