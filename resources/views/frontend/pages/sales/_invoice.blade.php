<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0 auto;
            padding: 1.5cm;
            width: 21cm;
            height: 29.7cm;
            background-color: #fff;
            color: #000;
            font-size: 12px;
            line-height: 1.3;

            box-sizing: border-box;
        }

        .container {
            width: 100%;
            height: 100%;
        }

        .header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .company-info {
            text-align: left;
            width: 45%;
        }

        .invoice-info {
            text-align: right;
            width: 45%;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #000;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            margin-right: 5px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
        }

        .customer-info {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .customer-left {
            text-align: left;
            width: 45%;
        }

        .customer-right {
            text-align: right;
            width: 45%;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .terms {
            width: 45%;
            text-align: left;
        }

        .totals {
            width: 45%;
            text-align: right;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 5px 0;
            text-align: right;
        }

        .totals td:first-child {
            text-align: left;
            padding-right: 10px;
        }

        .in-words {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            text-align: center;
        }

        .signatures {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            width: 45%;
            border-top: 1px solid #000;
            text-align: center;
            margin-top: 40px;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <div class="company-name">
                    <span class="logo">i</span> Intelligent Technology
                </div>
                Phone: 01904400202<br>
                Email: info.itechbd@yahoo.com
            </div>
            <div class="invoice-info">
                Date: 09/10/2025<br>
                Address: House # 7, (3rd floor), Road # 4,<br>
                Mirpur-10, Dhaka -1216, Bangladesh.
            </div>
        </div>

        <div class="invoice-title">INVOICE</div>

        <div class="customer-info">
            <div class="customer-left">
                <strong>Customer:</strong> {{ $customer->name }}<br>
                <strong>Phone:</strong> {{ $customer->phone }}<br>
                <strong>Address:</strong> {{ $customer->address }}
            </div>
            <div class="customer-right">
                <strong>Invoice No:</strong> {{ $sales->order_no }}<br>
                <strong>Invoice Date:</strong>
                @if (isset($sales[0]) && $sales[0]->created_at)
                    {{ $sales[0]->created_at->format('d-m-Y') }}
                @else
                    {{ date('d-m-Y') }}
                @endif
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Sl</th>
                    <th>Items Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->qty ?? 'N/A' }}</td>
                        <td>{{ $item->unit_price ?? 'N/A' }}</td>
                        <td>{{ $item->total_price ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer-section">
            <div class="terms">
                <strong>Terms & Conditions</strong><br>
                1. Products can be returned within 7 days in their
                original, unopened condition.

                <p>2. Refunds or exchanges
                    are offered, but perishable goods cannot be
                    returned. </p>

                Contact us at <strong>01904400205</strong> with a valid
                receipt for assistance.</p>
            </div>
            <div class="totals">
                <table>
                    <tr>
                        <td>Sub Total:</td>
                        <td>{{ $sales->bill }} Tk</td>
                    </tr>
                    <tr>
                        <td>Discount:</td>
                        <td>{{ $sales->discount }}Tk</td>
                    </tr>
                    <tr>
                        <td>Payable:</td>
                        <td>{{ $sales->payble }} Tk</td>
                    </tr>
                    <tr>
                        <td>Received:</td>
                        <td>{{ $sales->advanced_payment }}</td>
                    </tr>
                    <tr>
                        <td>Total Due:</td>
                        <td>{{ $sales->due_payment }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Previous Receivable:</td>
                        <td>#</td>
                    </tr>
                    <tr>
                        <td>Current Receivable:</td>
                        <td>#</td>
                    </tr> --}}
                </table>
            </div>
        </div>

        <div class="in-words">
            <strong>In Words:</strong>
            @php
                if (is_countable($sales)) {
                    $totalAmount = collect($sales)->sum('bill');
                } else {
                    $totalAmount = $sales->bill ?? 0;
                }
            @endphp
            {{ numberToWords($totalAmount) }} Taka Only
        </div>

        <div class="signatures">
            <div class="signature">Customer Signature</div>
            <div class="signature">Authorized Signature</div>
        </div>

        <div class="page-number">
            Page 1/1
        </div>
    </div>
</body>

</html>
