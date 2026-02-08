@extends('frontend.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <!-- <div class="col-sm-8"> -->

            <h3 class="mb-4 fw-bold">{{ isset($brand) ? 'Edit Brand' : 'Add New Brand' }}</h3>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="POST"
                        action="{{ isset($brand) ? route('brands.update', $brand->id) : route('brands.store') }}">
                        @csrf
                        @if (isset($brand))
                            @method('PUT')
                        @endif


                        <div class="row mb-3">
                            <div class="col-6 mb-3">
                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter brand name" required>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="1" {{ isset($brand) && $brand->status == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ isset($brand) && $brand->status == 0 ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
                                {{ isset($brand) ? 'Update' : 'Save' }}
                            </button>
                        </div>

                    </form>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2({});
            $('.js-example-basic-single-no-new-value').select2({});
        });
    </script>
@endsection