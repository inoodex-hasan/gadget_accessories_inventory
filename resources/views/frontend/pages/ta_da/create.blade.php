@extends('frontend.layouts.app')

@section('content')
    <div class="content container-fluid col-sm-10">

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add TA/DA</h5>
            <a href="{{ route('ta-da.index') }}" class="btn btn-secondary btn-sm">
                Back
            </a>
        </div>

        <div class="card mt-3">
            <div class="card-body">

                <form action="{{ route('ta-da.store') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->employee_id }} - {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ old('date', $ta->date ?? date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="TA">TA (Travel Allowance)</option>
                                <option value="DA">DA (Daily Allowance)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                placeholder="Enter Amount" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Purpose / Notes</label>
                            <textarea name="purpose" class="form-control" rows="3" placeholder="Enter Purpose or Notes"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Payment Type</label>
                            <select name="payment_type" class="form-control" required>
                                <option value="Advance">Advance</option>
                                <option value="Claim">Claim</option>
                            </select>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>

            </div>
        </div>

    </div>
@endsection
