@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="content-page-header mt-5">
                <h5>Purchase List</h5>
                <div class="list-btn">
                    <ul class="filter-list">
                        <li>
                            <a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#add-purchase-modal">
                                <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Purchase</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="add-purchase-modal" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Purchase</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="px-3" method="post" action="{{ route('purchase.store') }}" id="purchaseForm">
                                @csrf

                                <!-- Product Selection & Barcode Scanning -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="product_id" class="form-label">Product</label>
                                        <select class="form-control select2" name="product_id" id="product_id" required>
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-barcode="{{ $product->barcode ?? '' }}">
                                                     {{ Str::limit($product->name, 15) }} -
                                                    {{ Str::limit($product->model, 10) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Barcode Scanner (Optional)</label>
                                        <div class="input-group">
                                            <input type="text" id="barcodeInput" class="form-control"
                                                placeholder="Scan or enter barcode (optional)">
                                            <button type="button" class="btn btn-outline-primary" id="startScannerBtn">
                                                <i class="fa fa-camera"></i> Scan
                                            </button>
                                        </div>
                                        <small class="text-muted">Optional: Scan barcode to auto-fill or add serial
                                            numbers</small>
                                    </div>
                                </div>

                                <!-- Camera Container for Barcode Scanning -->
                                <div id="cameraContainer" class="mb-3" style="display: none;">
                                    <div id="barcodeScanner"
                                        style="width: 100%; height: 250px; border: 1px solid #ddd; background: #f8f9fa;">
                                    </div>
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-sm btn-danger" id="stopScannerBtn">
                                            <i class="fa fa-stop"></i> Stop Scanner
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" required
                                            min="1">
                                        <small class="text-muted">Number of items to add to inventory</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="vendor" class="form-label">Vendor</label>
                                        <select id="vendor" name="vendor_id" class="form-control" required>
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">
                                                    {{ $vendor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Serial Numbers Section -->
                                <div id="serialSection" class="mb-3" style="display: none;">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Serial Numbers Management</h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Individual Serial Inputs -->
                                            <div id="serialInputsContainer" class="mb-3">
                                                <!-- Serial inputs will be generated here -->
                                            </div>

                                            <!-- Bulk Serial Numbers Input -->
                                            <div class="mb-3">
                                                <label class="form-label">Or Enter Bulk Serial Numbers (One per
                                                    line)</label>
                                                <textarea id="bulkSerials" class="form-control" rows="3" placeholder="Enter serial numbers, one per line"></textarea>
                                                <button type="button" class="btn btn-sm btn-secondary mt-2"
                                                    id="loadBulkSerials">
                                                    Load Serial Numbers
                                                </button>
                                            </div>

                                            <!-- Serial Numbers List -->
                                            <div class="border rounded p-3">
                                                <h6 class="mb-2">Serial Numbers Added:</h6>
                                                <div id="serialNumbersList" style="max-height: 150px; overflow-y: auto;">
                                                    <!-- Serial numbers will appear here -->
                                                </div>
                                                <div class="text-muted small mt-2" id="serialCount">0 serial numbers added
                                                </div>
                                                <input type="hidden" name="serial_numbers_json" id="serialNumbersJson">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="unit_price" class="form-label">Unit Price</label>
                                        <input type="number" step="0.01" name="unit_price" id="unit_price"
                                            class="form-control" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sub_price" class="form-label">Sub Price</label>
                                        <input type="number" step="0.01" name="sub_price" id="sub_price"
                                            class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="total_price" class="form-label">Payable Total Price </label>
                                        <input type="number" step="0.01" name="total_price" id="total_price"
                                            class="form-control" required placeholder="Enter manually">
                                        <small class="text-muted">Enter manually. You can give discount or adjust
                                            here.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payment" class="form-label">Payment</label>
                                        <input type="number" step="0.01" name="payment" id="payment"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="due" class="form-label">Due</label>
                                    <input type="number" step="0.01" name="due" id="due"
                                        class="form-control" readonly>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Purchase List Table -->
        <div class="row">
            <div class="card-table">
                <div class="card-body">
                    <div class="table-fluid">
                        <table class="table table-center table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th>#</th> -->
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Vendor</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Payment</th>
                                    <th>Due</th>
                                    {{-- <th>Serial Items</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <!-- <td>{{ $loop->iteration }}</td> -->
                                        <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                                        <td>{{ Str::limit($purchase->product->name ?? 'N/A', 15) }}({{ Str::limit($purchase->product->model ?? 'N/A', 15) }})
                                        </td>
                                        <td>{{ Str::limit($purchase->vendor->name ?? 'N/A', 15) }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                        <td>{{ number_format($purchase->unit_price, 2) }}</td>
                                        <td>{{ number_format($purchase->total_price, 2) }}</td>
                                        <td>{{ number_format($purchase->payment, 2) }}</td>
                                        <td>{{ number_format($purchase->due, 2) }}</td>
                                        {{-- <td>
                                            @if ($purchase->inventoryItems && $purchase->inventoryItems->count() > 0)
                                                <span class="badge bg-info">
                                                    {{ $purchase->inventoryItems->count() }} items
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No serials</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#edit-purchase-{{ $purchase->id }}">Edit</a>
                                                    <!-- <a class="dropdown-item"
                                                            href="{{ route('purchase.show', $purchase->id) }}">View
                                                            Details</a> -->
                                                    <form method="POST"
                                                        action="{{ route('purchase.destroy', $purchase->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div id="edit-purchase-{{ $purchase->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="editModalLabel-{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel-{{ $purchase->id }}">
                                                        Edit Purchase</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST"
                                                        action="{{ route('purchase.update', $purchase->id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="edit-product_id-{{ $purchase->id }}"
                                                                class="form-label">Product</label>
                                                            <select id="edit-product_id-{{ $purchase->id }}"
                                                                name="product_id" class="form-select select2" required>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        {{ $product->id == $purchase->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                        ({{ $product->model ?? 'N/A' }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-quantity-{{ $purchase->id }}"
                                                                class="form-label">Quantity</label>
                                                            <input id="edit-quantity-{{ $purchase->id }}" name="quantity"
                                                                value="{{ $purchase->quantity }}" class="form-control"
                                                                placeholder="Quantity" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-unit_price-{{ $purchase->id }}"
                                                                class="form-label">Last Unit Price</label>
                                                            <input id="edit-unit_price-{{ $purchase->id }}"
                                                                name="unit_price" value="{{ $purchase->unit_price }}"
                                                                class="form-control" placeholder="Unit Price" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-sub_price-{{ $purchase->id }}"
                                                                class="form-label">Sub Price</label>
                                                            <input id="edit-sub_price-{{ $purchase->id }}"
                                                                name="sub_price" value="{{ $purchase->sub_price }}"
                                                                class="form-control" placeholder="Sub Price" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-total_price-{{ $purchase->id }}"
                                                                class="form-label">Payable Total Price</label>
                                                            <input id="edit-total_price-{{ $purchase->id }}"
                                                                name="total_price" value="{{ $purchase->total_price }}"
                                                                class="form-control" placeholder="Total Price" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-payment-{{ $purchase->id }}"
                                                                class="form-label">Payment</label>
                                                            <input id="edit-payment-{{ $purchase->id }}" name="payment"
                                                                value="{{ $purchase->payment }}" class="form-control"
                                                                placeholder="Payment" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-due-{{ $purchase->id }}"
                                                                class="form-label">Due</label>
                                                            <input id="edit-due-{{ $purchase->id }}" name="due"
                                                                value="{{ $purchase->due }}" class="form-control"
                                                                placeholder="Due" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-vendor-{{ $purchase->id }}"
                                                                class="form-label">Vendor</label>
                                                            <select id="edit-vendor-{{ $purchase->id }}" name="vendor_id"
                                                                class="form-select select2" required>
                                                                <option value="">Select Vendor</option>
                                                                @foreach ($vendors as $vendor)
                                                                    <option value="{{ $vendor->id }}"
                                                                        {{ $vendor->id == $purchase->vendor_id ? 'selected' : '' }}>
                                                                        {{ $vendor->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/quagga/dist/quagga.min.js"></script>

    <script>
        $(document).ready(function() {
            // === GLOBAL VARIABLES ===
            let serialNumbers = [];
            let quaggaActive = false;
            let isScannerInitialized = false;

            // ==================== UTILITY FUNCTIONS (MISSING BEFORE!) ====================
            function showToast(message, type = 'info') {
                // Remove old toasts
                $('.custom-toast').remove();

                const toast = $(`
                <div class="custom-toast alert alert-${type} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
                $('body').append(toast);

                setTimeout(() => toast.alert('close'), 4000);
            }

            function showLoading(message = 'Saving...') {
                $('.loading-overlay').remove();
                const overlay = $(`
                <div class="loading-overlay position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-75 text-white" style="z-index: 99999;">
                    <div class="spinner-border mb-3" role="status"></div>
                    <div>${message}</div>
                </div>
            `);
                $('body').append(overlay);
            }

            function hideLoading() {
                $('.loading-overlay').remove();
            }

            function playBeepSound() {
                try {
                    const ctx = new(window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.frequency.value = 800;
                    osc.type = 'square';
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.1);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.1);
                } catch (e) {
                    /* ignore */
                }
            }

            // ==================== PRICE CALCULATIONS ====================
            function calculateSubPrice() {
                const quantity = parseFloat($('#quantity').val()) || 0;
                const unitPrice = parseFloat($('#unit_price').val()) || 0;
                const subPrice = quantity * unitPrice;
                $('#sub_price').val(subPrice.toFixed(2));
                calculateDue();
            }

            function calculateDue() {
                const total = parseFloat($('#total_price').val()) || 0;
                const payment = parseFloat($('#payment').val()) || 0;
                const due = total - payment;
                $('#due').val(due < 0 ? 0 : due.toFixed(2));
            }

            $('#quantity, #unit_price').on('input', calculateSubPrice);
            $('#total_price, #payment').on('input', calculateDue);

            // ==================== BARCODE SCANNER ====================
            function initBarcodeScanner() {
                if (isScannerInitialized) return;

                $('#startScannerBtn').on('click', () => !quaggaActive && startBarcodeScanner());
                $('#stopScannerBtn').on('click', () => quaggaActive && stopBarcodeScanner());

                $('#barcodeInput').on('keypress', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        const barcode = $(this).val().trim();
                        if (barcode) {
                            processBarcode(barcode);
                            $(this).val('');
                        }
                    }
                });

                Quagga.onDetected(result => {
                    if (result?.codeResult?.code) {
                        const barcode = result.codeResult.code;
                        $('#barcodeInput').val(barcode);
                        processBarcode(barcode);
                        playBeepSound();
                        Quagga.pause();
                        setTimeout(() => quaggaActive && Quagga.start(), 1000);
                    }
                });

                isScannerInitialized = true;
            }

            function startBarcodeScanner() {
                $('#cameraContainer').show();
                $('#startScannerBtn').prop('disabled', true);

                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: '#barcodeScanner',
                        constraints: {
                            facingMode: "environment",
                            width: {
                                ideal: 640
                            },
                            height: {
                                ideal: 480
                            }
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader",
                            "upc_reader", "upc_e_reader", "codabar_reader"
                        ]
                    },
                    locate: true
                }, err => {
                    if (err) {
                        showToast('Camera not accessible or denied', 'error');
                        stopBarcodeScanner();
                        return;
                    }
                    Quagga.start();
                    quaggaActive = true;
                    showToast('Scanner active – scan barcode', 'success');
                });
            }

            function stopBarcodeScanner() {
                if (quaggaActive) Quagga.stop();
                quaggaActive = false;
                $('#cameraContainer').hide();
                $('#startScannerBtn').prop('disabled', false);
                $('#barcodeInput').val('');
            }

            function processBarcode(barcode) {
                let matched = false;

                $('#product_id option').each(function() {
                    if ($(this).data('barcode')?.toString() === barcode) {
                        $('#product_id').val($(this).val()).trigger('change');
                        matched = true;
                        showToast('Product found: ' + $(this).text().trim(), 'success');
                        return false;
                    }
                });

                if (!matched) {
                    if ($('#serialSection').is(':visible')) {
                        addSerialNumber(barcode);
                    } else if ($('#product_id').val() && parseInt($('#quantity').val()) > 0) {
                        $('#serialSection').show();
                        generateSerialInputs(parseInt($('#quantity').val()));
                        addSerialNumber(barcode);
                    } else {
                        showToast('Barcode not matched to any product', 'info');
                    }
                }
            }

            // ==================== SERIAL NUMBERS ====================
            $('#quantity').on('input', function() {
                const qty = parseInt($(this).val()) || 0;
                if (qty > 0) {
                    $('#serialSection').show();
                    generateSerialInputs(qty);
                } else {
                    $('#serialSection').hide();
                    serialNumbers = [];
                    updateSerialList();
                }
                calculateSubPrice();
            });

            function generateSerialInputs(qty) {
                $('#serialInputsContainer').empty();
                for (let i = 1; i <= qty; i++) {
                    $('#serialInputsContainer').append(`
                    <div class="input-group mb-2 serial-input-group" data-index="${i}">
                        <span class="input-group-text">${i}</span>
                        <input type="text" class="form-control serial-input" placeholder="Serial #${i}" data-index="${i}">
                        <button type="button" class="btn btn-outline-primary scan-serial-btn"><i class="fa fa-camera"></i></button>
                        <button type="button" class="btn btn-outline-success add-serial-btn"><i class="fa fa-check"></i></button>
                    </div>
                `);
                }

                $('.scan-serial-btn').off('click').on('click', function() {
                    $('.serial-input-group').removeClass('scanning');
                    $(this).closest('.serial-input-group').addClass('scanning');
                    showToast('Scan item #' + $(this).closest('.serial-input-group').data('index'), 'info');
                });

                $('.add-serial-btn').off('click').on('click', function() {
                    const idx = $(this).closest('.serial-input-group').data('index');
                    const val = $(`input[data-index="${idx}"]`).val().trim();
                    if (!val) return showToast('Enter serial first', 'warning');
                    if (isDuplicateSerial(val)) return showToast('Duplicate serial', 'error');
                    addSerialNumber(val);
                    $(`input[data-index="${idx}"]`).val('').focus();
                });

                $('.serial-input').off('keypress').on('keypress', function(e) {
                    if (e.which === 13) $(this).closest('.serial-input-group').find('.add-serial-btn')
                        .click();
                });
            }

            function addSerialNumber(serial) {
                if (!serial || isDuplicateSerial(serial)) {
                    if (serial) showToast('Duplicate: ' + serial, 'error');
                    return false;
                }
                serialNumbers.push({
                    id: Date.now(),
                    serial
                });
                updateSerialList();
                showToast('Added: ' + serial, 'success');
                return true;
            }

            function isDuplicateSerial(s) {
                return serialNumbers.some(item => item.serial === s);
            }

            window.removeSerialNumber = id => {
                serialNumbers = serialNumbers.filter(item => item.id !== id);
                updateSerialList();
            };

            function updateSerialList() {
                const list = $('#serialNumbersList');
                const qty = parseInt($('#quantity').val()) || 0;
                list.empty();

                if (serialNumbers.length === 0) {
                    list.html('<p class="text-muted text-center">No serials added</p>');
                } else {
                    serialNumbers.forEach((item, i) => {
                        list.append(`
                        <div class="d-flex justify-content-between align-items-center border p-2 rounded mb-2 bg-light">
                            <span><strong>#${i+1}</strong> ${item.serial}</span>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeSerialNumber(${item.id})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `);
                    });
                }

                $('#serialCount').text(`${serialNumbers.length} / ${qty} added`)
                    .toggleClass('text-success', serialNumbers.length === qty && qty > 0)
                    .toggleClass('text-warning', serialNumbers.length < qty && serialNumbers.length > 0);

                $('#serialNumbersJson').val(JSON.stringify(serialNumbers.map(s => s.serial)));
            }

            $('#loadBulkSerials').on('click', function() {
                const vals = $('#bulkSerials').val().trim().split(/[\n,;]+/).map(s => s.trim()).filter(
                    Boolean);
                if (!vals.length) return showToast('No serials entered', 'warning');
                let added = 0,
                    dup = 0;
                vals.forEach(s => addSerialNumber(s) ? added++ : dup++);
                $('#bulkSerials').val('');
                showToast(`Added ${added}${dup ? `, ${dup} duplicates skipped` : ''}`, 'success');
            });

            // ==================== FORM SUBMISSION (NOW WORKS!) ====================
            $('#purchaseForm').on('submit', function(e) {
                e.preventDefault();

                const qty = parseInt($('#quantity').val()) || 0;
                if (qty > 0 && serialNumbers.length !== qty) {
                    if (!confirm(`Serial count (${serialNumbers.length}) ≠ Quantity (${qty})\nContinue?`)) {
                        return;
                    }
                }

                if (!$('#vendor').val()) {
                    showToast('Select a vendor', 'error');
                    return;
                }

                showLoading('Saving purchase...');

                // Prepare form data - serialize form and add serials array
                let formData = $(this).serialize();

                // Add serials as array for Laravel (format: serials[]=value1&serials[]=value2)
                serialNumbers.forEach((item) => {
                    formData += '&serials[]=' + encodeURIComponent(item.serial);
                });

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function() {
                        showToast('Purchase saved successfully!', 'success');
                        // Safer modal close
                        $('#add-purchase-modal').modal('hide');
                        setTimeout(() => location.reload(), 800);
                    },
                    error: function(xhr) {
                        let msg = 'Save failed';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                msg = errors.join(', ');
                            }
                        } else if (xhr.statusText) {
                            msg = xhr.statusText;
                        }
                        showToast('Error: ' + msg, 'error');
                        console.error('Error details:', xhr.responseJSON || xhr);
                    },
                    complete: hideLoading
                });
            });

            // ==================== MODAL & SELECT2 ====================
            $('#add-purchase-modal').on('shown.bs.modal', function() {
                $('#purchaseForm')[0].reset();
                $('#product_id').val(null).trigger('change');
                serialNumbers = [];
                updateSerialList();
                $('#serialSection').hide();
                stopBarcodeScanner();
                initBarcodeScanner();

                // Initialize Select2 only when modal is open
                $('#product_id').select2({
                    width: '100%',
                    dropdownParent: $('#add-purchase-modal')
                });

                setTimeout(() => $('#product_id').select2('open'), 300);
            });

            $('#add-purchase-modal').on('hidden.bs.modal', function() {
                stopBarcodeScanner();
                serialNumbers = [];
                $('#product_id').select2('destroy');
            });

            // Fix Select2 in edit modals
            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find('.select2').select2({
                    width: '100%',
                    dropdownParent: $(this)
                });
            });

            initBarcodeScanner();
        });
    </script>

    <style>
        /* Barcode Scanner Styles */
        #barcodeScanner {
            position: relative;
            background: #000;
            border-radius: 4px;
            overflow: hidden;
        }

        #barcodeScanner video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Serial Number Input Styles */
        .serial-input-group.scanning {
            border: 2px solid #0d6efd;
            border-radius: 4px;
            padding: 5px;
            background: #f0f8ff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                border-color: #0d6efd;
            }

            50% {
                border-color: #86b7fe;
            }

            100% {
                border-color: #0d6efd;
            }
        }

        .serial-item {
            transition: all 0.3s ease;
        }

        .serial-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar for serial list */
        #serialNumbersList {
            scrollbar-width: thin;
        }

        #serialNumbersList::-webkit-scrollbar {
            width: 6px;
        }

        #serialNumbersList::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        #serialNumbersList::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        #serialNumbersList::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Select2 custom styles */
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        /* Toast animation */
        .custom-toast {
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            backdrop-filter: blur(5px);
        }
    </style>
@endsection
