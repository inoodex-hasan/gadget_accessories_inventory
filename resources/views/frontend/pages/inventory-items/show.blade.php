@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-primary">
                            Unit: <span class="fw-bold">{{ $item->serial_number }}</span>
                        </h3>
                        <a href="{{ route('inventory-items.index') }}" class="btn btn-outline-secondary btn-sm">
                            Back
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <h5 class="text-secondary mb-3 border-bottom pb-2">Barcode Identifier</h5>

                        <div class="text-center mb-4 p-3 bg-light rounded" style="width: 100%;">

                            <div class="d-inline-block mx-auto barcode-wrapper">
                                <div class="barcode-display mb-1">
                                    {!! $barcode['1d'] !!}
                                </div>
                            </div>

                            <small class="text-muted d-block fw-bold" style="letter-spacing: 1px;">
                                SCAN DATA: {{ $item->barcode_data }}
                            </small>
                        </div>

                        <h5 class="text-secondary mb-3 border-bottom pb-2">Unit Tracking & Product Info</h5>

                        <ul class="list-group list-group-flush">

                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fw-bold">Status:</span>
                                @php
                                    $statusColors = [
                                        'available' => 'success',
                                        'in_use' => 'primary',
                                        'under_maintenance' => 'warning',
                                        'retired' => 'secondary',
                                        'lost' => 'danger',
                                        'sold' => 'info',
                                    ];
                                    $statusKey = strtolower($item->unit_status);
                                    $statusClass = $statusColors[$statusKey] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }} fs-6 py-2 px-3">
                                    {{ ucfirst(str_replace('_', ' ', $item->unit_status)) }}
                                </span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fw-bold">Product:</span>
                                @if ($item->product)
                                    <span>{{ $item->product->name }} (SKU: {{ $item->product->sku }})</span>
                                @else
                                    <span>N/A</span>
                                @endif
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fw-bold">Purchase Date:</span>
                                <span>{{ $item->purchase_date ? $item->purchase_date->format('F d, Y') : 'N/A' }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="fw-bold">Record Created:</span>
                                <span class="text-muted">{{ $item->created_at->format('M d, Y') }}</span>
                            </li>
                        </ul>

                        <div class="d-grid gap-2">

                            <div class="btn-group gap-2" role="group">
                                <a href="{{ route('inventory-items.print-barcode', $item->id) }}" class="btn btn-info"
                                    target="_blank">
                                    <i class="fas fa-print me-1"></i> Print
                                </a>
                                <a href="{{ route('inventory-items.download-barcode', $item->id) }}"
                                    class="btn btn-success">
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
