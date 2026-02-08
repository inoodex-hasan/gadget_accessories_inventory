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
    <div class="row justify-content-center p-3">
        <div class="col-sm-10">
            <div class="card p-4 shadow">
                <h2 class=" mb-3">Edit Employee</h2>

                <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" value="{{ $employee->employee_id }}"
                                class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ $employee->name }}" class="form-control"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ $employee->email }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ $employee->phone }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Designation</label>
                            <input type="text" name="designation" value="{{ $employee->designation }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Join Date</label>
                            <input type="date" name="join_date" value="{{ $employee->join_date }}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Salary (basic)</label>
                            <input type="number" step="0.01" name="salary" value="{{ $employee->salary }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Current Image</label><br>
                            @if ($employee->image)
                                <img src="{{ asset('uploads/employees/' . $employee->image) }}" width="80">
                            @endif
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>New Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                    </div>

                    <div class="py-3">
                        <button class="btn btn-danger">Update Employee</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
