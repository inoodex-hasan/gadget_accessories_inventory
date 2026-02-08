<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        @page {
            margin: 40px 50px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #2c3e50;
            font-size: 13px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            color: #0d6efd;
            font-size: 26px;
            letter-spacing: 1px;
        }

        .header p {
            margin: 3px 0 0;
            color: #555;
            font-size: 13px;
        }

        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #222;
            margin-bottom: 10px;
        }

        .period {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #0d6efd;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px 8px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        th:nth-child(1),
        td:nth-child(1),
        th:nth-child(3),
        td:nth-child(3),
        th:nth-child(4),
        td:nth-child(4),
        th:nth-child(5),
        td:nth-child(5) {
            text-align: center;
        }

        th:nth-child(2),
        td:nth-child(2),
        th:nth-child(6),
        td:nth-child(6) {
            text-align: left;
        }

        .summary-box {
            margin-top: 30px;
            padding: 10px 15px;
            background-color: #f1f7ff;
            border: 1px solid #bcd0f7;
            border-radius: 5px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin: 5px 0;
        }

        .summary-item strong {
            color: #0d6efd;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Inventory Management System</h1>
        <p>Sales Report</p>
    </div>

    <div class="report-title">Sales Report</div>
    <div class="period">Generated on {{ now()->format('F d, Y H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Sale Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQty = 0;
                $totalAmount = 0;
            @endphp
            @forelse($salesReport as $index => $sale)
                @php
                    $totalQty += $sale->qty;
                    $totalAmount += $sale->total_price;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale->product_name ?? 'N/A' }}</td>
                    <td>{{ $sale->qty }}</td>
                    <td>{{ number_format($sale->unit_price, 2) }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No sales data available</td>
                </tr>
            @endforelse
            @if ($salesReport->count() > 0)
                <tr style="background-color: #e3f2fd; font-weight: bold;">
                    <td colspan="2" style="text-align: right;">TOTAL:</td>
                    <td>{{ $totalQty }}</td>
                    <td></td>
                    <td>{{ number_format($totalAmount, 2) }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    @if ($salesReport->count() > 0)
        <div class="summary-box">
            <div class="summary-item">
                <span>Total Items Sold:</span>
                <strong>{{ $totalQty }}</strong>
            </div>
            <div class="summary-item">
                <span>Total Sales Amount:</span>
                <strong>৳ {{ number_format($totalAmount, 2) }} Tk</strong>
            </div>
        </div>
    @endif

    <footer>
        © {{ date('Y') }} Inventory Management System | Developed by <a href="inoodex.com">Inoodex</a>
    </footer>
</body>

</html>
