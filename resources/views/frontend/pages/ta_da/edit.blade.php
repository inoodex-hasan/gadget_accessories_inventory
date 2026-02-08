@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid col-sm-10">
        <div class="page-header">
            <div class="content-page-header">
                <h5>Edit TA/DA</h5>
            </div>
        </div>

        <div class="card p-4">
            <form action="{{ route('ta-da.update', $tada->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Employee (ID)</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $tada->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->employee_id }} - {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $tada->date }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Amount</label>
                        <input type="number" name="amount" step="0.01" class="form-control" value="{{ $tada->amount }}"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                            <option value="TA" {{ $tada->type == 'TA' ? 'selected' : '' }}>TA</option>
                            <option value="DA" {{ $tada->type == 'DA' ? 'selected' : '' }}>DA</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Payment Type</label>
                        <select name="payment_type" class="form-control" required>
                            <option value="Advance">Advance</option>
                            <option value="Claim">Claim</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purpose / Notes</label>
                        <textarea name="purpose" class="form-control">{{ old('purpose', $tada->purpose ?? '') }}</textarea>
                    </div>


                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('ta-da.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection
