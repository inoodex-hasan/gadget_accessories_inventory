@extends('frontend.layouts.app')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Add Project Cost</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('project-costs.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Project *</label>
                                    <select name="project_id" class="form-control" required>
                                        <option value="">Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cost Category *</label>
                                    <select name="cost_category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Amount *</label>
                                    <input type="number" name="amount" class="form-control" step="0.01" required>
                                </div>
                            </div>
                       <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date *</label>
                                <input type="date" name="cost_date" class="form-control" 
                                    value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Add Cost</button>
                            <a href="{{ route('project-costs.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection