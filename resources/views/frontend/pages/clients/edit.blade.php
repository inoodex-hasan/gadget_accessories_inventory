@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-sm-8">

                <!-- Page Header -->
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ isset($client) ? 'Edit Client' : 'Add New Client' }}</h4>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
                </div>

                <!-- Card -->
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4">

                        <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($client))
                                @method('PUT')
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $client->name ?? '') }}" required>
                                </div>

                                {{-- <div class="col-md-6 mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" class="form-control"
                                        value="{{ old('company_name', $client->company_name ?? '') }}">
                                </div> --}}

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control"
                                        value="{{ old('phone', $client->phone ?? '') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $client->email ?? '') }}" required>
                                </div>

                                {{-- <div class="col-md-6 mb-3">
                                    <label class="form-label">Tax Number</label>
                                    <input type="text" name="tax_number" class="form-control"
                                        value="{{ old('tax_number', $client->tax_number ?? '') }}">
                                </div> --}}

                                <div class="col-12 mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ old('address', $client->address ?? '') }}</textarea>
                                </div>
                                {{-- 
                                <div class="col-12 mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes ?? '') }}</textarea>
                                </div> --}}
                            </div>

                            <div class="text-end">
                                <a href="{{ route('clients.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($client) ? 'Update Client' : 'Create Client' }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
