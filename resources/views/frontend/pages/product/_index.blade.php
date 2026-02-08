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
            background-color: #fff !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px !important;
            color: #495057 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            top: 1px !important;
            right: 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6c757d transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
            height: 0 !important;
            width: 0 !important;
            margin-left: -4px !important;
            margin-top: -2px !important;
            top: 50% !important;
            left: 50% !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6c757d transparent !important;
            border-width: 0 4px 5px 4px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default .select2-selection--single:focus {
            border-color: #80bdff !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25) !important;
        }
    </style>

    <!-- Your original page header & Add button (unchanged) -->
    <div class="content container-fluid col-sm-10">
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
                        <!-- Barcode Card -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-8">
                                        <label>Barcode <span class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="product-barcode"
                                                placeholder="Scan or type..." autocomplete="off">
                                            <button class="btn btn-outline-primary" id="scan-barcode-btn"><i
                                                    class="fas fa-camera"></i> Scan</button>
                                            <button class="btn btn-outline-info" id="lookup-barcode-btn"><i
                                                    class="fas fa-search"></i> Lookup</button>
                                        </div>
                                        <small id="scanner-status" class="text-muted">Ready</small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="barcode-preview" id="barcode-preview">Barcode preview appears here</div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Form -->
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
                            id="add-product-form">
                            @csrf
                            <input type="hidden" name="barcode" id="form-barcode">

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label>Brand <span class="text-danger">*</span></label>
                                    <select class="form-control select2-brand" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Model Name <span class="text-danger">*</span></label>
                                    <input type="text" name="model_name" id="model_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Warranty (Days) <span class="text-danger">*</span></label>
                                    <input type="number" name="warranty" id="warranty" class="form-control" value="365"
                                        required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Photos</label>
                                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save Product</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Camera Modal (unchanged) -->
        <div class="modal fade" id="cameraScannerModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5>Scan Barcode</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div id="reader"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- YOUR ORIGINAL TABLE LOOK - 100% RESTORED -->
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
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->model_name ?? 'N/A' }}</td>
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
                                                            <span
                                                                class="badge bg-secondary">+{{ count($product->photos) - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No photos</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->warranty ?? 'N/A' }}</td>
                                            <td>
                                                @if ($product->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a href="#" class="btn-action-icon"
                                                        data-bs-toggle="dropdown"><i class="fas fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="list-unstyled mb-0">
                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('products.edit', $product) }}"><i
                                                                        class="far fa-edit me-2"></i>Edit</a></li>
                                                            <li>
                                                                <form id="delete{{ $product->id }}"
                                                                    action="{{ route('products.destroy', $product->id) }}"
                                                                    method="POST" style="display:none;">
                                                                    @csrf @method('DELETE')
                                                                </form>
                                                                <a class="dropdown-item text-danger"
                                                                    href="javascript:void(0)"
                                                                    onclick="if(confirm('Delete product?')) document.getElementById('delete{{ $product->id }}').submit();">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete
                                                                </a>
                                                            </li>
                                                            @if ($product->barcode)
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:generateBarcodeLabel('{{ $product->barcode }}','{{ addslashes($product->name) }}')">
                                                                        <i class="fas fa-print me-2"></i>Print Label</a>
                                                                </li>
                                                            @endif
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

    <!-- Scripts (test data removed, table look untouched) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2-brand').select2({
                width: '100%',
                dropdownParent: $('#add-product-modal')
            });

            $('#scan-barcode-btn').on('click', function() {
                new bootstrap.Modal('#cameraScannerModal').show();
                setTimeout(startScanner, 600);
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
                (text) => {
                    playBeep();
                    processBarcode(text);
                    html5QrCode.stop();
                    bootstrap.Modal.getInstance(document.getElementById('cameraScannerModal')).hide();
                },
                () => {}
            ).catch(() => {
                $('#reader').html('<div class="text-danger">Camera not available</div>');
            });
        }

        function processBarcode(barcode) {
            $('#product-barcode').val(barcode).addClass('barcode-scanned');
            $('#form-barcode').val(barcode);
            $('#scanner-status').html('Scanned: <strong>' + barcode + '</strong>').addClass('text-success');
            generatePreview(barcode);
            setTimeout(() => $('#product-barcode').removeClass('barcode-scanned'), 2000);
        }

        function generatePreview(barcode) {
            $('#barcode-preview').html(`
        <strong>${barcode}</strong><br>
        <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=${barcode}&scale=3&height=10">
    `);
        }

        function playBeep() {
            const ctx = new(window.AudioContext || window.webkitAudioContext)();
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.connect(g);
            g.connect(ctx.destination);
            o.frequency.value = 880;
            o.start();
            o.stop(ctx.currentTime + 0.1);
        }

        function generateBarcodeLabel(barcode, name) {
            // your existing print function
        }
    </script>
@endsection
