@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Bills Management</h4>
                            <div>
                                <a href="{{ route('bills.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Create Bill
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select class="form-control" id="typeFilter">
                                        <option value="">All Types</option>
                                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Sales
                                            Challan</option>
                                        <option value="project" {{ request('type') == 'project' ? 'selected' : '' }}>Project
                                            Challan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Date From</label>
                                    <input type="date" class="form-control" id="dateFrom"
                                        value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Date To</label>
                                    <input type="date" class="form-control" id="dateTo"
                                        value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-end h-100">
                                    <button type="button" class="btn btn-primary me-2" id="applyFilters">
                                        Apply
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="resetFilters">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Bills Table -->
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                        <div class="table-fluid">
                            <table class="table table-bordered table-hover" id="billsTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Bill Number</th>
                                        <th>Type</th>
                                        <th>Bill Date</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bills as $bill)
                                        <tr>
                                            <td>
                                                <strong>{{ $bill->bill_number }}</strong>
                                            </td>
                                            <td>{{ $bill->type }}</td>
                                            <td>{{ $bill->bill_date->format('M d, Y') }}</td>
                                            <td>
                                                <strong>৳{{ number_format($bill->total_amount, 2) }}</strong>
                                            </td>
                                            <td class="d-flex align-items-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul>
                                                            <li>
                                                                {{-- <a class="dropdown-item"
                                                                            href="{{ route('bills.show', $bill->id) }}">
                                                                            <i class="far fa-eye me-2"></i>Preview
                                                                        </a> --}}
                                                                {{-- <a class="dropdown-item"
                                                                            href="{{ route('bills.show', $bill->id) }}">
                                                                            <i class="far fa-eye me-2"></i>View
                                                                        </a> --}}
                                                            </li>

                                                            <a class="dropdown-item"
                                                                href="{{ route('bills.download', $bill->id) }}">
                                                                <i class="fas fa-download me-2"></i>Download PDF
                                                            </a>

                                                            </li>
                                                            <li>
                                                                <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $bill->id }}').submit(); }"
                                                                    class="dropdown-item" href="javascript:void(0)">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                                <form id="serviceDelete{{ $bill->id }}"
                                                                    action="{{ route('bills.destroy', $bill->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('bills.show', $bill) }}"
                                                                class="btn btn-sm btn-info" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('bills.preview', $bill) }}" target="_blank"
                                                                class="btn btn-sm btn-warning" title="Preview PDF">
                                                                <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                            <a href="{{ route('bills.download', $bill) }}"
                                                                class="btn btn-sm btn-success" title="Download PDF">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            <div class="btn-group" role="group">
                                                                <button id="statusDropdown{{ $bill->id }}"
                                                                    type="button"
                                                                    class="btn btn-sm btn-secondary dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="fas fa-cog"></i>
                                                                </button>
                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="statusDropdown{{ $bill->id }}">
                                                                    <h6 class="dropdown-header">Change Status</h6>
                                                                    <a class="dropdown-item status-change" href="#"
                                                                        data-bill-id="{{ $bill->id }}"
                                                                        data-status="draft">
                                                                        <span class="badge badge-warning mr-2">●</span>
                                                                        Draft
                                                                    </a>
                                                                    <a class="dropdown-item status-change" href="#"
                                                                        data-bill-id="{{ $bill->id }}"
                                                                        data-status="sent">
                                                                        <span class="badge badge-info mr-2">●</span> Sent
                                                                    </a>
                                                                    <a class="dropdown-item status-change" href="#"
                                                                        data-bill-id="{{ $bill->id }}"
                                                                        data-status="paid">
                                                                        <span class="badge badge-success mr-2">●</span>
                                                                        Paid
                                                                    </a>
                                                                    <a class="dropdown-item status-change" href="#"
                                                                        data-bill-id="{{ $bill->id }}"
                                                                        data-status="cancelled">
                                                                        <span class="badge badge-secondary mr-2">●</span>
                                                                        Cancelled
                                                                    </a>
                                                                    <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item text-danger" href="#"
                                                                        onclick="confirmDelete({{ $bill->id }})">
                                                                        <i class="fas fa-trash mr-2"></i> Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    {{-- <i class="fas fa-file-invoice fa-3x mb-3"></i> --}}
                                                    <h5>No bills found</h5>
                                                    {{-- <p>Create your first bill to get started</p>
                                                            <a href="{{ route('bills.create') }}"
                                                                class="btn btn-primary">
                                                                <i class="fas fa-plus"></i> Create Bill
                                                            </a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($bills->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing {{ $bills->firstItem() }} to {{ $bills->lastItem() }} of
                                    {{ $bills->total() }} entries
                                </div>
                                <div>
                                    {{ $bills->links() }}
                                </div>
                            </div>
                        @endif
                        {{-- </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Bill Status</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update the bill status to <strong id="statusText"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Update Status</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Simple filter functionality
        function setupFilters() {
            console.log('Setting up filters...');

            // Get elements
            const applyBtn = document.getElementById('applyFilters');
            const resetBtn = document.getElementById('resetFilters');
            const typeFilter = document.getElementById('typeFilter');
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');

            // Check if elements exist
            if (!applyBtn || !resetBtn || !typeFilter) {
                console.error('Filter elements not found!');
                return;
            }

            console.log('All filter elements found');

            // Apply filters
            applyBtn.addEventListener('click', function() {
                console.log('Apply button clicked');

                // Get current values
                const typeValue = typeFilter.value;
                const dateFromValue = dateFrom.value;
                const dateToValue = dateTo.value;

                console.log('Filter values:', {
                    type: typeValue,
                    dateFrom: dateFromValue,
                    dateTo: dateToValue
                });

                // Build URL parameters
                let params = [];

                if (typeValue) {
                    params.push('type=' + typeValue);
                }
                if (dateFromValue) {
                    params.push('date_from=' + dateFromValue);
                }
                if (dateToValue) {
                    params.push('date_to=' + dateToValue);
                }

                // Create final URL
                let finalUrl = '{{ route('bills.index') }}';
                if (params.length > 0) {
                    finalUrl += '?' + params.join('&');
                }

                console.log('Redirecting to:', finalUrl);

                // Redirect
                window.location.href = finalUrl;
            });

            // Reset filters
            resetBtn.addEventListener('click', function() {
                console.log('Reset button clicked');
                window.location.href = '{{ route('bills.index') }}';
            });

            // Set current filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (typeFilter) typeFilter.value = urlParams.get('type') || '';
            if (dateFrom) dateFrom.value = urlParams.get('date_from') || '';
            if (dateTo) dateTo.value = urlParams.get('date_to') || '';

            console.log('Filters setup complete');
        }

        // Initialize when page loa
        document.addEventListener('DOMContentLoaded', setupFilters);
    </script>
@endsection

@push('styles')
    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            color: #6c757d;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .btn-group .btn {
            border-radius: 0.25rem;
            margin-right: 0.25rem;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush
