@extends('frontend.layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <!-- <div class="col-sm-10"> -->

            <!-- Page Title -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Add Customer</h4>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
            </div>

            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('customers.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" pattern="[0-9]{11}" maxlength="11"
                                    placeholder="Enter phone number" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter email">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Enter address"
                                    required></textarea>
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