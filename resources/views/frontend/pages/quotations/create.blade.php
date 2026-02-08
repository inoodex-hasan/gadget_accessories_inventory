@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Create New Quotation</h4>
                            <div>
                                <a href="{{ route('quotations.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="billForm" action="{{ route('quotations.store') }}" method="POST">
                            @csrf
                            <!-- Client Information Section (Always Required) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Client Information</h5>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Select Existing Client/Company</label>
                                        <select class="form-control select2" name="client_id" id="client_id">
                                            <option value="">Select Existing Client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" data-name="{{ $client->name }}"
                                                    data-address="{{ $client->address }}" data-phone="{{ $client->phone }}"
                                                    data-email="{{ $client->email }}">
                                                    {{ $client->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Attention To</label>
                                        <input type="text" class="form-control" id="attention_to" name="attention_to"
                                            placeholder="Enter contact person name">
                                    </div>
                                </div>

                                <!-- Manual Input Fields -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Client/Company Name *</label>
                                        <input type="text" class="form-control" id="client_name" name="client_name"
                                            placeholder="Enter client or company name" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Designation </label>
                                        <input type="text" class="form-control" id="client_designation"
                                            name="client_designation" placeholder="Enter designation">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Client Address *</label>
                                        <textarea class="form-control" id="client_address" name="client_address" rows="3"
                                            placeholder="Enter client address" required></textarea>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="client_phone" name="client_phone"
                                            placeholder="Enter phone number">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="client_email" name="client_email"
                                            placeholder="Enter email address">
                                    </div>
                                </div> --}}
                            </div>

                            <!-- Body/Content Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Quotation Body Content</h5>
                                    <div class="form-group">
                                        <label class="form-label">Custom Body Content *</label>
                                        <textarea class="form-control" name="body_content" id="body_content" rows="12"
                                            placeholder="Write your quotation body content here..." required>Dear Sir,

Concerning the above-mentioned subject, we are pleased to propose a technical solution and financial appraisal for the supply & installation of the ID Card Printing System for your organization.

We appreciate your interest in Cost-saving & new state-of-the-art technology ID Card Printers. We guarantee customer satisfaction by providing both excellent services and products of the highest quality. We maintain spares as recommended by our principal. 

Intelligent Technology is a leading card printer, office automation, and security solution provider. The company has an expert team of technical persons consisting of graduates and diploma engineers. For our valued customers we have a service desk available on an 8 / 6 basis which ensures instant support. Please note that Intelligent Technology is the original Distributor of all kinds of the best products, ensuring quality products with quality services. Especially authorized distributor for HiTi Digital Inc, Taiwan, and reseller for Zebra Technologies, USA & Evolis Card Printer, France in Bangladesh. Also Provide a Biometric Attendance and Access Control System, CCTV Surveillance System, Fire Safety & Security Solutions and Interactive Whiteboard System for the Classroom.

Please do not hesitate to contact me for further inquiries. We will be happy to provide our best to you all the time. We are ready to conduct the demonstration at any time as per your kind schedule. A detail of the offer is enclosed herewith. If you have any further assistance, please do not hesitate to contact us. We assure you of our best co-operation.

Thanks, with assuring you our best services.
Yours Sincerely,</textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="use_default_body">
                                        <label class="form-check-label" for="use_default_body">
                                            Use default body content
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Totals Section -->
                            {{-- <div class="row mb-4">
                                <div class="col-md-6 offset-md-6">
                                    <input type="hidden" id="total_amount" name="total_amount" value="0">
                                    <input type="hidden" id="subtotal" name="subtotal" value="0">
                                </div>
                            </div> --}}

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Products</h5>
                                        </div>

                                        <!-- Add Product Section -->
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <label class="form-label">Select Product</label>
                                                <select class="form-control select2" id="product-select"
                                                    data-toggle="select2">
                                                    <option value="">Choose a product...</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                            data-model="{{ $product->model }}"
                                                            data-description="{{ $product->description }}"
                                                            data-price="{{ $product->price ?? 0 }}"
                                                            data-photos="{{ $product->photos ? json_encode($product->photos) : '[]' }}"
                                                            data-brand="{{ $product->brand->name ?? '' }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Unit Price</label>
                                                <input type="number" class="form-control" id="product-price"
                                                    value="0" step="0.01" min="0" placeholder="Price">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" id="product-quantity"
                                                    value="1" min="1" placeholder="Qty">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="d-grid">
                                                    <button type="button" class="btn btn-primary" id="add-product">
                                                        <i class="mdi mdi-plus-circle me-1"></i> Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="products-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="5%" class="text-center align-middle">S/L</th>
                                                            <th width="15%" class="text-center align-middle">PRODUCT
                                                                PHOTO</th>
                                                            <th width="40%" class="align-middle">PRODUCT DESCRIPTION
                                                            </th>
                                                            <th width="8%" class="text-center align-middle">QTY.</th>
                                                            <th width="12%" class="text-end align-middle">UNIT PRICE
                                                            </th>
                                                            <th width="12%" class="text-end align-middle">TOTAL PRICE
                                                            </th>
                                                            <th width="8%" class="text-center align-middle">ACTION
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="products-tbody">
                                                        <tr id="no-products">
                                                            <td colspan="7"
                                                                class="text-center text-muted py-4 align-middle">
                                                                <i class="mdi mdi-cart-off display-4 d-block mb-2"></i>
                                                                No products added
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="table-light">
                                                        <tr>
                                                            <td colspan="4" class="text-end align-middle"><strong>Sub
                                                                    Total:</strong></td>
                                                            <td colspan="2" class="text-end align-middle"><strong
                                                                    id="sub-total">0.00</strong></td>
                                                            <td class="align-middle"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" class="text-end align-middle">
                                                                <strong>Discount:</strong>
                                                            </td>
                                                            <td colspan="2" class="text-end align-middle">
                                                                <input type="number"
                                                                    class="form-control form-control-sm text-end"
                                                                    name="discount_amount" id="discount-amount"
                                                                    value="0" min="0" step="0.01">
                                                            </td>
                                                            <td class="align-middle"></td>
                                                        </tr>
                                                        <tr class="table-primary">
                                                            <td colspan="4" class="text-end align-middle"><strong>Total
                                                                    Amount:</strong></td>
                                                            <td colspan="2" class="text-end align-middle"><strong
                                                                    id="total-amount">0.00</strong></td>
                                                            <td class="align-middle"></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Terms and Conditions</h5>
                                    <div class="form-group">
                                        <label class="form-label">Custom Terms & Conditions *</label>
                                        <textarea class="form-control" name="terms_conditions" id="terms_conditions" rows="10"
                                            placeholder="Enter your custom terms and conditions here..." required></textarea>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="use_default_terms">
                                        <label class="form-check-label" for="use_default_terms">
                                            Use default terms and conditions
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Quotation Subject</h5>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Quotation Subject *</label>
                                        <input type="text" class="form-control" name="subject" value=""
                                            required placeholder="Enter Quotation subject">
                                    </div>
                                </div>
                            </div>

                            <!-- Sales Person Details Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Company & Signatory Details</h5>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Company Name *</label>
                                        <input type="text" class="form-control" name="company_name"
                                            value="Intelligent Technology" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Signatory Name *</label>
                                        <input type="text" class="form-control" name="signatory_name"
                                            value="Engr. Shamsul Alam" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Signatory Designation *</label>
                                        <input type="text" class="form-control" name="signatory_designation"
                                            value="Director (Technical)" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="company_phone"
                                            value="+880 XXXX-XXXXXX">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="company_email"
                                            value="info@intelligenttech.com">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" name="company_website"
                                            value="www.intelligenttech.com">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Additional Enclosed Documents</label>
                                    <textarea class="form-control" name="additional_enclosed" id="additional_enclosed" rows="5"
                                        placeholder="Add any additional enclosed documents here..."> Enclosed:
                                                1)	Price Quotation.
                                                2)	Summary.
                                                3)	Terms & Conditions.</textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            Save Quotation
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields to store selected data -->
                            <input type="hidden" name="selected_sale_id" id="selected_sale_id">
                            <input type="hidden" name="selected_project_id" id="selected_project_id">
                        </form>

                        <script>
                            // Client selection change handler
                            document.getElementById('client_id').addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];

                                if (selectedOption.value) {
                                    // Auto-fill manual fields with client data
                                    document.getElementById('client_name').value = selectedOption.getAttribute('data-name') || '';
                                    document.getElementById('client_address').value = selectedOption.getAttribute('data-address') || '';
                                    document.getElementById('client_phone').value = selectedOption.getAttribute('data-phone') || '';
                                    document.getElementById('client_email').value = selectedOption.getAttribute('data-email') || '';

                                    // Disable manual fields when client is selected
                                    document.getElementById('client_name').readOnly = true;
                                    document.getElementById('client_address').readOnly = true;
                                    document.getElementById('client_phone').readOnly = true;
                                    document.getElementById('client_email').readOnly = true;
                                } else {
                                    // Enable manual fields when no client is selected
                                    document.getElementById('client_name').readOnly = false;
                                    document.getElementById('client_address').readOnly = false;
                                    document.getElementById('client_phone').readOnly = false;
                                    document.getElementById('client_email').readOnly = false;

                                    // Clear manual fields
                                    document.getElementById('client_name').value = '';
                                    document.getElementById('client_address').value = '';
                                    document.getElementById('client_phone').value = '';
                                    document.getElementById('client_email').value = '';
                                }
                            });
                            document.addEventListener('DOMContentLoaded', function() {
                                console.log('Script loaded - initializing quotation form');

                                // Prevent duplicate loading
                                if (window.quotationFormHandlerLoaded) {
                                    console.error('WARNING: Script already loaded!');
                                    return;
                                }
                                window.quotationFormHandlerLoaded = true;

                                // Default content
                                const defaultBodyContent = `Dear Sir,

Concerning the above-mentioned subject, we are pleased to propose a technical solution and financial appraisal for the supply & installation of the ID Card Printing System for your organization.

We appreciate your interest in Cost-saving & new state-of-the-art technology ID Card Printers. We guarantee customer satisfaction by providing both excellent services and products of the highest quality. We maintain spares as recommended by our principal. 

Intelligent Technology is a leading card printer, office automation, and security solution provider. The company has an expert team of technical persons consisting of graduates and diploma engineers. For our valued customers we have a service desk available on an 8 / 6 basis which ensures instant support. Please note that Intelligent Technology is the original Distributor of all kinds of the best products, ensuring quality products with quality services. Especially authorized distributor for HiTi Digital Inc, Taiwan, and reseller for Zebra Technologies, USA & Evolis Card Printer, France in Bangladesh. Also Provide a Biometric Attendance and Access Control System, CCTV Surveillance System, Fire Safety & Security Solutions and Interactive Whiteboard System for the Classroom.

Please do not hesitate to contact me for further inquiries. We will be happy to provide our best to you all the time. We are ready to conduct the demonstration at any time as per your kind schedule. A detail of the offer is enclosed herewith. If you have any further assistance, please do not hesitate to contact us. We assure you of our best co-operation.

Thanks, with assuring you our best services.
Yours Sincerely,`;

                                const defaultTerms =
                                    `1. Intelligent Technology will promptly deliver the product from available stock or within 7 to 15 days upon order placement.
2. Intelligent Technology will provide (01) one-year service warranty for printer; however, no warranty is provided for printer heads & any others spare parts. 
    • Warranty doesn't acceptable against natural disaster, burn case for AC INPUT power fluctuation or any mechanical/Physical damage.
    • Warranty doesn't acceptable of the product if "warranty void seal" removed or tempered.
3. Accessories are not covered by any warranty.
4. The printer and its accessories cannot be exchanged or replaced once used.
5. The design of the card and lanyard must design must be approved by the relevant authority. Once printed, no modifications to the design will be allowed. 
6. The payment for the services will be made through an account payee cheque/DD/pay order, payable to Intelligent Technology, along with a corresponding work order.
7. This offer is made in Bangladesh Taka Only.
8. Government VAT and TAX are not included in the prices. If necessary, please incorporate the applicable amount of TAX and VAT in accordance with government or organizational regulations.
9. As you are experiencing, the cost of inconsistence supplies and raw materials is highly fluctuating and reflecting an increasing cost trend all along the supply chain. Price is increasing both freight and US$ to Taka conversion rate too. Validity of all quotation will be 15 days only.`;

                                // Initialize default content
                                document.getElementById('body_content').value = defaultBodyContent;
                                document.getElementById('terms_conditions').value = defaultTerms;

                                // Toggle default body content
                                document.getElementById('use_default_body').addEventListener('change', function() {
                                    const bodyTextarea = document.getElementById('body_content');
                                    if (this.checked) {
                                        bodyTextarea.value = defaultBodyContent;
                                    } else {
                                        bodyTextarea.value = '';
                                    }
                                });

                                // Toggle default terms and conditions
                                document.getElementById('use_default_terms').addEventListener('change', function() {
                                    const termsTextarea = document.getElementById('terms_conditions');
                                    if (this.checked) {
                                        termsTextarea.value = defaultTerms;
                                    } else {
                                        termsTextarea.value = '';
                                    }
                                });

                                // Products functionality
                                let productCounter = 0;

                                // Product selection change handler - Auto-fill price but allow editing
                                document.getElementById('product-select').addEventListener('change', function() {
                                    const selectedOption = this.options[this.selectedIndex];

                                    if (selectedOption.value) {
                                        const unitPrice = selectedOption.getAttribute('data-price');
                                        // Set the price but keep it editable
                                        document.getElementById('product-price').value = unitPrice;
                                    } else {
                                        document.getElementById('product-price').value = '0';
                                    }
                                });

                                // Add product button handler
                                document.getElementById('add-product').addEventListener('click', function() {
                                    const productSelect = document.getElementById('product-select');
                                    const selectedOption = productSelect.options[productSelect.selectedIndex];
                                    const quantity = parseInt(document.getElementById('product-quantity').value) || 1;
                                    const unitPrice = parseFloat(document.getElementById('product-price').value) || 0;

                                    if (!selectedOption.value) {
                                        alert('Please select a product');
                                        return;
                                    }

                                    if (unitPrice <= 0) {
                                        alert('Please enter a valid unit price');
                                        return;
                                    }

                                    const productId = selectedOption.value;
                                    const productName = selectedOption.getAttribute('data-name');
                                    const productModel = selectedOption.getAttribute('data-model');
                                    const productDescription = selectedOption.getAttribute('data-description');
                                    const brandName = selectedOption.getAttribute('data-brand');
                                    const photos = JSON.parse(selectedOption.getAttribute('data-photos') || '[]');
                                    const totalPrice = quantity * unitPrice;

                                    // Remove "no products" message
                                    const noProducts = document.getElementById('no-products');
                                    if (noProducts) noProducts.remove();

                                    // Add product row
                                    const tbody = document.getElementById('products-tbody');
                                    const rowId = 'product-' + productCounter;

                                    // Get product image or placeholder
                                    const productImage = photos.length > 0 ?
                                        `<img src="{{ asset('storage') }}/${photos[0]}" alt="${productName}" 
                  class="img-thumbnail mx-auto d-block" 
                  style="height: 80px; width: 80px; object-fit: cover;">` :
                                        `<div class="text-center text-muted mx-auto d-block" 
                  style="height: 80px; width: 80px; line-height: 80px; border: 1px dashed #ccc; border-radius: 4px;">
                <i class="mdi mdi-image-off"></i>
            </div>`;

                                    const row = document.createElement('tr');
                                    row.id = rowId;
                                    row.innerHTML = `
            <td class="text-center align-middle fw-bold">${productCounter + 1}.</td>
            <td class="text-center align-middle">
                ${productImage}
            </td>
            <td class="align-middle">
                <div class="product-info">
                    <h6 class="mb-1 text-primary">${productName}</h6>
                    ${brandName ? `<p class="text-muted mb-1 small"><strong>Brand:</strong> ${brandName}</p>` : ''}
                    ${productModel ? `<p class="text-muted mb-1 small"><strong>Model:</strong> ${productModel}</p>` : ''}
                    ${productDescription ? `<p class="mb-0 small text-muted">${productDescription}</p>` : ''}
                </div>
                <input type="hidden" name="items[${productCounter}][product_id]" value="${productId}">
                <input type="hidden" name="items[${productCounter}][description]" value="${productDescription}">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control form-control-sm text-center quantity" 
                       name="items[${productCounter}][quantity]" value="${quantity}" min="1" required>
            </td>
            <td class="text-end align-middle">
                <input type="number" class="form-control form-control-sm text-end unit-price" 
                       name="items[${productCounter}][unit_price]" value="${unitPrice}" step="0.01" min="0" required>
            </td>
            <td class="text-end align-middle fw-bold text-success">
                <span class="product-total">${totalPrice.toFixed(2)}</span>
            </td>
<td class="text-center align-middle">
    <button type="button" class="btn btn-sm btn-danger remove-product" data-row="${rowId}"
            title="Remove Product">
        X
    </button>
</td>
        `;

                                    tbody.appendChild(row);
                                    productCounter++;

                                    // Reset form
                                    productSelect.value = '';
                                    document.getElementById('product-quantity').value = 1;
                                    document.getElementById('product-price').value = '0';

                                    // Update totals
                                    updateTotals();

                                    // Add event listeners
                                    addRowEventListeners(rowId);
                                });

                                function addRowEventListeners(rowId) {
                                    const row = document.getElementById(rowId);
                                    const quantityInput = row.querySelector('.quantity');
                                    const priceInput = row.querySelector('.unit-price');

                                    [quantityInput, priceInput].forEach(input => {
                                        input.addEventListener('input', updateTotals);
                                    });

                                    // Remove product button
                                    row.querySelector('.remove-product').addEventListener('click', function() {
                                        if (confirm('Are you sure you want to remove this product?')) {
                                            document.getElementById(rowId).remove();
                                            updateTotals();
                                            renumberRows();
                                        }
                                    });
                                }

                                function renumberRows() {
                                    const rows = document.querySelectorAll('#products-tbody tr');
                                    if (rows.length === 0) {
                                        const tbody = document.getElementById('products-tbody');
                                        tbody.innerHTML = `
                <tr id="no-products">
                    <td colspan="7" class="text-center text-muted py-4 align-middle">
                        <i class="mdi mdi-cart-off display-4 d-block mb-2"></i>
                        No products added
                    </td>
                </tr>`;
                                    } else {
                                        rows.forEach((row, index) => {
                                            if (row.id !== 'no-products') {
                                                row.cells[0].textContent = (index + 1) + '.';
                                            }
                                        });
                                    }
                                }

                                function updateTotals() {
                                    let subTotal = 0;

                                    document.querySelectorAll('#products-tbody tr').forEach(row => {
                                        if (row.id !== 'no-products') {
                                            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                                            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                                            const total = quantity * unitPrice;

                                            row.querySelector('.product-total').textContent = total.toFixed(2);
                                            subTotal += total;
                                        }
                                    });

                                    const discount = parseFloat(document.getElementById('discount-amount').value) || 0;
                                    const totalAmount = Math.max(0, subTotal - discount);

                                    document.getElementById('sub-total').textContent = subTotal.toFixed(2);
                                    document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
                                }

                                // Initialize discount listener
                                document.getElementById('discount-amount').addEventListener('input', updateTotals);
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
