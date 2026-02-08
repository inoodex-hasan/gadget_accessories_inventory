<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Barcode Labels – 2 Per Row </title>
    <style>
        @page {
            size: A4;
            margin: 1.2cm 1cm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", Arial, sans-serif;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            height: 5.5cm;
            border: 3px solid #000;
            border-radius: 8px;
            margin-bottom: 1.8cm;
            padding: 0 30px;
            page-break-inside: avoid;
            background: white;
        }

        .barcode-svg {
            flex: 0 0 40%;
            text-align: left;
        }

        .barcode-svg svg {
            height: 90px !important;
            width: 100% !important;
            max-width: 420px;
        }

        .text {
            flex: 1;
            text-align: center;
            font-weight: 900;
            font-size: 26px;
            letter-spacing: 2px;
            color: #000;
        }
    </style>
</head>

<body>

    @php
        $pairs = array_chunk($barcodes, 2);
    @endphp

    @foreach ($pairs as $pair)
        <div class="row">
            <!-- First label -->
            <div class="barcode-svg">
                {!! $pair[0]['barcode'] !!}
            </div>
            <div class="text">
                {{ $pair[0]['barcode_data'] }}
            </div>
        </div>

        @if (isset($pair[1]))
            <div class="row">
                <div class="barcode-svg">
                    {!! $pair[1]['barcode'] !!}
                </div>
                <div class="text">
                    {{ $pair[1]['barcode_data'] }}
                </div>
            </div>
        @endif
    @endforeach

</body>

</html>