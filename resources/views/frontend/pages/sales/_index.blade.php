@extends('frontend.layouts.app')
@section('content')
    <style>
        /* Default: Columns stack vertically */
        .custom-col-xl-2 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Media Query for XL screens (≥1200px) */
        @media (min-width: 1200px) {
            .custom-col-xl-2 {
                flex: 0 0 20%;
                /* Equivalent to col-xl-2 (2/12 = 16.67%) */
                max-width: 20%;
            }
        }

        .page-wrapper .content {
            padding: 14px !important;
        }
    </style>
    <div class="content container-fluid">


        <!-- Page Header -->
        <div class="page-header">

            <form action="{{ route('sales.index') }}" method="get">
                <div class="row">
                    <div class="col-12 col-md-2">
                        <label for="">From</label>
                        <input type="date" name="from" class="form-control"
                            value="{{ isset($request) ? $request->from : '' }}">
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="">To</label><br>
                        <input type="date" name="to" class="form-control"
                            value="{{ isset($request) ? $request->to : '' }}">
                    </div>

                    <div class="col-12 col-md-2">
                        <label for="">Search By</label><br>
                        <select name="search_by" id="" class="form-select">
                            <option value="">--Select--</option>
                            <option value="order_no"
                                {{ isset($request) && $request->search_by == 'order_no' ? 'selected' : '' }}>Order No
                            </option>
                            <option value="name" {{ isset($request) && $request->search_by == 'name' ? 'selected' : '' }}>
                                Name</option>
                            <option value="phone"
                                {{ isset($request) && $request->search_by == 'phone' ? 'selected' : '' }}>Phone</option>
                            <option value="email"
                                {{ isset($request) && $request->search_by == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="product_name"
                                {{ isset($request) && $request->search_by == 'product_name' ? 'selected' : '' }}>Product
                                Name</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="">Search Key</label><br>
                        <input type="text" name="key" class="form-control"
                            value="{{ isset($request) ? $request->key : '' }}">
                    </div>
                    <div class="col-12 col-md-2">
                        <label for=""></label>
                        <button type="submit" name="search_for" value="filter" class="btn btn-primary"
                            style="margin-top:25px;">Search</button>
                        <label for=""></label>
                        <button type="submit" name="search_for" value="pdf" class="btn btn-primary"
                            style="margin-top:25px;"><i class="fe fe-download"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /Page Header -->
        <!-- Search Filter -->
        <div id="filter_inputs" class="card filter-card">
            <div class="card-body pb-0">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="input-block mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="input-block mb-3">
                            <label>Email</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="input-block mb-3">
                            <label>Phone</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Search Filter -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card-table">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                <table class="table table-center table-hover no-footer" id="DataTables_Table_0"
                                    role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead class="thead-light">
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="#: activate to sort column descending">#</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1"
                                                aria-label="Name: activate to sort column ascending">Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1"
                                                aria-label="Name: activate to sort column ascending">Order No</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1"
                                                aria-label="Name: activate to sort column ascending">Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1"
                                                aria-label="Phone: activate to sort column ascending">Phone</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1"
                                                aria-label="Phone: activate to sort column ascending">Payble</th>
                                            <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                rowspan="1" colspan="1"
                                                aria-label="Status: activate to sort column ascending">Sales By</th>
                                            <th class="no-sort sorting_disabled" rowspan="1" colspan="1"
                                                aria-label="Actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $service)
                                            <tr role="row" class="odd">
                                                <td class="sorting_1">{{ $loop->index + 1 }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <span>{{ $service->created_at->format('Y-m-d') }}</span>
                                                    </h2>
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar"> <span>{{ $service->order_no }}</span></h2>
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="profile.html" class="avatar avatar-md me-2 d-none">
                                                            <img class="avatar-img rounded-circle"
                                                                src="assets/img/profiles/avatar-14.jpg" alt="User Image">
                                                        </a>
                                                        <a href="javascript:void(0)">{{ $service->name }}
                                                        </a>
                                                    </h2>
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar"> <span>{{ $service->phone }}</span></h2>
                                                </td>
                                                <td> {{ $service->payble }} </td>
                                                <td> {{ $service->sales_by }} </td>
                                                <td class="d-flex align-items-center">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class=" btn-action-icon "
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul>
                                                                <li>
                                                                    <a href="javascript:void(0)"
                                                                        class="dropdown-item view-sale-details-btn"
                                                                        data-sale-id="{{ $service->id }}">
                                                                        <i class="far fa-eye me-2"></i> Details
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" target="_blank"
                                                                        href="{{ route('sales.invoice', $service->id) }}">
                                                                        <i class="far fa-edit me-2"></i>Invoice </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('sales.edit', $service->id) }}">
                                                                        <i class="far fa-edit me-2"></i>Edit </a>
                                                                </li>
                                                                <li>
                                                                    <a onclick="if (confirm('Are you sure to delete the Sales?')) { document.getElementById('serviceDelete{{ $service->id }}').submit(); }"
                                                                        class="dropdown-item" href="javascript:void(0)">
                                                                        <i class="far fa-edit me-2"></i>Delete </a>
                                                                    <form id="serviceDelete{{ $service->id }}"
                                                                        action="{{ route('sales.destroy', $service->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!-- Sale Details Modal -->
                                    <div class="modal fade" id="saleDetailsModal" tabindex="-1"
                                        aria-labelledby="saleDetailsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="saleDetailsModalLabel">Sale Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" id="saleDetailsContent">
                                                    <!-- Content loaded dynamically -->
                                                    <p>Loading...</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </table>

                                <div class="dataTables_length" id="DataTables_Table_0_length">
                                    <label>
                                        <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0"
                                            class="custom-select custom-select-sm form-control form-control-sm">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item previous disabled"
                                            id="DataTables_Table_0_previous">
                                            <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0"
                                                tabindex="0" class="page-link">
                                                <i class="fa fa-angle-double-left me-2"></i> Previous </a>
                                        </li>
                                        <li class="paginate_button page-item active">
                                            <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1"
                                                tabindex="0" class="page-link">1</a>
                                        </li>
                                        <li class="paginate_button page-item next disabled" id="DataTables_Table_0_next">
                                            <a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2"
                                                tabindex="0" class="page-link">Next <i
                                                    class=" fa fa-angle-double-right ms-2"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                    aria-live="polite">Showing 1 to 6 of 6 entries</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-sale-details-btn').click(function() {
                let saleId = $(this).data('sale-id');

                // Use Laravel's route helper to generate URL pattern with placeholder
                let urlTemplate = "{{ route('sales.details', ':id') }}";
                let url = urlTemplate.replace(':id', saleId);

                $('#saleDetailsContent').html('<p>Loading...</p>');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(res) {
                        let sale = res.sale;
                        let items = res.items;

                        let html = `
            <h6>Customer: ${sale.name} (${sale.phone})</h6>
            <p>Address: ${sale.address}</p>
            <p>Bill: ${sale.bill}</p>
            <p>Discount: ${sale.discount}</p>
            <p>Payable: ${sale.payble}</p>
            <hr>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Warranty (Days)</th>
                  <th>Unit Price</th>
                  <th>Quantity</th>
                  <th>Total Price</th>
                </tr>
              </thead>
              <tbody>`;

                        items.forEach(function(item) {
                            html += `<tr>
                  <td>${item.name} (${item.model || ''})</td>
                  <td>${item.warranty || 'N/A'} <br><small class="text-muted">(${item.warranty_days_left} day(s) left)</small></td>
                  <td>${item.unit_price}</td>
                  <td>${item.qty}</td>
                  <td>${item.total_price}</td>
                </tr>`;
                        });


                        html += `</tbody></table>`;

                        $('#saleDetailsContent').html(html);
                        $('#saleDetailsModal').modal('show');
                    },
                    error: function() {
                        $('#saleDetailsContent').html('<p>Error loading sale details.</p>');
                    }
                });
            });
        });
    </script>
@endsection
