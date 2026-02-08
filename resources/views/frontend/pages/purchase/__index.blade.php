@extends('frontend.layouts.app')
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="content-page-header mt-5">
                <h5>Purchase List</h5>
                <div class="list-btn">
                    <ul class="filter-list">
                        <li>
                            <a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal"
                                data-bs-target="#add-purchase-modal">
                                <i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Purchase</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="add-purchase-modal" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="add-purchase-modal">Add Purchase</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form class="px-3" method="post" action="{{ route('purchase.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select class="form-control select2" name="product_id" id="product_id" required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }}({{ $product->model ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">Last Unit Price</label>
                                    <input type="number" step="0.01" name="unit_price" id="unit_price"
                                        class="form-control" required />
                                </div>

                                <div class="mb-3">
                                    <label for="sub_price" class="form-label">Sub Price</label>
                                    <input type="number" step="0.01" name="sub_price" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="total_price" class="form-label">Paybale Total Price</label>
                                    <input type="number" step="0.01" name="total_price" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="payment" class="form-label">Payment</label>
                                    <input type="number" step="0.01" name="payment" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="due" class="form-label">Due</label>
                                    <input type="number" step="0.01" name="due" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Vendor</label>
                                    <select id="vendor" name="vendor_id" class="form-control" required>
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">
                                                {{ $vendor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <!-- Search Filter -->
        <div class="row">
            <div class="card-table">
                <div class="card-body">
                    <div class="table-fluid">
                        <table class="table table-center table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Vendor</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Payment</th>
                                    <th>Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchase->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $purchase->product->name ?? 'N/A' }}({{ $purchase->product->model ?? 'N/A' }})
                                        </td>
                                        <td>{{ $purchase->vendor->name ?? 'N/A' }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                        <td>{{ $purchase->unit_price }}</td>
                                        <td>{{ $purchase->total_price }}</td>
                                        <td>{{ $purchase->payment }}</td>
                                        <td>{{ $purchase->due }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" class="btn-action-icon" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#edit-purchase-{{ $purchase->id }}">Edit</a>
                                                    <form method="POST"
                                                        action="{{ route('purchase.destroy', $purchase->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div id="edit-purchase-{{ $purchase->id }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="editModalLabel-{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel-{{ $purchase->id }}">
                                                        Edit Purchase</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <form method="POST"
                                                        action="{{ route('purchase.update', $purchase->id) }}">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                            <label for="edit-product_id-{{ $purchase->id }}"
                                                                class="form-label">Product</label>
                                                            <select id="edit-product_id-{{ $purchase->id }}"
                                                                name="product_id" class="form-select select2" required>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        {{ $product->id == $purchase->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }}
                                                                        ({{ $product->model ?? 'N/A' }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-quantity-{{ $purchase->id }}"
                                                                class="form-label">Quantity</label>
                                                            <input id="edit-quantity-{{ $purchase->id }}" name="quantity"
                                                                value="{{ $purchase->quantity }}" class="form-control"
                                                                placeholder="Quantity" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-unit_price-{{ $purchase->id }}"
                                                                class="form-label">Last Unit Price</label>
                                                            <input id="edit-unit_price-{{ $purchase->id }}"
                                                                name="unit_price" value="{{ $purchase->unit_price }}"
                                                                class="form-control" placeholder="Unit Price" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-sub_price-{{ $purchase->id }}"
                                                                class="form-label">Sub Price</label>
                                                            <input id="edit-sub_price-{{ $purchase->id }}"
                                                                name="sub_price" value="{{ $purchase->sub_price }}"
                                                                class="form-control" placeholder="Sub Price" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-total_price-{{ $purchase->id }}"
                                                                class="form-label">Paybale Total Price</label>
                                                            <input id="edit-total_price-{{ $purchase->id }}"
                                                                name="total_price" value="{{ $purchase->total_price }}"
                                                                class="form-control" placeholder="Total Price" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-payment-{{ $purchase->id }}"
                                                                class="form-label">Payment</label>
                                                            <input id="edit-payment-{{ $purchase->id }}" name="payment"
                                                                value="{{ $purchase->payment }}" class="form-control"
                                                                placeholder="Payment" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="edit-due-{{ $purchase->id }}"
                                                                class="form-label">Due</label>
                                                            <input id="edit-due-{{ $purchase->id }}" name="due"
                                                                value="{{ $purchase->due }}" class="form-control"
                                                                placeholder="Due" />
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="vendor" class="form-label">Vendor</label>
                                                            <select id="vendor" name="vendor_id" class="form-select"
                                                                required>
                                                                <option value="">Select Vendor</option>
                                                                @foreach ($vendors as $vendor)
                                                                    <option value="{{ $vendor->id }}"
                                                                        {{ $vendor->id == $purchase->vendor_id ? 'selected' : '' }}>
                                                                        {{ $vendor->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>

                                                        </div>

                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.querySelector('input[name="quantity"]');
            const unitPriceInput = document.querySelector('input[name="unit_price"]');
            const subPriceInput = document.querySelector('input[name="sub_price"]');
            const totalPriceInput = document.querySelector('input[name="total_price"]');
            const paymentInput = document.querySelector('input[name="payment"]');
            const dueInput = document.querySelector('input[name="due"]');

            function calculateSubPrice() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                subPriceInput.value = (quantity * unitPrice).toFixed(2);
            }

            function calculateDue() {
                const total = parseFloat(totalPriceInput.value) || 0;
                const payment = parseFloat(paymentInput.value) || 0;
                dueInput.value = (total - payment).toFixed(2);
            }

            quantityInput.addEventListener('input', calculateSubPrice);
            unitPriceInput.addEventListener('input', calculateSubPrice);
            totalPriceInput.addEventListener('input', calculateDue);
            paymentInput.addEventListener('input', calculateDue);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($purchases as $purchase)
                const quantityInput_{{ $purchase->id }} = document.getElementById(
                    'edit-quantity-{{ $purchase->id }}');
                const unitPriceInput_{{ $purchase->id }} = document.getElementById(
                    'edit-unit_price-{{ $purchase->id }}');
                const subPriceInput_{{ $purchase->id }} = document.getElementById(
                    'edit-sub_price-{{ $purchase->id }}');
                const totalPriceInput_{{ $purchase->id }} = document.getElementById(
                    'edit-total_price-{{ $purchase->id }}');
                const paymentInput_{{ $purchase->id }} = document.getElementById(
                    'edit-payment-{{ $purchase->id }}');
                const dueInput_{{ $purchase->id }} = document.getElementById('edit-due-{{ $purchase->id }}');

                function calculateSubPrice_{{ $purchase->id }}() {
                    const quantity = parseFloat(quantityInput_{{ $purchase->id }}.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput_{{ $purchase->id }}.value) || 0;
                    subPriceInput_{{ $purchase->id }}.value = (quantity * unitPrice).toFixed(2);
                }

                function calculateDue_{{ $purchase->id }}() {
                    const total = parseFloat(totalPriceInput_{{ $purchase->id }}.value) || 0;
                    const payment = parseFloat(paymentInput_{{ $purchase->id }}.value) || 0;
                    dueInput_{{ $purchase->id }}.value = (total - payment).toFixed(2);
                }

                quantityInput_{{ $purchase->id }}.addEventListener('input',
                    calculateSubPrice_{{ $purchase->id }});
                unitPriceInput_{{ $purchase->id }}.addEventListener('input',
                    calculateSubPrice_{{ $purchase->id }});
                totalPriceInput_{{ $purchase->id }}.addEventListener('input', calculateDue_{{ $purchase->id }});
                paymentInput_{{ $purchase->id }}.addEventListener('input', calculateDue_{{ $purchase->id }});
            @endforeach
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#product_id').on('change', function() {
                var productId = $(this).val();

                if (productId) {
                    var url = '{{ route('purchase.latest_price', ':id') }}';
                    url = url.replace(':id', productId);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            console.log(response);
                            $('#unit_price').val(response.price);
                        },
                        error: function() {
                            $('#unit_price').val(0);
                        }
                    });
                } else {
                    $('#unit_price').val(0);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('select[id^="edit-product_id-"]').on('change', function() {
                let selectId = $(this).attr('id'); // e.g., edit-product_id-5
                let purchaseId = selectId.split('-')[2]; // get 5

                let productId = $(this).val();

                if (productId) {
                    let url = '{{ route('purchase.latest_price', ':id') }}';
                    url = url.replace(':id', productId);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            $('#edit-unit_price-' + purchaseId).val(response.price);
                        },
                        error: function() {
                            $('#edit-unit_price-' + purchaseId).val(0);
                        }
                    });
                } else {
                    $('#edit-unit_price-' + purchaseId).val(0);
                }
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                tags: true
            });


        });
    </script>
@endsection
