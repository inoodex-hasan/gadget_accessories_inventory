@extends('frontend.layouts.app')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #888 transparent;
            border-width: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
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
    </style>

    <div class="content container-fluid">
        <div class="page-header">
            <div class="content-page-header">
                <h5>Inventories</h5>
                <div class="list-btn">


                    <!-- Add Product Modal -->
                    <div id="add-product-modal" class="modal fade" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="add-purchase-modal">Add Opening Stock</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <form method="post" action="{{ route('inventory.store') }}" class="px-3">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="product_id" class="form-label">Select Product <span
                                                    class="text-danger">*</span></label>
                                            <select name="product_id" id="product_id" class="form-select" required>
                                                <option value="" disabled selected>-- Select Product --</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->name }} ({{ $product->model ?? 'N/A' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="opening_stock" class="form-label">Opening Stock <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="opening_stock" id="opening_stock"
                                                class="form-control" min="0" value="0" required>
                                        </div>


                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Add Modal -->

                </div>
            </div>
        </div>

        <!-- Product Table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card-table">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="productTable" class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Model</th>
                                        <th>Photos</th>
                                        <th>Opening Stock</th>
                                        <th>Current Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventories as $inventory)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ Str::limit($inventory->product->name ?? 'N/A', 15) }}
                                            </td>
                                            <td>
                                                ({{ Str::limit($inventory->product->model ?? 'N/A', 15) }})
                                            </td>
                                            <td>
                                                @if (isset($inventory->product->photos) && count($inventory->product->photos) > 0)
                                                    <div class="d-flex gap-1 align-items-center">
                                                        @foreach ($inventory->product->photos as $index => $photo)
                                                            @if ($index < 2)
                                                                <img src="{{ asset($photo) }}" alt="Product Photo"
                                                                    style="height: 60px; width: 60px; object-fit: cover;"
                                                                    class="img-thumbnail rounded">
                                                            @endif
                                                        @endforeach

                                                        @if (count($inventory->product->photos) > 2)
                                                            <span
                                                                class="badge bg-secondary ms-1">+{{ count($inventory->product->photos) - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No photos</span>
                                                @endif
                                            </td>
                                            <td>{{ $inventory->opening_stock ?? 0 }}</td>
                                            <td>{{ $inventory->current_stock ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
