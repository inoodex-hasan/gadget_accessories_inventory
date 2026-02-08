@extends('frontend.layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            width: 55% !important;
        }

        select,
        input {
            border-color: #000 !important;
        }

        label {
            color: #000 !important;
        }

        @media (min-width: 768px) {
            .col-md-2 {
                width: 13% !important;
                padding: 0 5px;
            }
        }
    </style>

    <form action="{{ route('sales.update', $sales->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="content container-fluid pt-0">

            {{-- Customer Info --}}
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Customer Info</h6>
                    <div class="row">
                        <div class="col-lg-4">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control p-2" value="{{ $customer->name }}"
                                required>
                        </div>
                        <div class="col-lg-4">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control p-2" value="{{ $customer->phone }}"
                                required>
                        </div>
                        <div class="col-lg-4">
                            <label>Address <span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control p-2" value="{{ $customer->address }}"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cart Info --}}
            <div class="card mb-0">
                <div class="card-body">
                    <h6>Cart Info</h6>

                    <div class="row mb-2">
                        <div class="col-md-3"><label>Product Name</label></div>
                        <div class="col-md-2"><label>Unit Price</label></div>
                        <div class="col-md-2"><label>Qty</label></div>
                        <div class="col-md-2"><label>Total</label></div>
                        <div class="col-md-3 text-end"><label>Actions</label></div>
                    </div>

                    <div id="item_container">
                        @foreach ($items as $index => $item)
                            <div class="group-item item{{ $item->product_id }} mt-2" data-itemnumber="{{ $index + 1 }}"
                                id="form-group-item{{ $index + 1 }}">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <input type="hidden" name="product[]" value="{{ $item->product_id }}">
                                        <select class="form-control d-none" id="product{{ $index + 1 }}" disabled>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-price="{{ $product->latestPurchase->unit_price ?? 0 }}"
                                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}({{ $product->model }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p>
                                            @foreach ($products as $product)
                                                @if ($product->id == $item->product_id)
                                                    {{ $product->name }}({{ $product->model }})
                                                @endif
                                            @endforeach
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="unit_price[]" class="form-control unit-price"
                                            id="unit_price{{ $index + 1 }}" value="{{ $item->unit_price }}"
                                            onchange="calculateTotal()">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="qty[]"
                                            class="form-control qty qty{{ $item->product_id }}"
                                            id="qty{{ $index + 1 }}" value="{{ $item->qty }}" min="1"
                                            onchange="calculateTotal()">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="total[]" class="form-control total"
                                            id="total{{ $index + 1 }}" value="{{ $item->total_price }}" readonly>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <button type="button" class="btn btn-danger remove-item"
                                            onclick="removeItem({{ $index + 1 }})">×</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- <button type="button" class="btn btn-primary mt-2" onclick="addItem()">Add Product</button> --}}

                    <hr>

                    {{-- Summary Section --}}
                    <div id="summerySection" class="row justify-content-end align-items-end mt-3">
                        <div class="col-md-2">
                            <label>Sub Total</label>
                            <input type="number" id="subTotal" class="form-control" name="subTotal"
                                value="{{ $sales->bill }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label>Discount</label>
                            <input type="number" id="discount" class="form-control" name="discount"
                                value="{{ $sales->discount }}" step="any" onchange="calculateTotal()">

                        </div>

                        <div class="col-md-2">
                            <label>Grand Total</label>
                            <input type="number" id="grandTotal" class="form-control" name="grandTotal"
                                value="{{ $sales->payble }}" readonly>
                        </div>
                        <div class="col-md-2">
                            <label>Advanced Payment</label>
                            <input type="number" id="advancedPayment" class="form-control" name="advanced_payment"
                                value="{{ $sales->advanced_payment }}" min="0" step="0.01">
                        </div>
                        <div class="col-md-2">
                            <label>Due Payment</label>
                            <input type="number" id="duePayment" class="form-control" name="due_payment"
                                value="{{ $sales->due_payment }}" readonly>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Update Sale</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var itemNumber = {{ count($items) + 1 }};

        function addItem() {
            var html = `
    <div class="group-item mt-2" data-itemnumber="${itemNumber}" id="form-group-item${itemNumber}">
        <div class="row align-items-end">
            <div class="col-md-3">
                <input type="hidden" name="product[]" value="">
                <select class="form-control d-none" id="product${itemNumber}" disabled></select>
                <p>New Product</p>
            </div>
            <div class="col-md-2">
                <input type="number" name="unit_price[]" class="form-control unit-price" id="unit_price${itemNumber}" onchange="calculateTotal()">
            </div>
            <div class="col-md-2">
                <input type="number" name="qty[]" class="form-control qty" id="qty${itemNumber}" min="1" onchange="calculateTotal()">
            </div>
            <div class="col-md-2">
                <input type="number" name="total[]" class="form-control total" id="total${itemNumber}" readonly>
            </div>
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-danger remove-item" onclick="removeItem(${itemNumber})">×</button>
            </div>
        </div>
    </div>
    `;
            $('#item_container').append(html);
            itemNumber++;
        }

        function removeItem(num) {
            $('#form-group-item' + num).remove();
            calculateTotal();
        }

        function formatNumber(num) {
            if (num % 1 === 0) {
                return num; // integer, no decimals
            } else {
                return num.toFixed(2); // only show decimals if needed
            }
        }

        function calculateTotal() {
            var eles = document.getElementsByClassName('group-item');
            var subTotal = 0;

            for (var i = 0; i < eles.length; i++) {
                var itemNumber = eles[i].dataset.itemnumber;
                var unit_price = parseFloat(document.getElementById('unit_price' + itemNumber).value) || 0;
                var qty = parseFloat(document.getElementById('qty' + itemNumber).value) || 0;
                var totalEle = document.getElementById('total' + itemNumber);

                var total = qty * unit_price;
                totalEle.value = formatNumber(total);

                subTotal += total;
            }

            var discount = parseFloat(document.getElementById('discount').value) || 0;
            if (discount > subTotal) discount = subTotal;
            document.getElementById('discount').value = formatNumber(discount);

            var grandTotal = subTotal - discount;
            document.getElementById('subTotal').value = formatNumber(subTotal);
            document.getElementById('grandTotal').value = formatNumber(grandTotal);

            var advanced = parseFloat(document.getElementById('advancedPayment').value) || 0;
            var due = grandTotal - advanced;
            document.getElementById('duePayment').value = formatNumber(due);
        }


        // Recalculate due when advanced payment changes
        $('#advancedPayment, #discount, .unit-price, .qty').on('input', calculateTotal);

        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                tags: true
            });
        });
    </script>
@endsection
