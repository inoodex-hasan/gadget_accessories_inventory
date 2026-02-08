@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Scanner Section -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-barcode"></i> Barcode Scanner</h4>
                    </div>
                    <div class="card-body">
                        <!-- Scanner Options -->
                        <div class="mb-3">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" id="toggle-camera">
                                    <i class="fas fa-camera"></i> Camera Scanner
                                </button>
                                <button type="button" class="btn btn-outline-success" id="toggle-manual">
                                    <i class="fas fa-keyboard"></i> Manual Input
                                </button>
                            </div>
                        </div>

                        <!-- Camera Scanner -->
                        <div id="camera-scanner" class="scanner-section">
                            <div class="text-center">
                                <div id="reader" width="100%" height="300"></div>
                                <div class="mt-3">
                                    <button class="btn btn-success" id="start-scanner">
                                        <i class="fas fa-play"></i> Start Scanner
                                    </button>
                                    <button class="btn btn-danger" id="stop-scanner" disabled>
                                        <i class="fas fa-stop"></i> Stop Scanner
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Input -->
                        <div id="manual-input" class="scanner-section" style="display: none;">
                            <div class="form-group">
                                <label for="barcode-input">Enter Barcode:</label>
                                <input type="text" class="form-control form-control-lg text-center" id="barcode-input"
                                    placeholder="Scan or type barcode" autofocus>
                                <small class="form-text text-muted">
                                    Press Enter or focus away to submit
                                </small>
                            </div>
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" id="submit-barcode">
                                    <i class="fas fa-check"></i> Submit Barcode
                                </button>
                            </div>
                        </div>

                        <!-- Last Scanned Barcode -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6>Last Scanned:</h6>
                            <div class="d-flex justify-content-between align-items-center">
                                <code id="last-barcode" class="fs-4">-</code>
                                <button class="btn btn-sm btn-outline-secondary" id="copy-barcode">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information Section -->
            <div class="col-md-6">
                <div id="product-section">
                    <!-- Will be populated dynamically -->
                    <div class="card">
                        <div class="card-body text-center text-muted">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <h5>Scan a barcode to see product details</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Modal -->
    <div class="modal fade" id="quickActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .scanner-section {
            transition: all 0.3s ease;
        }

        #reader video {
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }

        .product-card {
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .barcode-preview {
            background: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <!-- HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        let html5QrCode = null;
        let lastScanTime = 0;
        const scanDelay = 2000; // 2 seconds between scans

        // Toggle between camera and manual input
        document.getElementById('toggle-camera').addEventListener('click', function() {
            document.getElementById('camera-scanner').style.display = 'block';
            document.getElementById('manual-input').style.display = 'none';
            this.classList.add('active');
            document.getElementById('toggle-manual').classList.remove('active');
        });

        document.getElementById('toggle-manual').addEventListener('click', function() {
            document.getElementById('camera-scanner').style.display = 'none';
            document.getElementById('manual-input').style.display = 'block';
            this.classList.add('active');
            document.getElementById('toggle-camera').classList.remove('active');
            document.getElementById('barcode-input').focus();
        });

        // Camera Scanner
        document.getElementById('start-scanner').addEventListener('click', function() {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 150
                },
                aspectRatio: 1.777778 // 16:9
            };

            html5QrCode.start({
                    facingMode: "environment"
                },
                config,
                onScanSuccess,
                onScanError
            ).then(() => {
                document.getElementById('start-scanner').disabled = true;
                document.getElementById('stop-scanner').disabled = false;
                showToast('Scanner started', 'success');
            });
        });

        document.getElementById('stop-scanner').addEventListener('click', function() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('start-scanner').disabled = false;
                    document.getElementById('stop-scanner').disabled = true;
                    showToast('Scanner stopped', 'info');
                });
            }
        });

        // Prevent duplicate scans
        function onScanSuccess(decodedText) {
            const currentTime = new Date().getTime();
            if (currentTime - lastScanTime < scanDelay) {
                return; // Ignore if scanned too recently
            }
            lastScanTime = currentTime;

            // Visual feedback
            document.getElementById('reader').style.border = '3px solid #28a745';
            setTimeout(() => {
                document.getElementById('reader').style.border = '2px solid #dee2e6';
            }, 500);

            // Process barcode
            processBarcode(decodedText);
        }

        function onScanError(error) {
            console.error('Scan error:', error);
        }

        // Manual barcode input
        document.getElementById('barcode-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                processBarcode(this.value.trim());
            }
        });

        document.getElementById('submit-barcode').addEventListener('click', function() {
            const barcode = document.getElementById('barcode-input').value.trim();
            if (barcode) {
                processBarcode(barcode);
            }
        });

        // Hardware barcode scanner detection (auto-submit on Enter)
        let barcodeBuffer = '';
        let lastKeyTime = 0;
        document.addEventListener('keydown', function(e) {
            const currentTime = new Date().getTime();

            // If key pressed quickly after last key, assume barcode scanner
            if (currentTime - lastKeyTime < 100) {
                barcodeBuffer += e.key;
            } else {
                barcodeBuffer = e.key;
            }

            lastKeyTime = currentTime;

            // Check for Enter key (scanners usually send Enter after barcode)
            if (e.key === 'Enter' && barcodeBuffer.length > 3) {
                e.preventDefault();
                processBarcode(barcodeBuffer.replace('Enter', '').trim());
                barcodeBuffer = '';
            }
        });

        // Process barcode
        function processBarcode(barcode) {
            if (!barcode || barcode.length < 3) {
                showToast('Invalid barcode', 'error');
                return;
            }

            // Update last scanned display
            document.getElementById('last-barcode').textContent = barcode;

            // Show loading
            document.getElementById('product-section').innerHTML = `
        <div class="card">
            <div class="card-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Processing barcode...</p>
            </div>
        </div>
    `;

            // Send to server
            fetch('{{ route('barcode.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        barcode: barcode
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.exists) {
                            showExistingProduct(data.product);
                        } else {
                            showNewProductForm(barcode);
                        }
                        playScanSound();
                    } else {
                        showToast(data.message || 'Error processing barcode', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error connecting to server', 'error');
                });
        }

        // Show existing product
        function showExistingProduct(product) {
            document.getElementById('product-section').innerHTML = `
        <div class="card product-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle"></i> Product Found
                    <span class="badge bg-light text-dark float-end">${product.quantity} in stock</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>${product.name}</h4>
                        <p class="text-muted">${product.description || 'No description'}</p>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <strong>Barcode:</strong><br>
                                <code>${product.barcode}</code>
                            </div>
                            <div class="col-6">
                                <strong>Price:</strong><br>
                                $${parseFloat(product.price).toFixed(2)}
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <strong>SKU:</strong><br>
                                ${product.sku || 'N/A'}
                            </div>
                            <div class="col-6">
                                <strong>Last Updated:</strong><br>
                                ${new Date(product.updated_at).toLocaleDateString()}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="barcode-preview mb-3">
                            <div id="barcode-display-${product.id}"></div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="editProduct(${product.id})">
                                <i class="fas fa-edit"></i> Edit Product
                            </button>
                            <button class="btn btn-outline-secondary" onclick="viewProduct(${product.id})">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-success" onclick="addStock(${product.id})">
                                <i class="fas fa-plus"></i> Add Stock
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <small class="text-muted">
                    Scanned at ${new Date().toLocaleTimeString()}
                </small>
            </div>
        </div>
    `;

            // Generate barcode display
            fetch(`/barcode/generate/${product.barcode}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById(`barcode-display-${product.id}`).innerHTML = html;
                });
        }

        // Show new product form
        function showNewProductForm(barcode) {
            document.getElementById('product-section').innerHTML = `
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i> New Product Detected
                </h5>
            </div>
            <div class="card-body">
                <form id="new-product-form">
                    @csrf
                    <input type="hidden" name="barcode" value="${barcode}">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" value="${barcode}" readonly>
                                <small class="form-text text-muted">Scanned barcode</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" required autofocus>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Initial Quantity</label>
                                    <input type="number" name="quantity" class="form-control" value="1" min="0">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">SKU (Optional)</label>
                                <input type="text" name="sku" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="barcode-preview mb-3">
                                <div id="new-barcode-preview"></div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Save Product
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
        </div>
    `;

            // Generate barcode preview
            fetch(`/barcode/generate/${barcode}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('new-barcode-preview').innerHTML = html;
                });

            // Handle form submission
            document.getElementById('new-product-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route('products.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Product created successfully!', 'success');
                            showExistingProduct(data.product);
                        } else {
                            showToast(data.message || 'Error creating product', 'error');
                        }
                    });
            });
        }

        // Utility functions
        function showToast(message, type = 'info') {
            // Implement toast notification
            console.log(`${type}: ${message}`);
        }

        function playScanSound() {
            const audio = new Audio('{{ asset('sounds/beep.mp3') }}');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Copy barcode to clipboard
        document.getElementById('copy-barcode').addEventListener('click', function() {
            const barcode = document.getElementById('last-barcode').textContent;
            navigator.clipboard.writeText(barcode).then(() => {
                showToast('Barcode copied to clipboard', 'success');
            });
        });
    </script>
@endpush
