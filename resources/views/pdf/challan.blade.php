 <!DOCTYPE html>
 <html>

 <head>
     <meta charset="utf-8" />
     <link rel="preconnect" href="https://fonts.googleapis.com" />
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
     <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
         rel="stylesheet" />
     <title>challan {{ $challan->reference_number }}</title>
     <style>
         body {
             font-family: "Montserrat", sans-serif;
             font-size: 12px;
             line-height: 1.3;
             color: #000;
             margin: 0;
             padding: 20px;

             background: rgba(255, 255, 255, 0.2) url("{{ public_path('big-logo.png') }}") no-repeat 310px -10px / 340px auto fixed transparent !important;

         }

         @page {
             margin: 160px 45px 100px 45px;
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
             display: block;
             height: 100px;
             background: transparent;
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
             text-align: center;
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

             /* margin-top: 30px; */
             margin-bottom: 20px;
             font-weight: bold;
             position: relative;
         }

         .reference .ref-date {
             position: absolute;
             top: 0;
             right: 0;
         }





         .to-section {
             margin-bottom: 20px;
         }

         .to-section p {
             margin: 3px 0;
         }

         .work-order {
             margin: 15px 0;
             font-weight: bold;
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

         .challan-title {
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
             /* background-color: #f9f9f9; */
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
             margin: 10px 0;
             padding: 15px;
             /* background-color: #f5f5f5; */
             border-left: 2px solid #333;
         }

         .bank-details strong {
             display: inline-block;
             width: 180px;
         }

         .closing {
             margin: 40px 0 0 0px;
             text-align: justify;
         }

         .signature-section {
             width: 100%;
             margin-top: 60px;
             position: relative;
         }

         .signature-content {
             float: left;
             text-align: left;
             width: 150px;
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

         .brand-details {
             margin-bottom: 3px;
         }

         .product-specs {
             margin-left: 10px;
         }

         .sil {
             max-width: 120px;
         }

         .sil img {
             width: 100%;
         }

         .signature-section table {
             border-collapse: separate;
             border-spacing: 30px 0;
         }

         .signature-section td {
             padding-bottom: 20px;
         }
     </style>
 </head>

 <body>
     <header>
         <div class="logo">
             <img src="{{ public_path('logo.jpg') }}" alt="logo">
         </div>
     </header>

     <footer>
         <div> Corporate Office: 187(3rd Floor), Green Road, Dhanmondi Dhaka-1205, Bangladesh. Cell: +88 01904400202,
             +88 01904400203</div>

         <div>E-mail: info.itechbd@yahoo.com Web: www.itechbd.net</div>
     </footer>




     <div class="container">

         <div class="reference">
             <div>Ref: {{ $challan->reference_number }}</div>
             <div class="ref-date">{{ $challan->challan_date->format('F d, Y') }}</div>
         </div>

         <div class="to-section">
             <p>To,</p>
             <p>{{ $recipient_designation ?? 'Director (IT)' }}</p>
             <p>{{ $recipient_organization ?? ($challan->client_name ?? 'N/A') }}</p>
             <p>{{ $recipient_address ?? ($challan->client_address ?? 'N/A') }}</p>
             <br>
             @if (!empty($attention_to))
                 <p>Attention: {{ $attention_to }}</p>
             @endif
         </div>

         @if ($challan->work_order_number)
             <div class="work-order">
                 Work order # {{ $challan->work_order_number }}
             </div>
         @endif



         <div class="challan-title">CHALLAN</div>

         <table>
             <thead>
                 <tr>
                     <th>S/L</th>
                     <th>PRODUCT DESCRIPTION</th>
                     <th>QTY.</th>
                     {{-- <th>UNIT PRICE</th>
                     <th>TOTAL PRICE</th> --}}
                 </tr>
             </thead>
             <tbody>
                 @foreach ($challan->challanItems as $index => $item)
                     <tr>
                         <td>{{ $loop->iteration }}</td>
                         <td>
                             <div class="product-specs">
                                 {!! nl2br(e($item->description)) !!}
                             </div>
                         </td>
                         <td>{{ number_format($item->quantity) }} {{ $item->unit ?? 'No' }}</td>
                         {{-- <td>{{ number_format($item->unit_price, 2) }}</td>
                         <td>{{ number_format($item->total, 2) }}</td> --}}
                     </tr>
                 @endforeach


             </tbody>
         </table>



         <div class="closing">
             <p> We assure you that we provide our best service at all times.</p>
             <p>Thank you once again.</p>
             <br>
             <p>Yours Sincerely,</p>
         </div>

         <div class="signature-section" style="display: table; width: 100%; margin-top: 80px;">
             <div style="display: table-cell; width: 33%; text-align: center; vertical-align: bottom;">
                 <div style="border-top: 1px dashed #000; width: 180px; margin: 0 auto 8px;"></div>
                 <p style="margin:0;">For,<strong>Intelligent Technology</strong></p>
             </div>
             <div style="display: table-cell; width: 33%; text-align: center; vertical-align: bottom;">
                 <img src="{{ public_path('sil.png') }}" style="max-height: 100px;">
             </div>
             <div style="display: table-cell; width: 33%; text-align: center; vertical-align: bottom;">
                 <div style="border-top: 1px dashed #000; width: 180px; margin: 0 auto 8px;"></div>
                 <p style="margin:0;"><strong> Customer’s Signature</strong></p>
             </div>
         </div>

     </div>

 </body>

 </html>
