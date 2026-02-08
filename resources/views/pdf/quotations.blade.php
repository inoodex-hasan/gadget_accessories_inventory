

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
      <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
           body {
    font-family: "Montserrat", sans-serif;
    font-size: 12px;
    line-height: 1.3;
    color: #000;
    margin: 0;
    padding: 20px;
         
    background:rgba(255,255,255,0.2) url("{{ public_path('big-logo.png') }}") 
                no-repeat 
                310px -10px / 340px auto 
                fixed 
                transparent !important;

  }

       
@page {
       margin: 160px 45px 90px 45px;
        size: A4 portrait;
    }

 
  .container {
    max-width: 800px;
    margin: 15px auto 15px;
     
  }
    header {
        position: fixed;
        top: -150px;
        left: 0;
        right: 0;
        display:block;
        height:100px;
        background:transparent;
        padding: 15px 45px 30px;     
        padding-bottom: 30px; 
        font-size: 11px;
        z-index: 10;
        }


      
   footer {
      position: fixed;
      bottom: -70px;
      left: 0;
      right: 0;
      height: 50px;
      padding: 10px 0;
      border-top: 1px solid #999; 
      font-size: 12px;
      display: flex;
      justify-content: space-between;
      text-align:center;
      align-items: center;
  } 

  .logo {
    max-width: 200px;
  }

  .logo img {
    width: 100%;
    text-align: left;
    opacity: 0.5;
    margin-left: -60px;
    margin-bottom: 50px !important;
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
    float: left;
    text-align: left;
    width: 300px;
  }

  .signature-line {
    border-top: 1px dashed #000;
    width: 200px;
    /* margin: 40px auto 5px auto; */
  }
        .contact-info {
            margin-top: 10px;
            font-size: 11px;
        }

        .product-specs {
            margin-left: 10px;
        }
 
        .sil{
            max-width:130px;

        }
        .sil img{
            width:100%;
        }
    </style>
</head>

<body>
    <header>
         <div class="logo">
        <img src="{{ public_path('logo.jpg') }}" alt="logo">
      </div>
    </header>

    <!-- ফুটার -->
    <footer>
        <div> Corporate Office: 187(3rd Floor), Green Road, Dhanmondi Dhaka-1205, Bangladesh. Cell: +88 01904400202, +88 01904400203</div>
       
        <div>E-mail: info.itechbd@yahoo.com  Web: www.itechbd.net</div>
    </footer>
 <!-- <div class="chiti-watermark" id="chitiImage">
        <img src="{{ public_path('chiti.jpg') }}" alt="চিতি">
    </div> -->


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
                            <td>{{ $index + 1 }}.</td>
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
        
        <div class="signature-line"></div>
        <p><strong>{{ $company['signatory_name'] ?? 'Engr. Shamsul Alam' }}</strong></p>
        <p>{{ $company['signatory_designation'] ?? 'Director (Technical)' }}</p>
        <p>For, <strong>{{ $company['name'] ?? 'Intelligent Technology' }}</strong></p>

        <div class="contact-info">
          @if (!empty($company['phone'] ?? null))<p>Cell: {{ $company['phone'] }}</p>@endif
          @if (!empty($company['email'] ?? null))<p>E-mail: {{ $company['email'] }}</p>@endif
          @if (!empty($company['website'] ?? null))<p>Web: {{ $company['website'] }}</p>@endif
        </div>
      </div>
      <div class="sil"> 
        <img src="{{ public_path('sil.png') }}" alt="logo">
      </div>
    </div>
   
    </div>

   <div style="position: relative; height: 100%; margin-top: 100px; page-break-before: always;">
        <div style="
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            width: 85%;
            max-width: 650px;
            text-align: center;
            z-index: -1;
            pointer-events: none;
        ">
            <img src="{{ public_path('chiti.jpg') }}" 
                 style="width: 100%;  " alt="চিতি">
        </div>
    </div>
</body>

</html>
