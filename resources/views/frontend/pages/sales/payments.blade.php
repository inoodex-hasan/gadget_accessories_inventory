@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            @if (isset($sale))
                                Payment for Order: {{ $sale->order_no }}
                                <small class="text-muted"> - {{ $sale->customer->name ?? 'N/A' }}</small>
                            @else
                                All Payments
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">

                        {{-- <!-- Success/Error Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif --}}

                        <!-- Show Sale Details if specific sale -->
                        @if (isset($sale))
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Sale Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Order No:</strong> {{ $sale->order_no }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Customer Name:</strong> {{ $sale->customer->name ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Phone:</strong> {{ $sale->customer->phone ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Sale Date:</strong> {{ $sale->created_at->format('d M Y') }}
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row text-center">
                                                <div class="col-md-3 border-end">
                                                    <h6 class="text-muted mb-1">Total Bill</h6>
                                                    <h4 class="text-primary">৳{{ number_format($sale->bill, 2) }}</h4>
                                                </div>
                                                <div class="col-md-2 border-end">
                                                    <h6 class="text-muted mb-1">Discount</h6>
                                                    <h4 class="text-info">৳{{ number_format($sale->discount, 2) }}</h4>
                                                </div>
                                                <div class="col-md-2 border-end">
                                                    <h6 class="text-muted mb-1">Payable</h6>
                                                    <h4 class="text-warning">৳{{ number_format($sale->payble, 2) }}</h4>
                                                </div>
                                                <div class="col-md-2 border-end">
                                                    <h6 class="text-muted mb-1">Paid</h6>
                                                    <h4 class="text-success">
                                                        ৳{{ number_format($sale->advanced_payment, 2) }}</h4>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6 class="text-muted mb-1">Due</h6>
                                                    <h4 class="text-danger">৳{{ number_format($sale->due_payment, 2) }}
                                                    </h4>
                                                </div>
                                            </div>
                                            {{-- <div class="row mt-3">
                                                <div class="col-md-12 text-center">
                                                    <span
                                                        class="badge bg-{{ $sale->payment_status == 'paid' ? 'success' : ($sale->payment_status == 'partial' ? 'warning' : 'danger') }} fs-6 p-2">
                                                        Payment Status: {{ ucfirst($sale->payment_status) }}
                                                    </span>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Make Payment Form -->
                            @if ($sale->due_payment > 0)
                                <div class="card mb-4">
                                    <div class="card-header bg-light text-white">
                                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i> Make Payment</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('sales.process-payment') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Payment Amount *</label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="payment_amount" id="payment_amount"
                                                        max="{{ $sale->due_payment }}" min="0.01" step="0.01"
                                                        value="{{ $sale->due_payment }}" required
                                                        oninput="updateRemaining(this.value)">

                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Payment Method *</label>
                                                    <select class="form-select form-select-sm" name="payment_method"
                                                        required>
                                                        <option value="">Select Method</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="card">Card</option>
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Payment Date</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        name="payment_date" value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                            </div>

                                            <div class="row g-3 mt-2">
                                                <div class="col-md-8">
                                                    <label class="form-label fw-bold">Notes (Optional)</label>
                                                    <textarea class="form-control form-control-sm" name="notes" rows="2" placeholder="Enter payment notes..."></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Remaining After Payment</label>
                                                    <div class="alert alert-info py-1 text-center mb-0">
                                                        <h5 class="mb-0" id="remainingAmount">৳0.00</h5>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-success btn-sm w-100 mt-3">
                                                <i class="fas fa-check-circle me-2"></i> Process Payment
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-success text-center">
                                    <h4 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        This order is fully paid! No due amount remaining.
                                    </h4>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($sale) && $sale->due_payment > 0)
        <script>
            function updateRemaining(amount) {
                const dueAmount = {{ $sale->due_payment }};
                const paymentAmount = parseFloat(amount) || 0;
                const remaining = dueAmount - paymentAmount;

                document.getElementById('remainingAmount').textContent = '৳' + remaining.toFixed(2);

                // Change color based on remaining amount
                const remainingElement = document.getElementById('remainingAmount');
                if (remaining === 0) {
                    remainingElement.parentElement.className = 'alert alert-success py-2';
                } else if (remaining < 0) {
                    remainingElement.parentElement.className = 'alert alert-danger py-2';
                } else {
                    remainingElement.parentElement.className = 'alert alert-info py-2';
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateRemaining({{ $sale->due_payment }});
            });
        </script>
    @endif
@endsection
