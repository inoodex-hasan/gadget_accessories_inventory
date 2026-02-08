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

    <div class="content container-fluid">
        <div class="card mb-0">
            <div class="card-body">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="content-page-header">
                        <h5>Add Daily Expense</h5>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('dailyExpenses.store') }}" method="post">
                            @csrf
                            <div class="form-group-item">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Date <span class="text-danger">*</span></label>
                                            <input type="date" name="date" class="form-control"
                                                value="{{ old('date', now()->format('Y-m-d')) }}" required
                                                autocomplete="off">
                                        </div>

                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label>Employee</label>
                                        <select id="employeeSelect" class="form-control" name="employee_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}" data-salary="{{ $emp->salary }}">
                                                    {{ $emp->name }} ({{ $emp->employee_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Expense Category <span class="text-danger">*</span></label>
                                            <select name="expense_category_id" class="form-select" required>
                                                <option value="">--Select--</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Amount <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="amount" class="form-control"
                                                placeholder="Enter Amount" value="{{ old('amount') }}" required
                                                autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Spend Method <span class="text-danger">*</span></label>
                                            <select name="spend_method" class="form-select" required>
                                                <option value="">--Select--</option>
                                                <option value="cash"
                                                    {{ old('spend_method') == 'cash' ? 'selected' : '' }}>Cash Payment
                                                </option>
                                                <option value="card"
                                                    {{ old('spend_method') == 'card' ? 'selected' : '' }}>Card Payment
                                                </option>
                                                <option value="bank_transfer"
                                                    {{ old('spend_method') == 'bank_transfer' ? 'selected' : '' }}>Other
                                                    Payment</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Remarks </label>
                                            <textarea name="remarks" class="form-control" placeholder="Enter Remarks" required>{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="add-customer-btns text-left">
                                <button type="submit" class="btn customer-btn-save">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.js-example-basic-single').select2({
                tags: true,
            });
            $('.js-example-basic-single-no-new-value').select2({
                tags: true,
            });
        });

        function getTotal() {
            var price = document.getElementById("price").value.trim();
            var qty = document.getElementById("qty").value.trim();
            if (price < 0) {
                document.getElementById("price").value = 0;
                price = 0;
            }
            if (qty < 0) {
                document.getElementById("qty").value = 0;
                qty = 0;
            }
            document.getElementById("total").value = price * qty;
            calculateDue();
        }

        function calculateDue() {
            var bill = (document.getElementById("total").value.trim() * 1) ?? 0;
            var paid_amount = (document.getElementById("paid_amount").value.trim() * 1) ?? 0;
            document.getElementById("due_amount").value = Math.max(0, bill - paid_amount);
        }
    </script>

    <script>
        const selectElements = document.querySelectorAll('.phoneCode');
        selectElements.forEach(selectElement => {
            selectElement.addEventListener('focus', function() {
                // console.log('open');
                const options = selectElement.options;
                Array.from(options).forEach(option => {
                    option.text = option.dataset.showdefault;
                });
            });
            selectElement.addEventListener('blur', function() {
                // console.log('close')
                const options = selectElement.options;
                Array.from(options).forEach(option => {
                    option.text = option.dataset.show;
                });
            });

            selectElement.addEventListener('change', function() {
                // console.log('close')
                const options = selectElement.options;
                Array.from(options).forEach(option => {
                    option.text = option.dataset.show;
                });
                selectElement.blur();
            });

            selectElement.addEventListener('mousedown', function(event) {
                // console.log('close')
                const options = selectElement.options;
                Array.from(options).forEach(option => {
                    option.text = option.dataset.show;
                });
                selectElement.blur();
            });

            // Handling touchend event for touch devices
            selectElement.addEventListener('touchend', function(event) {
                // console.log('close');
                const options = selectElement.options;
                Array.from(options).forEach(option => {
                    option.text = option.dataset.show;
                });
                selectElement.blur();
            });
        });
    </script> --}}
@endsection
