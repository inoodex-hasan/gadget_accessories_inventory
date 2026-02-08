@extends('frontend.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <!-- <div class="col-sm-8"> -->

            <!-- Page Header -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Brand</h4>
                <a href="{{ route('brands.index') }}" class="btn btn-secondary">Back</a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('brands.update', $brand->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $brand->name) }}"
                                    required>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="1" {{ old('status', $brand->status) == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ old('status', $brand->status) == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-start">
                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- </div> -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-single-no-new-value').select2();
        });
    </script>
@endsection