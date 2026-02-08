@extends('frontend.layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
            border-width: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 0 !important;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }
    </style>

    <div class="content container-fluid col-sm-10">
        <div class="card mb-0">
            <div class="card-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="content-page-header">
                        <h5>Edit Daily Expense</h5>
                    </div>
                </div>
                <!-- /Page Header -->

                <form action="{{ route('dailyExpenses.update', $expense->id) }}" method="post">
                    @csrf @method('PUT')
                    <div class="row g-3">

                        <!-- Date -->
                        <div class="col-md-4">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control"
                                value="{{ old('date', $expense->date) }}" required>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Employee</label>
                            <select id="employeeSelect" class="form-control" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ old('employee_id', $expense->employee_id) == $emp->id ? 'selected' : '' }}
                                        data-basic_salary="{{ $emp->basic_salary }}">
                                        {{ $emp->name }} ({{ $emp->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Expense Category -->
                        <div class="col-md-4">
                            <label class="form-label">Expense Category <span class="text-danger">*</span></label>
                            <select name="expense_category_id" class="form-select " required>
                                <option value="">-- Select --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('expense_category_id', $expense->expense_category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-4">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control"
                                value="{{ old('amount', $expense->amount) }}" required>
                        </div>

                        <!-- Spend Method -->
                        <div class="col-md-4">
                            <label class="form-label">Spend Method <span class="text-danger">*</span></label>
                            <select name="spend_method" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="cash" {{ $expense->spend_method == 'cash' ? 'selected' : '' }}>Cash
                                </option>
                                <option value="card" {{ $expense->spend_method == 'card' ? 'selected' : '' }}>Card
                                </option>
                                <option value="bank_transfer"
                                    {{ $expense->spend_method == 'bank_transfer' ? 'selected' : '' }}>
                                    Bank Transfer</option>
                            </select>
                        </div>

                        <!-- Remarks -->
                        <div class="col-md-8">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Enter remarks (optional)">{{ old('remarks', $expense->remarks) }}</textarea>
                        </div>

                    </div>

                    <div class="mt-3 text-start">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                width: '100%'
            });
        });
    </script>
@endsection
