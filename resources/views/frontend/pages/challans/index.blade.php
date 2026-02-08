@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Challan Management</h4>
                            <a href="{{ route('challans.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Challan
                            </a>
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

                        <!-- Challan List -->
                        <div class="table-fluid">
                            <table class="table table-bordered table-hover" id="challansTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Challan No.</th>
                                        <th>Challan Type</th>
                                        <th>Date</th>
                                        {{-- <th>Items</th> --}}
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($challans as $challan)
                                        <tr>
                                            <td>
                                                <strong>{{ $challan->challan_number }}</strong>
                                            </td>
                                            <td>{{ $challan->type }}</td>
                                            <td>{{ $challan->challan_date->format('M d, Y') }}</td>
                                            {{-- 
                                            <td>
                                                <span
                                                    class="badge {{ $challan->type == 'sale' ? 'bg-primary' : 'bg-success' }}">
                                                    {{ ucfirst($challan->type) }} Challan
                                                </span>
                                            </td> --}}
                                            <td class="d-flex align-items-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul>
                                                            <li>
                                                                {{-- <a class="dropdown-item"
                                                                    href="{{ route('challans.show', $challan->id) }}">
                                                                    <i class="far fa-eye me-2"></i>Preview
                                                                </a> --}}
                                                                {{-- <a class="dropdown-item"
                                                                            href="{{ route('bills.show', $bill->id) }}">
                                                                            <i class="far fa-eye me-2"></i>View
                                                                        </a> --}}
                                                            </li>

                                                            <a class="dropdown-item"
                                                                href="{{ route('challans.download', $challan->id) }}">
                                                                <i class="fas fa-download me-2"></i>Download PDF
                                                            </a>

                                                            </li>
                                                            <li>
                                                                <a onclick="if (confirm('Are you sure to delete?')) { document.getElementById('serviceDelete{{ $challan->id }}').submit(); }"
                                                                    class="dropdown-item" href="javascript:void(0)">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                                <form id="serviceDelete{{ $challan->id }}"
                                                                    action="{{ route('challans.destroy', $challan->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">

                                                    <h5>No Challans Found</h5>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if ($challans->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing {{ $challans->firstItem() }} to {{ $challans->lastItem() }} of
                                    {{ $challans->total() }} entries
                                </div>
                                <nav>
                                    {{ $challans->links() }}
                                </nav>
                            </div>
                        @endif
                    </div>
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
                let finalUrl = '{{ route('challans.index') }}';
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
                window.location.href = '{{ route('challans.index') }}';
            });

            // Set current filter values from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (typeFilter) typeFilter.value = urlParams.get('type') || '';
            if (dateFrom) dateFrom.value = urlParams.get('date_from') || '';
            if (dateTo) dateTo.value = urlParams.get('date_to') || '';

            console.log('Filters setup complete');
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', setupFilters);
    </script>
@endsection
