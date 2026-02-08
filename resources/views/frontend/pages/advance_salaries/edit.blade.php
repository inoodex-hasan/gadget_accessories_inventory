@extends('frontend.layouts.app')
@section('content')
    <div class="row justify-content-center p-3">
        <div class="col-md-10">
            <div class="card p-4 shadow">
                <h4 class="mb-4">Edit Advance Salary</h4>

                <form action="{{ route('advance.update', $advance->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Employee</label>
                            <select name="employee_id" class="form-control" required>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ $advance->employee_id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} ({{ $emp->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ $advance->amount }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Request Date</label>
                            <input type="date" name="request_date" value="{{ $advance->request_date }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $advance->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ $advance->status == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ $advance->status == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $advance->notes }}</textarea>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary">Update</button>
                            <a href="{{ route('advance.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
