<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('assets/invoice/style.css') }}" />
</head>

<body>
    <button class="print-btn no-print" onclick="window.print()">
        Print Invoice
    </button>

    <div class="a4-container">
        <div class="invoice-header">
            <div class="header__left">
                <div class="logo">
                    <img src="{{ asset($companyInfo->photo) }}" alt="" />
                </div>
                <div class="company-info">
                      <h1>{{ $companyInfo->name }}</h1>
                    <p>{{ $companyInfo->address }}</p>
                    <p>{{ $companyInfo->email }}</p>
                </div>
            </div>
                      <div class="invoice-title">
                <p>Date: {{ date('d-m-Y') }}</p>
                <h1>INVOICE</h1>
            </div>
        </div>
        <!-- invoice address  -->
        <div class="address">
            <p>Address: {{ $companyInfo->address }}</p>
        </div>
        
        <div class="invoice-details">
            <ul>
                <li class="details__item">
                    <div class="customer">
                        <p>Customer:</p>
                        <p class="customer__name">{{ $customer->name }}</p>
                    </div>

                    <div class="right">
                        <p>Invoice No: {{ $sales->order_no }}</p>
                    </div>
                </li>
                <li class="details__item">
                    <div class="customer">
                        <p>Phone:</p>
                        <p class="customer__name">{{ $customer->phone }}</p>
                    </div>

                    <div class="right">
                        <p>Invoice Date:
                            {{ date('d-m-Y') }}

                        </p>
                    </div>
                </li>
                <li class="details__item">
                    <div class="customer">
                        <p>Address:</p>
                        <p class="customer__name">{{ $customer->address }}</p>
                    </div>

                    <div class="right"></div>
                </li>
            </ul>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Item Names</th>
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

        <div class="totals-section">
            <div class="conditions">
                <h2>Terms & Conditions</h2>
                <br>
                <p>
                    1. Products can be returned within 7 days in their original, unopened
                    condition.
                </p>
                <br>
                <p>2. Refunds or exchanges are offered, but perishable goods
                    cannot be returned.</p>
                <br>
                <p>Contact us at <strong>01xxxxxxxx</strong> with a valid receipt
                    for assistance.</p>
                </p>
            </div>
            <table class="totals-table">
                <tr>
                    <td>Sub Total:</td>
                    <td class="text-right">{{ $sales->bill }} Tk</td>
                </tr>
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">{{ $sales->discount }} Tk</td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td class="text-right">{{ $sales->payble }} Tk</td>
                </tr>
                <tr>
                    <td>Received:</td>
                    <td class="text-right">{{ $sales->advanced_payment }} Tk</td>
                </tr>
                <tr>
                    <td>Total Due:</td>
                    <td class="text-right">{{ $sales->due_payment }} Tk</td>
                </tr>
            </table>
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

        <!-- signature  -->
        <div class="signature">
            <div class="signature__left">
                <p>Customer Signature</p>
            </div>
            <div class="signature__right">
                <p>Authorized Signature</p>
            </div>
        </div>
        
          <div class="footer">
        <p>Developed by <a href="inoodex.com">Inoodex</a></p>
        </div>
    </div>

    <script>
  
        document
            .querySelector(".print-btn")
            .addEventListener("click", function() {
                window.print();
            });

    </script>
</body>

</html>
