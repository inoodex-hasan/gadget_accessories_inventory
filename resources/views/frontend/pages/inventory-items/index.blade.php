@extends('layouts.app')

@section('content')
    <div class="container-fluid p-5">
        <div class="row mb-4">
            <div class="col">
                <h1>Inventory Items</h1>
            </div>
        </div>

        <!-- Search Form -->
        {{-- <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by serial or barcode..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="in_use" {{ request('status')=='in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="under_maintenance" {{ request('status')=='under_maintenance' ? 'selected' : ''
                                }}>Maintenance</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-end">
                    <!-- Search by serial/barcode -->
                    <div class="col-md-3 col-lg-3">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Serial or Barcode..."
                            value="{{ request('search') }}">
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2 col-lg-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                        </select>
                    </div>

                    <!-- Purchase Date From -->
                    <div class="col-md-2 col-lg-2">
                        <label class="form-label">Purchase From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <!-- Purchase Date To -->
                    <div class="col-md-2 col-lg-2">
                        <label class="form-label">Purchase To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <!-- Search Button -->
                    <div class="col-md-1 col-lg-1 ps-1">
                        <button type="submit" class="btn btn-info">
                            Search
                        </button>
                    </div>

                    <!-- Clear Filters Button -->
                    <!-- <div class="col-md-1 col-lg-1 ps-1">
                            <a href="{{ route('inventory-items.index') }}" class="btn btn-secondary">
                                Clear
                            </a>
                        </div> -->
                </form>
            </div>
        </div>

        <!-- Hidden Bulk Form -->
        <form id="bulkForm" action="{{ route('inventory-items.bulk-download-pdf') }}" method="POST" target="_blank"
            style="display: none;">
            @csrf
            <!-- Item IDs will be added by JavaScript -->
        </form>

        <!-- Bulk Actions Bar -->
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check m-0">
                        <input type="checkbox" id="selectAll" class="form-check-input">
                        <label for="selectAll" class="form-check-label ms-2">
                            <span id="selectedCount">0</span> selected
                        </label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success btn-sm" id="bulkDownloadBtn"
                            onclick="downloadSelected()" disabled>
                            <i class="fas fa-download"></i> Download Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table (OUTSIDE THE FORM) -->
        <div class="card">
            <div class="card-body">
                @if ($items->isEmpty())
                    <div class="text-center py-5">
                        <p class="text-muted">No inventory items found.</p>
                    </div>
                @else
                    <div class="table-fluid">
                        <table class="table table-hover table-center">
                            <thead>
                                <tr class="text-center">
                                    <th>
                                        <input type="checkbox" id="selectAllHeader">
                                    </th>
                                    {{-- <th>#</th> --}}
                                    <th>Barcode</th>
                                    <th>Serial Number</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    {{-- <th>Purchase Date</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr class="text-center align-middle">
                                        <!-- Checkbox (NO name attribute since not in form) -->
                                        <td class="align-middle">
                                            <input type="checkbox" value="{{ $item->id }}" class="item-checkbox">
                                        </td>
                                        {{-- <td class="align-middle">{{ $loop->iteration }}</td> --}}
                                        <td class="align-middle">
                                            <div class="barcode-small d-flex justify-content-center">
                                                {!! $item->barcode_html !!}
                                            </div>
                                            <small class="text-muted d-block mt-1">{{ $item->barcode_data }}</small>
                                        </td>
                                        <td class="align-middle">
                                            <code>{{ $item->serial_number }}</code>
                                        </td>
                                        <td class="align-middle">
                                            @if ($item->product)
                                                <strong>{{ str($item->product->name)->limit(15, '...') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->product->sku }}</small>
                                            @else
                                                <span class="text-muted">No Product</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $statusColors = [
                                                    'available' => 'success',
                                                    'in_use' => 'primary',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$item->unit_status] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $item->unit_status)) }}
                                            </span>
                                        </td>
                                        {{-- <td class="align-middle">{{ $item->purchase_date->format('M d, Y') }}</td> --}}
                                        <td class="align-middle">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('inventory-items.show', $item->id) }}">
                                                            <i class="fas fa-eye text-info me-2"></i> View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('inventory-items.download-barcode', $item->id) }}">
                                                            <i class="fas fa-download text-success me-2"></i> Download
                                                        </a>
                                                    </li>
                                                    <!-- Delete using JavaScript instead of form -->
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="#"
                                                            onclick="return deleteItem({{ $item->id }})">
                                                            <i class="fas fa-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    <style>
        .barcode-small {
            max-width: 200px !important;
            width: 200px !important;
            overflow: hidden;
            margin: 0 auto;
        }

        .barcode-small svg {
            height: 80px !important;
            width: 200px !important;
            max-width: 200px !important;
            display: block !important;
        }

        .barcode-small svg * {
            width: inherit !important;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        #bulkDownloadBtn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Desktop & Laptop Responsive Table Styles */
        @media (min-width: 992px) {
            .table-fluid {
                overflow-x: auto;
            }

            .table {
                table-layout: fixed;
                width: 100%;
            }

            .table th,
            .table td {
                padding: 10px 8px;
                font-size: 13px;
                text-align: center;
                vertical-align: middle;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .table thead th {
                background-color: #f8f9fa;
                font-weight: 600;
                border-bottom: 2px solid #dee2e6;
                text-align: center;
            }

            .table tbody tr {
                border-bottom: 1px solid #dee2e6;
                transition: background-color 0.2s ease;
            }

            .table tbody tr:hover {
                background-color: #f8f9fa;
            }

            .table-center {
                text-align: center;
            }

            .table-center td {
                text-align: center !important;
            }

            .align-middle {
                vertical-align: middle !important;
            }

            .card {
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            .card-body {
                padding: 20px;
            }
        }

        @media (min-width: 1200px) {

            .table th,
            .table td {
                padding: 12px 10px;
                font-size: 14px;
            }

            .container-fluid {
                max-width: 1400px;
                margin: 0 auto;
            }

            .table-center th:nth-child(1),
            .table-center td:nth-child(1) {
                width: 5%;
            }

            .table-center th:nth-child(2),
            .table-center td:nth-child(2) {
                width: 22%;
            }

            .table-center th:nth-child(3),
            .table-center td:nth-child(3) {
                width: 15%;
            }

            .table-center th:nth-child(4),
            .table-center td:nth-child(4) {
                width: 23%;
            }

            .table-center th:nth-child(5),
            .table-center td:nth-child(5) {
                width: 12%;
            }

            .table-center th:nth-child(6),
            .table-center td:nth-child(6) {
                width: 10%;
            }
        }

        /* Tablet and up - improve row spacing */
        @media (min-width: 768px) {
            .card {
                margin-bottom: 20px;
            }

            .page-header {
                margin-bottom: 30px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const selectAllHeader = document.getElementById('selectAllHeader');
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const bulkDownloadBtn = document.getElementById('bulkDownloadBtn');
            const selectedCount = document.getElementById('selectedCount');

            function updateSelection() {
                const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
                const count = checkedBoxes.length;
                const allChecked = count === checkboxes.length;
                const anyChecked = count > 0;

                // Update select all checkboxes
                if (selectAll) selectAll.checked = allChecked;
                if (selectAllHeader) selectAllHeader.checked = allChecked;

                // Update selected count
                if (selectedCount) {
                    selectedCount.textContent = count;
                    selectedCount.style.fontWeight = count > 0 ? 'bold' : 'normal';
                }

                // Enable/disable bulk download button
                if (bulkDownloadBtn) {
                    bulkDownloadBtn.disabled = !anyChecked;
                }
            }

            // Header select all
            if (selectAllHeader) {
                selectAllHeader.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateSelection();
                });
            }

            // Main select all
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateSelection();
                });
            }

            // Individual checkbox change
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSelection);
            });

            // Row click to select checkbox
            document.querySelectorAll('tbody tr').forEach(row => {
                row.addEventListener('click', function (e) {
                    // Don't trigger if clicking on links, buttons, or the checkbox itself
                    if (e.target.tagName === 'A' ||
                        e.target.tagName === 'BUTTON' ||
                        e.target.tagName === 'INPUT' ||
                        e.target.closest('a') ||
                        e.target.closest('button') ||
                        e.target.closest('.dropdown')) {
                        return;
                    }

                    const checkbox = this.querySelector('.item-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        updateSelection();
                    }
                });
            });

            // Initialize
            updateSelection();
        });

        // Download selected items
        function downloadSelected() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            const itemIds = Array.from(checkboxes).map(cb => cb.value);

            if (itemIds.length === 0) {
                alert('Please select at least one item to download.');
                return;
            }

            // Get the hidden form
            const form = document.getElementById('bulkForm');

            // Clear previous inputs
            form.innerHTML = '';

            // Add CSRF token
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            // Add selected item IDs
            itemIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'item_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            // Submit the form
            form.submit();
        }

        // Delete item
        function deleteItem(itemId) {
            if (confirm('Are you sure you want to delete this item?')) {
                // Create delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/inventory-items/' + itemId;
                form.style.display = 'none';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
            return false; // Prevent default link behavior
        }
    </script>
@endsection