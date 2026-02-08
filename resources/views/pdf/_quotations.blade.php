{{-- <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .company-address {
            font-size: 11px;
            margin-bottom: 5px;
        }

        .quotation-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }

        .quotation-info {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .client-info {
            width: 60%;
        }

        .quotation-details {
            width: 35%;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 10px;
        }

        .info-box h3 {
            margin: 0 0 8px 0;
            font-size: 12px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .serial {
            width: 5%;
            text-align: center;
        }

        .product-description {
            width: 45%;
        }

        .quantity,
        .unit-price,
        .total-price {
            width: 15%;
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .total-row td {
            text-align: right;
            padding-right: 20px;
        }

        .amount-section {
            margin: 15px 0;
        }

        .amount-in-words {
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            font-style: italic;
            margin-bottom: 15px;
        }

        .summary-table {
            width: 300px;
            margin-left: auto;
        }

        .terms-section {
            margin: 20px 0;
        }

        .terms-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
        }

        .terms-content {
            font-size: 11px;
        }

        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 300px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 40px auto 5px auto;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .product-specs {
            margin-left: 10px;
        }

        .notes {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">YOUR COMPANY NAME</div>
            <div class="company-tagline">Your Company Tagline</div>
            <div class="company-address">
                123 Company Address, City, State - ZIP Code<br>
                Phone: +1 234 567 890 | Email: info@company.com | Website: www.company.com
            </div>
        </div>

        <!-- Quotation Title -->
        <div class="quotation-title">QUOTATION</div>

        <!-- Quotation Information -->
        <div class="quotation-info">
            <div class="client-info">
                <div class="info-box">
                    <h3>quotation TO</h3>
                    <p><strong>{{ $quotation->client->name }}</strong></p>
                    <p>{{ $quotation->client->address ?? 'Address not specified' }}</p>
                    <p>Phone: {{ $quotation->client->phone ?? 'N/A' }}</p>
                    <p>Email: {{ $quotation->client->email ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="quotation-details">
                <div class="info-box">
                    <h3>QUOTATION DETAILS</h3>
                    <p><strong>Quotation No:</strong> {{ $quotation->quotation_number }}</p>
                    <p><strong>Date:</strong> {{ $quotation->quotation_date->format('F d, Y') }}</p>
                    <p><strong>Valid Until:</strong> {{ $quotation->expiry_date->format('F d, Y') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($quotation->status) }}</p>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if ($quotation->notes)
            <div class="notes">
                <strong>Notes:</strong> {{ $quotation->notes }}
            </div>
        @endif

        <!-- Products Table -->
        <table>
            <thead>
                <tr>
                    <th class="serial">#</th>
                    <th class="product-description">PRODUCT DESCRIPTION</th>
                    <th class="quantity">QUANTITY</th>
                    <th class="unit-price">UNIT PRICE</th>
                    <th class="total-price">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotation->items as $index => $item)
                    <tr>
                        <td class="serial">{{ $index + 1 }}</td>
                        <td class="product-description">
                            <strong>{{ $item->product->name }}</strong>
                            @if ($item->product->brand)
                                <br><em>Brand: {{ $item->product->brand->name }}</em>
                            @endif
                            @if ($item->product->model)
                                <br><em>Model: {{ $item->product->model }}</em>
                            @endif
                            @if ($item->description)
                                <div class="product-specs">
                                    {!! nl2br(e($item->description)) !!}
                                </div>
                            @endif
                        </td>
                        <td class="quantity">{{ number_format($item->quantity) }}</td>
                        <td class="unit-price">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="total-price">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Amount Summary -->
        <div class="amount-section">
            <table class="summary-table">
                <tr class="total-row">
                    <td>Sub Total:</td>
                    <td>{{ number_format($quotation->sub_total, 2) }}</td>
                </tr>
                @if ($quotation->discount_amount > 0)
                    <tr class="total-row">
                        <td>Discount:</td>
                        <td>- {{ number_format($quotation->discount_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td><strong>Total Amount:</strong></td>
                    <td><strong>{{ number_format($quotation->total_amount, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Amount in Words -->
        <div class="amount-in-words">
            In Word: {{ $amount_in_words }}
        </div>

        <!-- Terms and Conditions -->
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-content">
                <ol>
                    <li>This quotation is valid until {{ $quotation->expiry_date->format('F d, Y') }}</li>
                    <li>Prices are subject to change without prior notice</li>
                    <li>Delivery time: 7-10 working days after order confirmation</li>
                    <li>Payment terms: 50% advance, 50% before delivery</li>
                    <li>Warranty as per manufacturer terms and conditions</li>
                    <li>Taxes extra as applicable</li>
                    <li>Order confirmation subject to availability</li>
                </ol>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Prepared By</p>
                <div class="signature-line"></div>
                <p><strong>Authorized Signatory</strong></p>
            </div>

            <div class="signature-box">
                <p>For {{ $company_name ?? 'Your Company Name' }}</p>
                <div class="signature-line"></div>
                <p><strong>Authorized Signatory</strong></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            This is a computer generated quotation. No signature required.<br>
            Thank you for your business!
        </div>
    </div>
</body>

</html> --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .reference {
            margin-bottom: 20px;
            font-weight: bold;
        }

        .to-section {
            margin-bottom: 20px;
        }

        .to-section p {
            margin: 3px 0;
        }

        .subject {
            margin: 15px 0;
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }

        .letter-body {
            margin: 20px 0;
            text-align: justify;
        }

        .quotation-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }

        table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .product-description {
            width: 55%;
        }

        .quantity,
        .unit-price,
        .total-price {
            width: 15%;
            text-align: center;
        }

        .total-row {
            font-weight: bold;
        }

        .total-row td {
            text-align: right;
            padding-right: 20px;
        }

        .amount-in-words {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            font-style: italic;
            text-align: center;
            font-weight: bold;
        }

        .terms-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0 10px 0;
        }

        .terms-table {
            margin: 10px 0;
        }

        .terms-table td {
            vertical-align: top;
            padding: 5px 8px;
        }

        .terms-table td:first-child {
            width: 30px;
            font-weight: bold;
        }

        .bank-details {
            margin: 20px 0;
            padding: 15px;
            background-color: #f5f5f5;
            border-left: 4px solid #333;
        }

        .bank-details strong {
            display: inline-block;
            width: 180px;
        }

        .closing {
            margin: 20px 0;
            text-align: justify;
        }

        .signature-section {
            margin-top: 60px;
            position: relative;
        }

        .signature-content {
            float: right;
            text-align: center;
            width: 300px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 40px auto 5px auto;
        }

        .contact-info {
            margin-top: 10px;
            font-size: 11px;
        }

        .product-specs {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Reference and Date -->
        <div class="reference">
            <span style="float: left;">Ref: {{ $quotation->quotation_number }}</span>
            <span style="float: right;">{{ $quotation->quotation_date->format('F d, Y') }}</span>
            <div style="clear: both;"></div>
        </div>

        <!-- Recipient Details -->
        <div class="to-section">
            <p>To,</p>
            <p>{{ $client_designation ?? 'Director (IT)' }}</p>
            <p>{{ $client_name ?? 'N/A' }}</p>
            <p>{{ $client_address ?? 'N/A' }}</p>
            @if ($attention_to)
                <p>Attention: {{ $attention_to }}</p>
            @endif
        </div>

        <!-- Subject -->
        <div class="subject">
            Sub: <span
                style="text-decoration: underline;">{{ $subject ?? 'Quotation for Supplying of Products/Services' }}</span>
        </div>

        <!-- Letter Body -->
        <div class="letter-body">
            <p>{!! nl2br(e($body_content ?? '')) !!}</p>
        </div>

        <!-- Additional Enclosed -->
        @if ($additional_enclosed)
            <div class="additional-enclosed">
                <strong>Additional Enclosed Documents:</strong><br>
                {!! nl2br(e($additional_enclosed)) !!}
            </div>
        @endif

        <!-- Quotation Title -->
        <div class="quotation-title">QUOTATION</div>

        <!-- Products Table -->
        <table>
            <thead>
                <tr>
                    <th class="serial">S/L</th>
                    <th class="product-description">PRODUCT DESCRIPTION</th>
                    <th class="product-photo">PHOTO</th>
                    <th class="quantity">QTY.</th>
                    <th class="unit-price">UNIT PRICE</th>
                    <th class="total-price">TOTAL PRICE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotation->items as $index => $item)
                    <tr>
                        <td class="serial">{{ $index + 1 }}</td>
                        <td class="product-description">
                            @if ($item->product)
                                <strong>{{ $item->product->name }}</strong>
                                @if ($item->product->brand)
                                    <br><small>Brand: {{ $item->product->brand->name }}</small>
                                @endif
                                @if ($item->product->model)
                                    <br><small>Model: {{ $item->product->model }}</small>
                                @endif
                            @endif
                            @if ($item->description)
                                <div class="product-specs">
                                    {!! nl2br(e($item->description)) !!}
                                </div>
                            @endif
                        </td>
                        <td class="product-photo" style="text-align: center; width: 80px;">
                            @if ($item->product && $item->product->photos)
                                @php
                                    $photos = is_array($item->product->photos)
                                        ? $item->product->photos
                                        : json_decode($item->product->photos, true);
                                @endphp
                                @if (!empty($photos) && isset($photos[0]))
                                    <img src="{{ storage_path('app/public/' . $photos[0]) }}"
                                        style="height: 60px; width: 60px; object-fit: cover;" alt="Product Image">
                                @else
                                    <div
                                        style="height: 60px; width: 60px; line-height: 60px; border: 1px dashed #ccc; text-align: center;">
                                        No Image
                                    </div>
                                @endif
                            @else
                                <div
                                    style="height: 60px; width: 60px; line-height: 60px; border: 1px dashed #ccc; text-align: center;">
                                    No Image
                                </div>
                            @endif
                        </td>
                        <td class="quantity">{{ number_format($item->quantity) }}</td>
                        <td class="unit-price">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="total-price">{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach

                <!-- Total Rows -->
                <tr class="total-row">
                    <td colspan="5">Subtotal</td>
                    <td class="total-price">{{ number_format($quotation->sub_total, 2) }}</td>
                </tr>
                @if ($quotation->discount_amount > 0)
                    <tr class="total-row">
                        <td colspan="5">Discount</td>
                        <td class="total-price">-{{ number_format($quotation->discount_amount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td colspan="5">Total Amount</td>
                    <td class="total-price">{{ number_format($quotation->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Amount in Words -->
        <div class="amount-in-words">
            In Word: {{ $amount_in_words }}
        </div>

        <!-- Terms and Conditions -->
        @if ($terms_conditions)
            <div class="terms-title">Terms and Conditions</div>
            <table class="terms-table">
                @foreach (explode("\n", $terms_conditions) as $index => $term)
                    @if (trim($term))
                        <tr>
                            {{-- <td>{{ $index + 1 }}.</td> --}}
                            <td>{{ trim($term) }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endif

        <!-- Closing -->
        <div class="closing">
            <p> We assure you that we provide our best service at all
                times.</p>
            <p>Thank you once again.</p>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-content">
                <p>Yours Sincerely,</p>
                <div class="signature-line"></div>
                <p><strong>{{ $signatory_name ?? 'Engr. Shamsul Alam' }}</strong></p>
                <p>{{ $signatory_designation ?? 'Director (Technical)' }}</p>
                <p>For, <strong>{{ $company_name ?? 'Intelligent Technology' }}</strong></p>
                <div class="contact-info">
                    @if ($company_phone)
                        <p>Cell: {{ $company_phone }}</p>
                    @endif
                    @if ($company_email)
                        <p>E-mail: {{ $company_email }}</p>
                    @endif
                    @if ($company_website)
                        <p>Web: {{ $company_website }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>
