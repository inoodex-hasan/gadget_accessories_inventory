@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ isset($bankDetail) ? 'Edit' : 'Create' }} Bank Details</h4>
                        <a href="{{ route('bank-details.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($bankDetail) ? route('bank-details.update', $bankDetail->id) : route('bank-details.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($bankDetail))
                                @method('PUT')
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Name *</label>
                                        <input type="text" name="account_name" class="form-control"
                                            value="{{ old('account_name', $bankDetail->account_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name *</label>
                                        <input type="text" name="bank_name" class="form-control"
                                            value="{{ old('bank_name', $bankDetail->bank_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch *</label>
                                        <input type="text" name="branch" class="form-control"
                                            value="{{ old('branch', $bankDetail->branch ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Number *</label>
                                        <input type="text" name="account_number" class="form-control"
                                            value="{{ old('account_number', $bankDetail->account_number ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account Type *</label>
                                        <select name="account_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="Current"
                                                {{ old('account_type', $bankDetail->account_type ?? '') == 'Current' ? 'selected' : '' }}>
                                                Current</option>
                                            <option value="Savings"
                                                {{ old('account_type', $bankDetail->account_type ?? '') == 'Savings' ? 'selected' : '' }}>
                                                Savings</option>
                                            <option value="Salary"
                                                {{ old('account_type', $bankDetail->account_type ?? '') == 'Salary' ? 'selected' : '' }}>
                                                Salary</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Routing Number *</label>
                                        <input type="text" name="routing_number" class="form-control"
                                            value="{{ old('routing_number', $bankDetail->routing_number ?? '') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-check mt-4">
                                            <input type="checkbox" name="is_default" value="1"
                                                {{ old('is_default', $bankDetail->is_default ?? false) ? 'checked' : '' }}
                                                class="form-check-input" id="is_default">
                                            <label class="form-check-label" for="is_default">Set as Default</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $bankDetail->is_active ?? true) ? 'checked' : '' }}
                                                class="form-check-input" id="is_active">
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($bankDetail) ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
