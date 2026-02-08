<!DOCTYPE html>
<html>

<head>
    <title>Barcode Label - {{ $item->serial_number }}</title>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .barcode-1d-container svg,
            .qr-code-container svg {
                /* Force explicit physical dimensions */
                width: 100% !important;
                /* Use 100% of the container width */
                height: 0.5in !important;
                /* Set a mandatory height */
                max-width: none !important;
                /* Prevent shrinking */
            }

            /* Ensure no margins/padding are pushing it off the page */
            .print-label {
                margin: 0 !important;
                padding: 0 !important;
            }

            .print-area,
            .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }

        .label-container {
            width: 3.5in;
            height: 2in;
            padding: 15px;
            border: 1px solid #ddd;
            margin: 10px auto;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .barcode-container {
            margin: 10px 0;
        }

        .barcode-container svg {
            height: 100%;
            width: 100%;
        }

        .label-info {
            font-size: 12px;
            margin-top: 5px;
        }

        .label-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container print-area">

        <!-- Barcode Label -->
        <div class="label-container">
            <div class="label-title">INVENTORY ITEM</div>
            <div class="barcode-container">
                {!! $barcode['1d'] !!}
            </div>
            <div class="label-info">
                <div><strong>SN:</strong> {{ $item->serial_number }}</div>
                <div><strong>Product:</strong> {{ $item->product->name ?? 'N/A' }}</div>
                <div><strong>Status:</strong> {{ ucfirst($item->unit_status) }}</div>
                <div><strong>Date:</strong> {{ $item->purchase_date->format('Y-m-d') }}</div>
            </div>
        </div>

        <!-- QR Code Label (optional) -->
        {{-- <div class="label-container" style="margin-top: 20px;">
            <div class="mb-4 p-3 bg-light rounded d-flex flex-column justify-content-center align-items-center">
                <small class="text-muted d-block mb-1">QR Code</small>
                <br>
                <div class="text-center">
                    {!! $barcode['qr'] !!}
                </div>


            </div>
            <div class="label-info">
                <small>{{ $item->barcode_data }}</small>
            </div>
        </div> --}}

        <div class="mb-4 p-3 bg-light rounded d-flex flex-column justify-content-center align-items-center">
            <div class="text-center mb-4 no-print">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Label
                </button>
                <button onclick="window.close()" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Close Window
                </button>
            </div>
        </div>
    </div>

    <script>
        // Auto-print if desired
        // window.onload = function() {
        //     window.print();
        // }
    </script>
</body>

</html>
