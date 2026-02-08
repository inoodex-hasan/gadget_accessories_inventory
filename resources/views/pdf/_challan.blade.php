<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Challan {{ $challan->reference_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
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

        .attention {
            margin: 10px 0;
        }

        .challan-title {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
        }

        .serial {
            width: 10%;
            text-align: center;
        }

        .description {
            width: 70%;
        }

        .quantity {
            width: 20%;
            text-align: center;
        }

        .closing {
            margin: 20px 0;
        }

        /* Simple signature section */
        .signature-wrapper {
            margin-top: 100px;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .company-area {
            width: 45%;
        }

        .customer-area {
            width: 45%;
            text-align: right;
        }

        /* .signature-line {
            border-top: 1px dashed #000;
            width: 200px;
            margin: 40px 0 10px 0;
        }

        .customer-line {
            border-top: 1px dashed #000;
            width: 200px;
            margin: 40px 0 10px auto;
        } */
    </style>
</head>

<body>
    <div class="container">
        <!-- Reference and Date -->
        <div class="reference">
            <span style="float: left;">Ref: {{ $challan->reference_number }}</span>
            <span style="float: right;">{{ $challan->challan_date->format('F d, Y') }}</span>
            <div style="clear: both;"></div>
        </div>

        <!-- Recipient Details -->
        <div class="to-section">
            <p>To,</p>
            <p>{{ $recipient_designation }},</p>
            <p>{{ $recipient_organization }},</p>
            <p>{{ $recipient_address }}.</p>
            @if ($attention_to)
                <div class="attention">
                    Attention: {{ $attention_to }}
                </div>
            @endif
        </div>

        <!-- Challan Title -->
        <div class="challan-title">CHALLAN</div>
        <div class="subject" style="text-align: center; margin-bottom: 20px;">
            {{ $challan->subject }}
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th class="serial">S/L</th>
                    <th class="description">PRODUCT DESCRIPTION</th>
                    <th class="quantity">QTY.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($challan->challanItems as $index => $item)
                    <tr>
                        <td class="serial">{{ $index + 1 }}.</td>
                        <td class="description">{!! nl2br(e($item->description)) !!}</td>
                        <td class="quantity">{{ number_format($item->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Closing Notes -->
        @if ($notes)
            <div class="closing">
                {!! nl2br(e($notes)) !!}
            </div>
        @endif

        <!-- Replace your entire signature section with this -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 100px;">

            <!-- Company Signature - Left -->
            <div style="width: 45%;">
                <p>We assure you that we provide our best service at all times.</p>
                <p>Thank you once again.</p>
                <p>Yours Sincerely,</p>
                <div style="border-top: 1px solid #000; width: 250px; margin-top: 40px; margin-bottom: 10px;"></div>
                <p><strong>{{ $signatory_name }}</strong></p>
                <p>{{ $signatory_designation }}</p>
                <p>For, <strong>{{ $company_name }}</strong></p>
            </div>

            <!-- Customer Signature - Right -->
            <div style="width: 45%;">
                <div
                    style="border-top: 1px dashed #000; width: 250px; margin-left: auto; margin-top: 40px; margin-bottom: 10px;">
                </div>
                <p style="text-align: right;">Customer's Signature</p>
            </div>
        </div>
    </div>
</body>

</html>
