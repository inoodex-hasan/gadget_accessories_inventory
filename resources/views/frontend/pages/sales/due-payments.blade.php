@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid pt-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">All Due Payments</h4>
                    </div>

                    @if ($sales->count())
                        <div class="p-3">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Customer/Client</th>
                                        <th>Total Amount</th>
                                        <th>Paid</th>
                                        <th>Due</th>
                                        <th>Sale Type</th>
                                        <th>Sale Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sale->order_no }}</td>
                                            <td>{{ $sale->sale_type == 'project' ? $sale->client->name ?? 'N/A' : $sale->customer->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ number_format($sale->payble, 2) }} Tk</td>
                                            <td>{{ number_format($sale->advanced_payment, 2) }} Tk</td>
                                            <td>
                                                <strong class="text-danger">{{ number_format($sale->due_payment, 2) }}
                                                    Tk</strong>
                                            </td>
                                            <td>{{ ucfirst($sale->sale_type) }}</td>
                                            <td>{{ $sale->created_at->format('d M, Y') }}</td>
                                            <td>
                                                @if ($sale->due_payment > 0)
                                                    @if ($sale->sale_type == 'project')
                                                        <a href="{{ route('projects.payments', $sale->id) }}"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-credit-card me-1"></i> Pay Now
                                                        </a>
                                                    @else
                                                        <a href="{{ route('sales.payments', $sale->id) }}"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-credit-card me-1"></i> Pay Now
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center shadow-sm">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Great!</strong> All sales are fully paid — no due payments remaining.
                        </div>
                    @endif
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

                const remainingElement = document.getElementById('remainingAmount');
                if (remaining === 0) {
                    remainingElement.parentElement.className = 'alert alert-success py-2';
                } else if (remaining < 0) {
                    remainingElement.parentElement.className = 'alert alert-danger py-2';
                } else {
                    remainingElement.parentElement.className = 'alert alert-info py-2';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateRemaining({{ $sale->due_payment }});
            });
        </script>
    @endif
@endsection
