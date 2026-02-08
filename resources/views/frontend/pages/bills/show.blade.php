@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Bill Details - {{ $bill->reference_number }}</h4>
                            <div>
                                <a href="{{ route('bills.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                                <a href="{{ route('bills.download', $bill->id) }}" class="btn btn-success">
                                    Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Bill Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Bill Information</h5>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Bill Number:</th>
                                        <td>{{ $bill->bill_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Reference Number:</th>
                                        <td>{{ $bill->reference_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bill Date:</th>
                                        <td>{{ $bill->bill_date->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        {{-- <th>Bill Type:</th>
                                        <td>
                                            <span class="badge bg-{{ $bill->bill_type == 'sale' ? 'primary' : 'info' }}">
                                                {{ ucfirst($bill->bill_type) }} Bill
                                            </span>
                                        </td> --}}
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Work Order:</th>
                                        <td>{{ $bill->work_order_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Subtotal:</th>
                                        <td>৳ {{ number_format($bill->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total Amount:</th>
                                        <td>৳ {{ number_format($bill->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount in Words:</th>
                                        <td><em>{{ $amount_in_words }}</em></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Client Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Client Information</h5>
                            </div>
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="20%">Client Name:</th>
                                        <td>{{ $recipient_organization }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $recipient_address }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th>Attention To:</th>
                                        <td>{{ $attention_to ?? 'N/A' }}</td>
                                    </tr> --}}
                                </table>
                            </div>
                        </div>

                        <!-- Bill Items -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom-0 pb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 text-dark">Bill Items</h5>
                                            <span class="badge bg-primary rounded-pill">{{ $bill->billItems->count() }}
                                                items</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-hover mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th width="5%" class="text-center ps-4">#</th>
                                                        <th width="50%">Description</th>
                                                        <th width="15%" class="text-center">Qty</th>
                                                        <th width="15%" class="text-end">Unit Price</th>
                                                        <th width="15%" class="text-end pe-4">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($bill->billItems as $index => $item)
                                                        <tr class="border-bottom">
                                                            <td class="text-center ps-4">
                                                                <span
                                                                    class="badge bg-light text-dark rounded-circle">{{ $index + 1 }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="text-dark">{!! nl2br(e($item->description)) !!}</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="fw-semibold">{{ number_format($item->quantity) }}</span>
                                                                <small
                                                                    class="text-muted d-block">{{ $item->unit ?? 'Pcs' }}</small>
                                                            </td>
                                                            <td class="text-end text-muted">৳
                                                                {{ number_format($item->unit_price, 2) }}</td>
                                                            <td class="text-end fw-bold text-success pe-4">৳
                                                                {{ number_format($item->total, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="bg-light">
                                                    <tr>
                                                        <td colspan="3" class="text-end fw-bold border-0 ps-4">Subtotal
                                                        </td>
                                                        <td colspan="2" class="text-end fw-bold border-0 pe-4">৳
                                                            {{ number_format($bill->subtotal, 2) }}</td>
                                                    </tr>
                                                    <tr class="border-top">
                                                        <td colspan="3" class="text-end fw-bold fs-5 border-0 ps-4">Total
                                                            Amount</td>
                                                        <td colspan="2"
                                                            class="text-end fw-bold fs-5 text-primary border-0 pe-4">
                                                            ৳ {{ number_format($bill->total_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        @if ($terms_conditions)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Terms and Conditions</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            {!! nl2br(e($terms_conditions)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Bank Details -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Bank Details</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Account Name:</strong> {{ $bank_details['account_name'] }}</p>
                                                <p><strong>Bank Name:</strong> {{ $bank_details['bank_name'] }}</p>
                                                <p><strong>Branch:</strong> {{ $bank_details['branch'] }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Account Number:</strong> {{ $bank_details['account_number'] }}
                                                </p>
                                                <p><strong>Account Type:</strong> {{ $bank_details['account_type'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Details -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Company Details</h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Company Name:</strong> {{ $company['name'] }}</p>
                                                <p><strong>Signatory Name:</strong> {{ $company['signatory_name'] }}</p>
                                                <p><strong>Designation:</strong> {{ $company['signatory_designation'] }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Phone:</strong> {{ $company['phone'] ?? 'N/A' }}</p>
                                                <p><strong>Email:</strong> {{ $company['email'] ?? 'N/A' }}</p>
                                                <p><strong>Website:</strong> {{ $company['website'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
