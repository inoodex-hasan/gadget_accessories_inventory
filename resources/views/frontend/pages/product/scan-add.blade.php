@extends('frontend.layouts.app')
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <title>Scan & Add Product</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://unpkg.com/html5-qrcode"></script>
        <style>
            body {
                font-family: Arial;
                padding: 20px;
            }

            #scanner-container {
                width: 100%;
                max-width: 500px;
                margin: 20px auto;
            }

            #reader {
                width: 100%;
            }

            .result {
                padding: 15px;
                margin: 10px 0;
                border-radius: 5px;
            }

            .success {
                background: #d4edda;
                color: #155724;
            }

            .error {
                background: #f8d7da;
                color: #721c24;
            }

            .info {
                background: #d1ecf1;
                color: #0c5460;
            }

            button {
                padding: 10px 20px;
                margin: 10px;
                background: #007bff;
                color: white;
                border: none;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <h1> Scan & Add Product</h1>

        <!-- Scanner -->
        <div id="scanner-container">
            <div id="reader"></div>
        </div>

        <!-- Manual Input -->
        <div style="margin: 20px 0;">
            <h3>Or enter barcode manually:</h3>
            <input type="text" id="manualBarcode" placeholder="Enter barcode">
            <button onclick="submitManualBarcode()">Add Product</button>
        </div>

        <!-- Results -->
        <div id="result"></div>

        <script>
            let scannedBarcode = '';
            let html5QrCode = null;

            // Start scanner
            function startScanner() {
                html5QrCode = new Html5Qrcode("reader");

                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    onScanSuccess,
                    onScanFailure
                );
            }

            function onScanSuccess(decodedText) {
                scannedBarcode = decodedText;
                showResult(`Scanned: <strong>${decodedText}</strong>`, 'info');

                // Auto-process after 1 second
                setTimeout(() => {
                    processBarcode(decodedText);
                }, 1000);

                // Stop scanner
                if (html5QrCode) {
                    html5QrCode.stop();
                }
            }

            function onScanFailure(error) {
                console.log('Scan error:', error);
            }

            function submitManualBarcode() {
                const barcode = document.getElementById('manualBarcode').value;
                if (!barcode) {
                    showResult('Please enter a barcode', 'error');
                    return;
                }
                processBarcode(barcode);
            }

            function processBarcode(barcode) {
                showResult('Processing...', 'info');

                // Send to server
                fetch('/scan-add-product', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            barcode_data: barcode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showResult(`
                         <strong>Success!</strong><br>
                        ${data.message}<br>
                        Product: ${data.product?.name || 'N/A'}<br>
                        Stock: ${data.inventory?.current_stock || 1}
                    `, 'success');

                            // Reset after 3 seconds
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            showResult(`Error: ${data.message || 'Unknown error'}`, 'error');
                        }
                    })
                    .catch(error => {
                        showResult(`Network error: ${error}`, 'error');
                    });
            }

            function showResult(message, type) {
                document.getElementById('result').innerHTML =
                    `<div class="result ${type}">${message}</div>`;
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', startScanner);
        </script>
    </body>

    </html>
@endsection
