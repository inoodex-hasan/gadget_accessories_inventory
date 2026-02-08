<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Revenue Report - {{ $revenue->month_name }} {{ $revenue->year }}</title>
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #4872b1;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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
            color: #66a3ff;
        }

        .profit {
            color: #198754;
            font-weight: bold;
        }

        .loss {
            color: #dc3545;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Inventory Management System</h1>
        {{-- <p>Admin Revenue Report</p> --}}
    </div>

    <div class="report-title">Monthly Revenue Report</div>
    <div class="period">{{ $revenue->month_name }} {{ $revenue->year }}</div>

    <table>
        <tr>
            <th>Year</th>
            <th>Month</th>
            <th>Total Sales</th>
            <th>Total Purchases</th>
            <th>Total Expenses</th>
            <th>Net Profit</th>
        </tr>
        <tr>
            <td>{{ $revenue->year }}</td>
            <td>{{ $revenue->month_name }}</td>
            <td>{{ number_format($revenue->total_sales, 2) }}</td>
            <td>{{ number_format($revenue->total_purchases, 2) }}</td>
            <td>{{ number_format($revenue->total_expenses, 2) }}</td>
            <td class="{{ $revenue->net_profit >= 0 ? 'profit' : 'loss' }}">
                {{ number_format($revenue->net_profit, 2) }}
            </td>
        </tr>
    </table>

    <div class="summary-box">
        <div class="summary-item">
            <span>Total Sales:</span>
            <strong style="float: right;">{{ number_format($revenue->total_sales, 2) }} Tk</strong>
        </div>
        <div class="summary-item">
            <span>Total Purchases:</span>
            <strong style="float: right;">{{ number_format($revenue->total_purchases, 2) }} Tk</strong>
        </div>
        <div class="summary-item">
            <span>Total Expenses:</span>
            <strong style="float: right;">{{ number_format($revenue->total_expenses, 2) }} Tk</strong>
        </div>
        <hr>
        <div class="summary-item" style="margin-top: 10px; border-top: 1px dashed #ccc; padding-top: 8px;">
            <span style="font-weight: 600;">Net Result:</span>
            @if ($revenue->net_profit >= 0)
                <strong style="float: right; color: #198754; font-size: 15px;">
                    Profit: {{ number_format($revenue->net_profit, 2) }} Tk
                </strong>
            @else
                <strong style="float: right; color: #dc3545; font-size: 15px;">
                    Loss: {{ number_format(abs($revenue->net_profit), 2) }} Tk
                </strong>
            @endif
        </div>

    </div>

    <footer>
         © {{ date('Y') }} Inventory Management System | Developed by <a href="inoodex.com">Inoodex</a>
    </footer>
</body>

</html>
