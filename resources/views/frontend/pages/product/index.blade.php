{{-- resources/views/products/index.blade.php --}}
@extends('frontend.layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .barcode-scanned {
            background-color: #d1ffd1 !important;
            border-color: #28a745 !important;
            transition: all 0.3s;
        }

        .barcode-preview {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            text-align: center;
            border: 1px solid #ddd;
            font-family: 'Courier New', monospace;
            font-size: 1.1em;
        }

        #reader {
            width: 100% !important;
            max-width: 520px;
            margin: 0 auto;
        }

        #reader video {
            border-radius: 8px;
            width: 100%;
        }

        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            top: 1px !important;
            right: 8px !important;
        }

        .select2-selection__arrow {
            display: none !important;
        }
    </style>

    <div class="content container-fluid">
        <div class="page-header">
            <div class="content-page-header">
                <h5>Products</h5>
                <div class="list-btn">
                    <ul class="filter-list">
                        <li>
                            <a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#add-product-modal">
                                <i class="fa fa-plus-circle me-2"></i>Add Product
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="add-product-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Barcode & Serial Number Card -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Scan Item Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6">
                                        <label>Barcode</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="product-barcode"
                                                placeholder="Scan or type barcode..." autocomplete="off">
                                            <button class="btn btn-outline-primary" id="scan-barcode-btn" type="button">
                                                <i class="fas fa-camera"></i>
                                            </button>
                                        </div>
                                        <small id="barcode-status" class="text-muted">Ready to scan</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Serial Number</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="product-serial"
                                                placeholder="Auto or manual input..." autocomplete="off">
                                            <button class="btn btn-outline-success" id="scan-serial-btn" type="button">
                                                <i class="fas fa-barcode"></i>
                                            </button>
                                        </div>
                                        <small id="serial-status" class="text-muted">Will auto-fill if possible</small>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="barcode-preview" id="barcode-preview">
                                            Barcode preview
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="barcode-preview" id="serial-preview">
                                            Serial preview
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Form -->
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
                            id="add-product-form">
                            @csrf
                            <input type="hidden" name="barcode" id="form-barcode">
                            <input type="hidden" name="serial_number" id="form-serial">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select class="form-control select2-brand" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Model Name</label>
                                    <input type="text" name="model_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>Warranty (Days)</label>
                                    <input type="number" name="warranty" class="form-control" value="365">
                                </div>
                                <div class="col-12">
                                    <label>Photos</label>
                                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    {{-- <small class="text-muted">Default: Active</small> --}}
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-4">Save Product</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
         @foreach ($products as $product)
                                <div id="edit-product-modal{{ $product->id }}" class="modal fade" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edit-product-modal{{ $product->id }}">Edit
                                                    Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="px-3" method="POST"
                                                    action="{{ route('products.update', $product->id) }}"
                                                    enctype="multipart/form-data" id="edit-form-{{ $product->id }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Brand Selection -->
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Brand Name <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-control select2" name="brand_id"
                                                            id="brand_id" required>
                                                            <option value="">Select Brand</option>
                                                            @foreach ($brands as $brand)
                                                                <option
                                                                    {{ $brand->id == $product->brand_id ? 'selected' : '' }}
                                                                    value="{{ $brand->id }}">{{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Product Name -->
                                                    <div class="mb-3">
                                                        <label for="name{{ $product->id }}" class="form-label">Product
                                                            Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="name"
                                                            id="name{{ $product->id }}" class="form-control"
                                                            placeholder="Enter product name"
                                                            value="{{ old('name', $product->name) }}" required>
                                                    </div>

                                                    <!-- Product Model Name -->
                                                    <div class="mb-3">
                                                        <label for="model_name{{ $product->id }}"
                                                            class="form-label">Product Model Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="model_name"
                                                            id="model_name{{ $product->id }}" class="form-control"
                                                            placeholder="Enter product model name"
                                                            value="{{ old('model_name', $product->model ?? '') }}"
                                                            required>
                                                    </div>

                                                    <!-- Warranty -->
                                                    <div class="mb-3">
                                                        <label for="warranty{{ $product->id }}"
                                                            class="form-label">Warranty (Days)<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="warranty"
                                                            id="warranty{{ $product->id }}" class="form-control"
                                                            placeholder="Enter how many days"
                                                            value="{{ old('model_name', $product->warranty ?? '') }}"
                                                            required>
                                                    </div>

                                                    <!-- Existing Photos with Delete Buttons -->
                                                    @if ($product->photos && count($product->photos) > 0)
                                                        <div class="mb-3">
                                                            <label class="form-label">Existing Photos</label>
                                                            <div class="d-flex flex-wrap gap-2"
                                                                id="photos-container-{{ $product->id }}">
                                                                @foreach ($product->photos as $index => $photo)
                                                                    <div class="position-relative photo-item"
                                                                        data-photo="{{ $photo }}">
                                                                        <img src="{{ asset($photo) }}"
                                                                            class="img-thumbnail"
                                                                            style="height: 80px; width: 80px; object-fit: cover;">
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-photo-btn"
                                                                            data-product-id="{{ $product->id }}"
                                                                            data-photo="{{ $photo }}"
                                                                            style="width: 20px; height: 20px; padding: 0; border-radius: 50%;">
                                                                            ×
                                                                        </button>
                                                                        <small class="d-block text-center">Photo
                                                                            {{ $index + 1 }}</small>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <input type="hidden" name="remaining_photos"
                                                                id="remaining-photos-{{ $product->id }}"
                                                                value="{{ json_encode($product->photos) }}">
                                                        </div>
                                                    @endif

                                                    <!-- New Photos Upload -->
                                                    <div class="mb-3">
                                                        <label for="photos{{ $product->id }}" class="form-label">Add New
                                                            Photos</label>
                                                        <input type="file" name="photos[]"
                                                            id="photos{{ $product->id }}" class="form-control" multiple
                                                            accept="image/*">
                                                        <div id="image-preview-{{ $product->id }}"
                                                            class="mt-2 d-flex flex-wrap gap-2"></div>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="mb-3">
                                                        <label for="status{{ $product->id }}"
                                                            class="form-label">Status</label>
                                                        <select class="form-select mb-3" name="status"
                                                            id="status{{ $product->id }}" required>
                                                            <option value="1"
                                                                {{ old('status', $product->status) == 1 ? 'selected' : '' }}>
                                                                Active</option>
                                                            <option value="0"
                                                                {{ old('status', $product->status) == 0 ? 'selected' : '' }}>
                                                                Inactive</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

        <!-- Camera Scanner Modal -->
        <div class="modal fade" id="cameraScannerModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5>Scan Code</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div id="reader"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your Original Products Table (unchanged) -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card-table">
                    <div class="card-body">
                        <div class="table-fluid">
                            <table id="productTable" class="table table-center table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Brand Name</th>
                                        <th>Product Name</th>
                                        <th>Model</th>
                                        <th>Photos</th>
                                        <th>Warranty</th>
                                        <th>Status</th>
                                        <th class="no-sort">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ Str::limit($product->brand->name, 20) }}
                                            </td>
                                            <td>{{ Str::limit($product->name, 20) }}
                                            </td>
                                            <td>{{ $product->model ?? 'N/A' }}</td>
                                            <td>
                                                @if ($product->photos && count($product->photos) > 0)
                                                    <div class="d-flex gap-1">
                                                        @foreach ($product->photos as $index => $photo)
                                                            @if ($index < 2)
                                                                <img src="{{ asset($photo) }}" class="img-thumbnail"
                                                                    style="height:60px;width:60px;object-fit:cover;">
                                                            @endif
                                                        @endforeach
                                                        @if (count($product->photos) > 2)
                                                            <span class="badge bg-secondary">+{{ count($product->photos) - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No photos</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->warranty ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->status == 1 ? 'success' : 'danger' }}">
                                                    {{ $product->status == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="list-unstyled mb-0">
                                                            <!--<li><a class="dropdown-item"-->
                                                            <!--        href="{{ route('products.edit', $product) }}">-->
                                                            <!--        <i class="far fa-edit me-2"></i>Edit</a></li>-->
                                                            <!--<li> -->
                                                             <li>
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#edit-product-modal{{ $product->id }}">
                                                                    <i class="far fa-edit me-2"></i>Edit
                                                                </a>
                                                            </li>
                                                                <form id="delete{{ $product->id }}"
                                                                    action="{{ route('products.destroy', $product->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf @method('DELETE')
                                                                </form>
                                                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                                    onclick="if(confirm('Delete product?')) document.getElementById('delete{{ $product->id }}').submit();">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2-brand').select2({
                width: '100%',
                dropdownParent: $('#add-product-modal')
            });

            let currentScanTarget = 'barcode'; // barcode or serial

            $('#scan-barcode-btn, #scan-serial-btn').on('click', function () {
                currentScanTarget = this.id === 'scan-barcode-btn' ? 'barcode' : 'serial';
                new bootstrap.Modal('#cameraScannerModal').show();
                setTimeout(startScanner, 600);
            });

            // Allow manual typing
            $('#product-barcode').on('input', function () {
                const val = $(this).val().trim();
                $('#form-barcode').val(val);
                updateBarcodePreview(val);
                autoFillSerial(val);
            });

            $('#product-serial').on('input', function () {
                $('#form-serial').val($(this).val().trim());
            });
        });

        function startScanner() {
            const html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 300,
                    height: 120
                }
            },
                (decodedText) => {
                    playBeep();
                    if (currentScanTarget === 'barcode') {
                        $('#product-barcode').val(decodedText).trigger('input').addClass('barcode-scanned');
                        $('#barcode-status').html('Scanned').addClass('text-success');
                        autoFillSerial(decodedText);
                    } else {
                        $('#product-serial').val(decodedText).addClass('barcode-scanned');
                        $('#form-serial').val(decodedText);
                        $('#serial-status').html('Scanned').addClass('text-success');
                    }
                    setTimeout(() => $('.barcode-scanned').removeClass('barcode-scanned'), 1500);
                    html5QrCode.stop();
                    bootstrap.Modal.getInstance('#cameraScannerModal').hide();
                },
                () => { }
            ).catch(() => {
                $('#reader').html('<div class="text-danger">Camera not available</div>');
            });
        }

        function autoFillSerial(barcode) {
            if (!barcode || barcode.length < 12) {
                $('#product-serial').val('');
                $('#form-serial').val('');
                $('#serial-preview').html('Serial preview');
                return;
            }

            // Common patterns: last 10-15 digits are serial
            const possibleSerial = barcode.slice(-15);
            if (possibleSerial.length >= 8 && !isNaN(possibleSerial)) {
                $('#product-serial').val(possibleSerial);
                $('#form-serial').val(possibleSerial);
                $('#serial-preview').html(`<strong>Auto Serial:</strong><br>${possibleSerial}`);
                $('#serial-status').html('Auto-filled').addClass('text-info');
            }
        }

        function updateBarcodePreview(text) {
            if (text) {
                $('#barcode-preview').html(`
                            <strong>Barcode</strong><br>
                            <div style="font-size:1.3em; letter-spacing:1px;">${text}</div>
                            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=${encodeURIComponent(text)}&scale=3&height=12&includetext">
                        `);
            } else {
                $('#barcode-preview').html('Barcode will appear here');
            }
        }

        function playBeep() {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.connect(g);
            g.connect(ctx.destination);
            o.frequency.value = 900;
            o.start();
            o.stop(ctx.currentTime + 0.1);
        }
    </script>
@endsection