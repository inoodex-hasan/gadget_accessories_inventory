@extends('frontend.layouts.app')
@section('content')
    <div class="row justify-content-center p-3">
        <div class="col col-sm-10">
            <div class="card p-4 shadow">
                <h2 class="mb-3">Edit Salary</h2>
                <form method="POST" action="{{ route('salary.update', $salary->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Employee</label>
                            <select class="form-control" disabled style="background-color: #e9ecef;">
                                <option value="">Select</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ $salary->employee_id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} ({{ $emp->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="employee_id" value="{{ $salary->employee_id }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Month <span class="text-danger">*</span></label>
                            <select class="form-control" disabled style="background-color: #e9ecef;">
                                @php
                                    $months = [
                                        '01' => 'January',
                                        '02' => 'February',
                                        '03' => 'March',
                                        '04' => 'April',
                                        '05' => 'May',
                                        '06' => 'June',
                                        '07' => 'July',
                                        '08' => 'August',
                                        '09' => 'September',
                                        '10' => 'October',
                                        '11' => 'November',
                                        '12' => 'December',
                                    ];
                                @endphp
                                @foreach ($months as $key => $month)
                                    <option value="{{ date('Y', strtotime($salary->month)) }}-{{ $key }}"
                                        {{ substr($salary->month, 5, 2) == $key ? 'selected' : '' }}>
                                        {{ $month }} {{ date('Y', strtotime($salary->month)) }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="month" value="{{ $salary->month }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Basic Salary</label>
                            <input type="number" step="any" class="form-control" value="{{ $salary->basic_salary }}"
                                disabled style="background-color: #e9ecef;">
                            <input type="hidden" name="basic_salary" value="{{ $salary->basic_salary }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Advance</label>
                            <input type="number" step="any" class="form-control" value="{{ $salary->advance }}"
                                disabled style="background-color: #e9ecef;">
                            <input type="hidden" name="advance" value="{{ $salary->advance }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Allowance</label>
                            <input type="number" step="any" name="allowance" class="form-control"
                                value="{{ $salary->allowance }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Deduction</label>
                            <input type="number" step="any" name="deduction" class="form-control"
                                value="{{ $salary->deduction }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="payment_status" class="form-control">
                                <option value="paid" {{ $salary->payment_status == 'paid' ? 'selected' : '' }}>Paid
                                </option>
                                <option value="unpaid" {{ $salary->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Payment Date</label>
                            <input type="date" name="payment_date" class="form-control"
                                value="{{ old('payment_date', $salary->payment_date ?? date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Notes</label>
                            <textarea name="note" class="form-control" rows="3">{{ old('note', $salary->note ?? '') }}</textarea>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('salary.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
