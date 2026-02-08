@extends('frontend.layouts.app')
@section('content')
    <div class="row justify-content-center p-3">
        <div class="col-md-10">
            <div class="card p-4 shadow">
                <h4 class="mb-4">Request Advance Salary</h4>

                <form action="{{ route('advance.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-control" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step="0.01" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Request Date</label>
                            <input type="date" name="request_date" value="{{ date('Y-m-d') }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Enter Notes or Purpose"></textarea>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">Save</button>
                            <a href="{{ route('advance.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
