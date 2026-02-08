@extends('frontend.layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Fix Select2 arrow alignment and remove unwanted triangle when open */
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent !important;
            border-width: 0 !important;
        }

        /* Customize Select2 arrow (closed state) */
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent !important;
            border-style: solid;
            border-width: 0 !important;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }

        /* Make all inputs and selects have a clean black border */
        select,
        input {
            border-color: #000 !important;
            border-width: 1px;
            box-shadow: none !important;
        }

        /* Make labels black (you can remove this if using dark backgrounds) */
        label {
            color: #000 !important;
            font-weight: 500;
        }

        /* Customize Select2 box styling */
        .select2-container--default .select2-selection--single {
            background-color: #fff !important;
            border: 1px solid #000 !important;
            border-radius: 4px;
            height: 30px !important;
            display: flex;
            align-items: center;
            width: 100% !important;
            padding-left: 8px;
        }

        /* Align the Select2 text vertically */
        .select2-selection__rendered {
            line-height: 30px !important;
        }

        /* Fix the dropdown width to match input width */
        .select2-container {
            width: 100% !important;
        }
    </style>

    <form action="{{ route('sales.store') }}" method="post" target="_blank" onsubmit="reloadAfterSubmit()">
        @csrf
        <div class="content container-fluid pt-4">
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Page Header -->
                    <div class="page-header mb-3">
                        <div class="content-page-header mb-3">
                            <h6>Customer Info</h5>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    {{-- <div class="row">
                        <div class="col-md-12">

                            <div class="form-group-item mb-0 pb-0">
                                <h5 class="form-title d-none">Basic Details</h5>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control p-2" placeholder="Enter Name"
                                                value="{{ old('name') }}" required autocomplete="off">
                                        </div>
                                    </div>



                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control p-2" name="phone" id="phone"
                                                pattern="[0-9]{11}" maxlength="11" placeholder="Enter phone number"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="input-block mb-3">
                                            <label>Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control p-2" placeholder="Enter Address"
                                                id="address" name="address" value="{{ old('address') }}" required
                                                autocomplete="off">
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div> --}}

                    <!-- <div class="row mb-4">
                                        <div class="col-md-4"> -->
                    <div class="input-block mb-3">
                        <label class="form-label">Customer Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="client_type" id="newClient" value="new"
                                    checked>
                                <label class="form-check-label" for="newClient">New Customer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="client_type" id="existingClient"
                                    value="existing">
                                <label class="form-check-label" for="existingClient">Existing Customer</label>
                            </div>
                        </div>
                    </div>
                    <!-- </div>
                                    </div> -->

                    <!-- New Client Form -->
                    <div id="newClientForm">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="input-block mb-3">
                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="newClientName" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="input-block mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="phone" id="newClientPhone" required>
                                </div>
                            </div>
                            {{-- <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="input-block mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="client_email" id="newClientEmail"
                                        required>
                                </div>
                            </div> --}}
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="input-block mb-3">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="address" id="newClientAddress" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Client Form -->
                    <div id="existingClientForm" style="display: none;">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="input-block mb-3">
                                    <label class="form-label">Select Customer <span class="text-danger">*</span></label>
                                    <select name="existing_client_id" class="form-control" id="clientSelect">
                                        <option value="">Select Customer</option>
                                        @foreach ($existingClients as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->name }} - {{ $client->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card mb-0">
                <div class="card-body">
                    <!-- Page Header -->
                    <div class="page-header mb-3">
                        <div class="content-page-header mb-3">
                            <h6>Cart Info</h6>
                        </div>
                    </div>
                    <!-- /Page Header -->
                    <div class="row">
                        <div class="col-md-12">

                            {{-- <div class="group-item" data-itemnumber="1" id="form-group-item1"
                                style="background:#198754; color:#fff !important; padding: 10px 5px;">

                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <label style="color:#fff !important;">Product Name</label>
                                        <select onchange="selectProduct(1)" id="product1"
                                            class="form-control js-example-basic-single" style="height: 30px;" required>
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                data-name="{{ $product->name }}({{ $product->model }})"
                                                data-stock="{{ $product->inventory->current_stock ?? 0 }}"
                                                data-price="{{ $product->latestPurchase->unit_price ?? 0 }}"
                                                data-warranty="{{ $product->warranty ?? 0 }}">
                                                {{ $product->name }}({{ $product->model }}) </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label style="color:#fff !important;">Stock</label>
                                        <input type="number" id="stock1" style="height: 30px;" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label style="color:#fff !important;"> Warranty (Days)</label>
                                        <input type="number" id="warranty1" style="height: 30px;" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label style="color:#fff !important;"> Purchase Price</label>
                                        <input type="number" id="purchase_price1" style="height: 30px;" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label style="color:#fff !important;"> Unit Price</label>
                                        <input onchange="calculateTotal()" type="number" id="unit_price1"
                                            style="height: 30px;" class="form-control unit-price">
                                    </div>

                                    <div class="col-md-1">
                                        <label style="color:#fff !important;">Qty</label>
                                        <input onchange="calculateTotal()" type="number" id="qty1" style="height: 30px;"
                                            class="form-control qty" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label style="color:#fff !important;">Total</label>
                                        <input type="number" id="total1" style="height: 30px;" class="form-control total"
                                            readonly>
                                    </div>
                                    <div class="col-md-1 text-end btn-holder">
                                        <button onclick="addItem()" type="button"
                                            class=" btn btn-primary addItemBtn">Add</button>
                                    </div>
                                </div>

                            </div> --}}

                            <div class="product-form-item" id="form-group-item1">
                                <div class="item-header">
                                    <span class="item-number">Product</span>

                                </div>

                                <div class="form-grid">
                                    <!-- Product Selection -->
                                    <div class="form-field">
                                        <label for="product1" class="form-label">Product Name *</label>
                                        <select onchange="selectProduct(1)" id="product1" class="form-select" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-name="{{ $product->name }}({{ $product->model }})"
                                                    data-stock="{{ $product->inventory->current_stock ?? 0 }}"
                                                    data-price="{{ $product->latestPurchase->unit_price ?? 0 }}"
                                                    data-warranty="{{ $product->warranty ?? 0 }}">
                                                    {{ $product->name }} ({{ $product->model }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Product Info Row -->
                                    <div class="info-row">
                                        <div class="info-box">
                                            <label class="info-label">Available Stock</label>
                                            <input type="number" id="stock1" class="info-input" readonly>
                                        </div>

                                        <div class="info-box">
                                            <label class="info-label">Warranty (Days)</label>
                                            <input type="number" id="warranty1" class="info-input" readonly>
                                        </div>

                                        <div class="info-box">
                                            <label class="info-label">Purchase Price</label>
                                            <div class="price-display">
                                                <span class="currency-symbol"></span>
                                                <input type="number" id="purchase_price1" class="info-input" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Details Row -->
                                    <div class="order-row">
                                        <div class="input-field">
                                            <label for="unit_price1" class="form-label">Unit Price</label>
                                            <input onchange="calculateTotal()" type="number" id="unit_price1"
                                                class="form-input price-input" step="0.01" min="0" placeholder="0.00">
                                        </div>

                                        <div class="input-field">
                                            <label for="qty1" class="form-label">Quantity</label>
                                            <div class="quantity-box">
                                                <button type="button" class="qty-btn" onclick="adjustQty(1, -1)">-</button>
                                                <input onchange="calculateTotal()" type="number" id="qty1" class="qty-input"
                                                    min="0" value="0">
                                                <button type="button" class="qty-btn" onclick="adjustQty(1, 1)">+</button>
                                            </div>
                                        </div>

                                        <div class="input-field">
                                            <label class="form-label">Total</label>
                                            <div class="total-box">
                                                <span class="currency-symbol"></span>
                                                <input type="number" id="total1" class="total-input" readonly>
                                            </div>
                                        </div>

                                        <div class="button-field">
                                            <button type="button" onclick="addItem()" class="add-item-btn">Add
                                                Product</button>
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    .product-form-item {
                                        background: #f8f9fa;
                                        border: 1px solid #dee2e6;
                                        border-radius: 6px;
                                        margin-bottom: 15px;
                                        padding: 0;
                                        overflow: hidden;
                                    }

                                    .item-header {
                                        background: #198754;
                                        color: white;
                                        padding: 12px 15px;
                                        display: flex;
                                        justify-content: space-between;
                                        align-items: center;
                                    }

                                    .item-number {
                                        font-weight: 600;
                                        font-size: 14px;
                                    }

                                    .add-item-btn {
                                        background: rgba(255, 255, 255, 0.2);
                                        border: 1px solid rgba(255, 255, 255, 0.3);
                                        color: white;
                                        padding: 6px 12px;
                                        border-radius: 4px;
                                        cursor: pointer;
                                        font-size: 13px;
                                        transition: background 0.2s;
                                    }

                                    .add-item-btn:hover {
                                        background: rgba(255, 255, 255, 0.3);
                                    }

                                    .form-grid {
                                        padding: 15px;
                                    }

                                    .form-field {
                                        margin-bottom: 15px;
                                    }

                                    .form-label {
                                        display: block;
                                        font-weight: 500;
                                        color: #495057;
                                        margin-bottom: 5px;
                                        font-size: 13px;
                                    }

                                    .form-label[for]::after {
                                        content: '*';
                                        color: #dc3545;
                                        margin-left: 2px;
                                    }

                                    .form-select,
                                    .form-input {
                                        width: 100%;
                                        padding: 8px 12px;
                                        border: 1px solid #ced4da;
                                        border-radius: 4px;
                                        font-size: 14px;
                                        background: white;
                                        box-sizing: border-box;
                                    }

                                    .form-select:focus,
                                    .form-input:focus {
                                        outline: none;
                                        border-color: #198754;
                                        box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.1);
                                    }

                                    .info-row,
                                    .order-row {
                                        display: grid;
                                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                                        gap: 12px;
                                        margin: 15px 0;
                                    }

                                    .info-box {
                                        background: white;
                                        border: 1px solid #e9ecef;
                                        border-radius: 4px;
                                        padding: 10px;
                                    }

                                    .info-label {
                                        display: block;
                                        font-size: 12px;
                                        color: #6c757d;
                                        margin-bottom: 5px;
                                        font-weight: 500;
                                    }

                                    .info-input {
                                        width: 100%;
                                        border: none;
                                        background: transparent;
                                        font-size: 14px;
                                        color: #212529;
                                        padding: 0;
                                        font-weight: 600;
                                    }

                                    .info-input:focus {
                                        outline: none;
                                    }

                                    .price-display,
                                    .total-box {
                                        display: flex;
                                        align-items: center;
                                    }

                                    .currency-symbol {
                                        color: #6c757d;
                                        margin-right: 5px;
                                        font-size: 14px;
                                    }

                                    .input-field {
                                        display: flex;
                                        flex-direction: column;
                                    }

                                    .price-input {
                                        padding-left: 25px;
                                        background-position: left 8px center;
                                        background-size: 14px;
                                    }

                                    .quantity-box {
                                        display: flex;
                                        align-items: center;
                                        gap: 5px;
                                    }

                                    .qty-input {
                                        flex: 1;
                                        text-align: center;
                                        padding: 8px;
                                        border: 1px solid #ced4da;
                                        border-radius: 4px;
                                        font-size: 14px;
                                        width: 60px;
                                    }

                                    .qty-btn {
                                        width: 30px;
                                        height: 30px;
                                        border: 1px solid #ced4da;
                                        background: white;
                                        border-radius: 4px;
                                        cursor: pointer;
                                        font-size: 14px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        transition: all 0.2s;
                                    }

                                    .qty-btn:hover {
                                        background: #f8f9fa;
                                        border-color: #adb5bd;
                                    }

                                    .total-input {
                                        flex: 1;
                                        border: none;
                                        background: transparent;
                                        font-size: 16px;
                                        color: #198754;
                                        font-weight: 600;
                                        padding: 0;
                                    }

                                    /* Stock level colors */
                                    .info-input[value="0"] {
                                        color: #dc3545;
                                    }

                                    .info-input[value^="-"] {
                                        color: #dc3545;
                                    }

                                    /* Responsive Design */
                                    @media (max-width: 768px) {
                                        .item-header {
                                            flex-direction: column;
                                            gap: 8px;
                                            align-items: flex-start;
                                        }

                                        .add-item-btn {
                                            align-self: stretch;
                                            text-align: center;
                                        }

                                        .info-row,
                                        .order-row {
                                            grid-template-columns: 1fr;
                                        }

                                        .info-box {
                                            padding: 8px;
                                        }
                                    }

                                    @media (max-width: 576px) {
                                        .form-grid {
                                            padding: 12px;
                                        }

                                        .quantity-box {
                                            justify-content: flex-start;
                                        }

                                        .qty-input {
                                            width: 50px;
                                        }
                                    }

                                    /* Status messages */
                                    .status-message {
                                        padding: 8px 12px;
                                        margin-top: 10px;
                                        border-radius: 4px;
                                        font-size: 13px;
                                        display: none;
                                    }

                                    .status-message.info {
                                        background: #e7f6ff;
                                        border: 1px solid #b3e0ff;
                                        color: #0066cc;
                                        display: block;
                                    }

                                    .status-message.success {
                                        background: #d4edda;
                                        border: 1px solid #c3e6cb;
                                        color: #155724;
                                    }

                                    .status-message.warning {
                                        background: #fff3cd;
                                        border: 1px solid #ffeaa7;
                                        color: #856404;
                                    }

                                    .order-row {
                                        display: grid;
                                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                                        gap: 12px;
                                        margin: 15px 0;
                                        align-items: end;
                                    }

                                    .input-field {
                                        display: flex;
                                        flex-direction: column;
                                    }

                                    .button-field {
                                        display: flex;
                                        align-items: flex-end;
                                        justify-content: flex-start;
                                    }

                                    .add-item-btn {
                                        background: #198754;
                                        color: white;
                                        border: none;
                                        border-radius: 4px;
                                        padding: 10px 20px;
                                        font-size: 14px;
                                        cursor: pointer;
                                        transition: background 0.2s;
                                        white-space: nowrap;
                                        height: 42px;
                                        font-weight: 500;
                                        width: 100%;
                                    }

                                    .add-item-btn:hover {
                                        background: #157347;
                                    }

                                    /* Responsive adjustments */
                                    @media (max-width: 768px) {
                                        .order-row {
                                            grid-template-columns: 1fr;
                                            gap: 15px;
                                        }

                                        .button-field {
                                            margin-top: 5px;
                                        }

                                        .add-item-btn {
                                            height: 40px;
                                        }
                                    }

                                    @media (max-width: 576px) {
                                        .add-item-btn {
                                            padding: 8px 16px;
                                            font-size: 13px;
                                        }
                                    }

                                    /* Total box styling */
                                    .total-box {
                                        background: white;
                                        border: 1px solid #e9ecef;
                                        border-radius: 4px;
                                        padding: 8px 12px;
                                        display: flex;
                                        align-items: center;
                                    }

                                    .total-input {
                                        flex: 1;
                                        border: none;
                                        background: transparent;
                                        font-size: 16px;
                                        color: #198754;
                                        font-weight: 600;
                                        padding: 0;
                                        text-align: center;
                                    }

                                    .currency-symbol {
                                        color: #6c757d;
                                        margin-right: 5px;
                                        font-size: 14px;
                                        font-weight: 500;
                                    }

                                    /* Form input styling */
                                    .form-input {
                                        width: 100%;
                                        padding: 8px 12px;
                                        border: 1px solid #ced4da;
                                        border-radius: 4px;
                                        font-size: 14px;
                                        background: white;
                                        box-sizing: border-box;
                                    }

                                    .form-input:focus {
                                        outline: none;
                                        border-color: #198754;
                                        box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.1);
                                    }
                                </style>

                                <script>
                                    function adjustQty(itemNumber, change) {
                                        const qtyInput = document.getElementById(`qty${itemNumber}`);
                                        let currentQty = parseInt(qtyInput.value) || 0;
                                        const newQty = Math.max(0, currentQty + change);
                                        qtyInput.value = newQty;
                                        calculateTotal();
                                    }

                                    function selectProduct(itemNumber) {
                                        const select = document.getElementById(`product${itemNumber}`);
                                        const option = select.options[select.selectedIndex];

                                        if (option.value) {
                                            const stock = parseInt(option.getAttribute('data-stock')) || 0;
                                            document.getElementById(`stock${itemNumber}`).value = stock;
                                            document.getElementById(`warranty${itemNumber}`).value = option.getAttribute('data-warranty') || 0;
                                            document.getElementById(`purchase_price${itemNumber}`).value = option.getAttribute('data-price') || 0;

                                            // Update quantity max attribute based on stock
                                            document.getElementById(`qty${itemNumber}`).max = stock;

                                            // Auto-fill unit price with purchase price + markup (optional)
                                            const purchasePrice = parseFloat(option.getAttribute('data-price')) || 0;
                                            if (purchasePrice > 0) {
                                                // Add 20% markup by default
                                                const unitPrice = purchasePrice * 1.2;
                                                document.getElementById(`unit_price${itemNumber}`).value = unitPrice.toFixed(2);
                                                calculateTotal();
                                            }
                                        }
                                    }

                                    function calculateTotal() {
                                        const itemNumber = 1; // This would be dynamic in a multi-item setup
                                        const unitPrice = parseFloat(document.getElementById(`unit_price${itemNumber}`).value) || 0;
                                        const quantity = parseInt(document.getElementById(`qty${itemNumber}`).value) || 0;
                                        const total = unitPrice * quantity;
                                        document.getElementById(`total${itemNumber}`).value = total.toFixed(2);
                                    }
                                </script>


                                <hr>

                                <div class="" style="color:#000 !important;">
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label style="color:#000 !important;">Product Name</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label style="color:#000 !important;"> Unit Price</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label style="color:#000 !important;">Qty</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label style="color:#000 !important;">Total</label>
                                        </div>
                                        <div class="col-md-1 text-end btn-holder">
                                        </div>
                                    </div>
                                </div>
                                <div id="item_container">

                                </div>
                                <hr>

                                <br>
                                <div id="summerySection" class="row align-items-end mt-3 d-none" style="color:#000;">
                                    <div class="col-md-2">
                                        <label>Sub Total</label>
                                        <input onchange="calculateTotal()" type="number" id="subTotal" name="subTotal"
                                            class="form-control total" style="height: 30px;" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Discount</label>
                                        <input onchange="calculateTotal()" type="number" id="discount" name="discount"
                                            class="form-control total" style="height: 30px;">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Grand Total</label>
                                        <input type="number" id="grandTotal" name="grandTotal" class="form-control total"
                                            style="height: 30px;" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label>Current Payment</label>
                                        <input type="number" name="advanced_payment" id="advancedPayment"
                                            class="form-control total" style="height: 30px;" value="0" min="0" step="0.01">
                                    </div>

                                    <div class="col-md-2">
                                        <label>Due Payment</label>
                                        <input type="number" id="duePayment" name="duePayment" class="form-control total"
                                            style="height: 30px;" readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="add-customer-btns text-right">
                                            <button type="submit" class="btn customer-btn-save">Submit</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </form>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newClientRadio = document.getElementById('newClient');
            const existingClientRadio = document.getElementById('existingClient');
            const newClientForm = document.getElementById('newClientForm');
            const existingClientForm = document.getElementById('existingClientForm');

            // Get all new client input fields
            const newClientInputs = document.querySelectorAll('#newClientForm input, #newClientForm select');

            function toggleClientForms() {
                if (newClientRadio.checked) {
                    newClientForm.style.display = 'block';
                    existingClientForm.style.display = 'none';

                    // Make new client fields required
                    newClientInputs.forEach(input => {
                        input.required = true;
                    });

                    // Make existing client field not required
                    document.getElementById('clientSelect').required = false;
                } else {
                    newClientForm.style.display = 'none';
                    existingClientForm.style.display = 'block';

                    // Make new client fields not required
                    newClientInputs.forEach(input => {
                        input.required = false;
                    });

                    // Make existing client field required
                    document.getElementById('clientSelect').required = true;
                }
            }

            newClientRadio.addEventListener('change', toggleClientForms);
            existingClientRadio.addEventListener('change', toggleClientForms);

            // Initialize on page load
            toggleClientForms();
        });
    </script>

    <script>
        function reloadAfterSubmit() {
            setTimeout(function () {
                window.location.reload();
            }, 500); // give it some time to finish submission
        }
    </script>

    <script>
        var itemNumber = 2;

        $(document).ready(function () {
            $('.js-example-basic-single').select2({
                tags: true
            });


        });

        function addItem() {

            var product = document.getElementById('product1').value;
            var qty = document.getElementById('qty1').value;
            if (product == "") {
                document.getElementById('product1').setCustomValidity("Time is required");
                document.getElementById('product1').reportValidity();
                return;
            }

            let selectedName = document.getElementById('product1').options[document.getElementById('product1')
                .selectedIndex].text;

            const price = document.getElementById('unit_price1').value;
            if (price.trim() === "") {
                document.getElementById('unit_price1').setCustomValidity("Time is required");
                document.getElementById('unit_price1').reportValidity();
                return;
            }

            if (qty.trim() === "") {
                document.getElementById('qty1').setCustomValidity("Time is required");
                document.getElementById('qty1').reportValidity();
                return;
            }

            const stock = parseFloat(document.getElementById('stock1').value) || 0;
            if (parseFloat(qty) > stock) {
                alert("Quantity exceeds available stock!");
                return;
            }


            var eles = document.getElementsByClassName('item' + product);
            if (eles.length) {
                var qEles = document.getElementsByClassName('qty' + product);
                if (qEles.length) {
                    var old_qty = qEles[0].value;
                    qEles[0].value = parseInt(old_qty) + parseInt(qty);
                }

            } else {


                var html = `
                                    <div class="item${product} group-item mt-2" data-itemnumber="${itemNumber}" id="form-group-item${itemNumber}">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <input  type="hidden" name="product[]" value="${product}">
                                                <select onchange="selectProduct(${itemNumber})" style="height: 30px;"  id="product${itemNumber}" class="product${product} form-control product-select js-example-basic-single d-none" required disabled>
                                                    <option value=""></option>
                                                    @foreach ($products as $product)`;

                                                        var select = (product == {{ $product->id }} ? 'selected' : '');

                                                        html += `
                                                                                                                                                                                                                        <option value="{{ $product->id }}" data-price="{{ $product->latestPurchase->unit_price ?? 0 }}" ${select}>
                                                                                                                                                                                                                            {{ $product->name }}({{ $product->model }})
                                                                                                                                                                                                                        </option>
                                                    @endforeach
                                                </select>
                                                <p>${selectedName}</p>
                                            </div>
                                            <div class="col-md-2">
                                                <input onchange="calculateTotal()" type="number" name="unit_price[]" id="unit_price${itemNumber}" style="height: 30px;" class="form-control unit-price" value="${price}" >
                                            </div>
                                            <div class="col-md-2">
                                                <input onchange="calculateTotal()" type="number" name="qty[]" id="qty${itemNumber}" style="height: 30px;" class="qty${product} form-control qty" min="0" value="${qty}">
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" name="total" id="total${itemNumber}" style="height: 30px;" class="form-control total" readonly>
                                            </div>
                                            <div class="col-md-2 text-end btn-holder">
                                                <button onclick="removeItem(${itemNumber})" type="button" class="btn btn-danger remove-item me-1 ">×</button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                $('#item_container').append(html);

                itemNumber++;
            }

            calculateTotal();

        }

        function removeItem(item) {
            document.getElementById('form-group-item' + item).remove();
            calculateTotal();
        }

        // function selectProduct(item) {

        //     var selectedPrice = $('#product' + item + ' option:selected').data('price');
        //     var selectedWarranty = $('#product' + item + ' option:selected').data('warranty');
        //     if (document.getElementById('purchase_price' + item))
        //         document.getElementById('purchase_price' + item).value = selectedPrice;
        //     if (document.getElementById('warranty' + item))
        //         document.getElementById('warranty' + item).value = selectedWarranty;
        //     calculateTotal();
        // }

        function selectProduct(item) {
            var selected = $('#product' + item + ' option:selected');
            var selectedPrice = selected.data('price') || 0;
            var selectedWarranty = selected.data('warranty') || 0;
            var selectedStock = selected.data('stock') || 0;

            if (document.getElementById('purchase_price' + item))
                document.getElementById('purchase_price' + item).value = selectedPrice;
            if (document.getElementById('warranty' + item))
                document.getElementById('warranty' + item).value = selectedWarranty;
            if (document.getElementById('stock' + item))
                document.getElementById('stock' + item).value = selectedStock;

            calculateTotal();
        }


        function calculateTotal() {
            let subTotal = 0;

            const eles = document.getElementsByClassName('group-item');
            for (let i = 0; i < eles.length; i++) {
                const itemNumber = eles[i].dataset.itemnumber;

                if (itemNumber == 1) continue;

                const unit_price = parseFloat(document.getElementById('unit_price' + itemNumber)?.value) || 0;
                const qty = parseFloat(document.getElementById('qty' + itemNumber)?.value) || 0;
                const totalEle = document.getElementById('total' + itemNumber);

                const total = qty * unit_price;
                if (totalEle) totalEle.value = total.toFixed(2);

                subTotal += total;
            }

            const previewUnitPrice = parseFloat(document.getElementById('unit_price1')?.value) || 0;
            const previewQty = parseFloat(document.getElementById('qty1')?.value) || 0;
            const previewTotal = previewUnitPrice * previewQty;
            const previewTotalInput = document.getElementById('total1');
            if (previewTotalInput) previewTotalInput.value = previewTotal.toFixed(2);

            let discount = parseFloat(document.getElementById('discount').value) || 0;
            if (discount > subTotal) discount = subTotal;
            document.getElementById('discount').value = discount.toFixed(2);

            const grandTotal = subTotal - discount;
            document.getElementById('subTotal').value = subTotal.toFixed(2);
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);

            const advanced = parseFloat(document.getElementById('advancedPayment').value) || 0;
            const due = grandTotal - advanced;
            document.getElementById('duePayment').value = due.toFixed(2);

            const hasItems = document.querySelectorAll('.group-item[data-itemnumber]:not([data-itemnumber="1"])').length >
                0;
            document.getElementById("summerySection").classList.toggle('d-none', !hasItems);
        }


        // Optional: recalc Due Payment whenever Advanced Payment changes
        document.getElementById('advancedPayment').addEventListener('input', calculateTotal);
    </script>
@endsection