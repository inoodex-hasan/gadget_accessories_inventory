@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Create New Challan</h4>
                            <div>
                                <a href="{{ route('challans.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="challanForm" action="{{ route('challans.store') }}" method="POST">
                            @csrf

                            <!-- Challan Basic Information -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Challan Information</h5>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Challan Type *</label>
                                        <select class="form-control" name="type" id="challan_type" required>
                                            <option value="">Select Challan Type</option>
                                            <option value="sale">Sales Challan</option>
                                            <option value="project">Project Challan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Dynamic Selection Area -->
                                <div class="col-md-6" id="dynamic-selection">
                                    <!-- Will be populated by JavaScript -->
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Reference Number *</label>
                                        <input type="text" class="form-control" name="reference_number"
                                            value="CHL-{{ date('Ymd-His') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Challan Date *</label>
                                        <input type="date" class="form-control" name="challan_date"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Recipient Information Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 text-primary">Recipient Information</h5>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Organization Name *</label>
                                        <input type="text" class="form-control" id="recipient_organization"
                                            name="recipient_organization" placeholder="Enter organization name" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Attention To</label>
                                        <input type="text" class="form-control" id="attention_to" name="attention_to"
                                            placeholder="Enter contact person name">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Recipient Designation</label>
                                        <input type="text" class="form-control" id="recipient_designation"
                                            name="recipient_designation">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Address *</label>
                                        <textarea class="form-control" id="recipient_address" name="recipient_address" rows="3"
                                            placeholder="Enter recipient address" readonly></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Section -->
                            {{-- <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Challan Subject</h5>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">Subject *</label>
                                        <input type="text" class="form-control" name="subject"
                                            value="Delivery Challan for Supplying of Products/Services" required
                                            placeholder="Enter challan subject">
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Auto-filled Information Section -->
                            <div class="row mb-4 p-2" id="auto-info-section" style="display: none;">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 text-success">
                                        Auto-filled Information
                                    </h5>
                                </div>

                                <div class="row w-100">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0" id="info-title">Details</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 mb-2">
                                                        <strong>Reference No:</strong> <span id="detail-reference">-</span>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <strong>Date:</strong> <span id="detail-date">-</span>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <strong>Total Items:</strong> <span id="detail-amount">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Challan Items Section -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Challan Items</h5>
                                        <div>
                                            <span class="badge bg-info" id="items-count">0 items</span>
                                        </div>
                                    </div>

                                    <div id="items-container">
                                        <div class="text-center p-4" id="no-items-message">
                                            <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">Select a sale or project to auto-fill items</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields to store selected data -->
                            <input type="hidden" name="selected_sale_id" id="selected_sale_id">
                            <input type="hidden" name="selected_project_id" id="selected_project_id">

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success btn-lg" id="generateBtn">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                                aria-hidden="true"></span>
                                            <span class="btn-text">Generate Challan</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- 
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                console.log('Challan form script loaded');

                                const challanTypeSelect = document.getElementById('challan_type');
                                const dynamicSelection = document.getElementById('dynamic-selection');
                                const autoInfoSection = document.getElementById('auto-info-section');
                                const itemsContainer = document.getElementById('items-container');
                                const noItemsMessage = document.getElementById('no-items-message');
                                const challanForm = document.getElementById('challanForm');
                                const submitBtn = document.querySelector('button[type="submit"]');

                                const baseUrl = window.location.origin;

                                // Reset button state on page load (in case of form submission)
                                resetButtonState();

                                // Challan type change handler
                                challanTypeSelect.addEventListener('change', function() {
                                    const challanType = this.value;
                                    console.log('Challan type selected:', challanType);
                                    dynamicSelection.innerHTML = '';
                                    autoInfoSection.style.display = 'none';
                                    clearItems();

                                    if (challanType === 'sale') {
                                        showSalesSelection();
                                    } else if (challanType === 'project') {
                                        showProjectsSelection();
                                    }
                                });

                                function showSalesSelection() {
                                    let html = `
                <div class="form-group">
                    <label class="form-label">Select Sale</label>
                    <select class="form-control" name="sale_id" id="sale_select">
                        <option value="">Select a Sale</option>
                    </select>
                </div>
            `;
                                    dynamicSelection.innerHTML = html;

                                    console.log('Fetching sales data...');

                                    fetch(`${baseUrl}/get-sales`)
                                        .then(response => {
                                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                                            return response.json();
                                        })
                                        .then(data => {
                                            console.log('Sales API response:', data);
                                            populateSalesSelect(data);
                                        })
                                        .catch(error => {
                                            console.error('Error loading sales:', error);
                                            document.getElementById('sale_select').innerHTML =
                                                '<option value="">Error loading sales</option>';
                                        });
                                }

                                function populateSalesSelect(data) {
                                    const select = document.getElementById('sale_select');

                                    if (data.sales && data.sales.length > 0) {
                                        select.innerHTML = '<option value="">Select a Sale</option>';

                                        data.sales.forEach(sale => {
                                            const customerName = sale.customer?.name || 'Unknown Customer';
                                            select.innerHTML +=
                                                `<option value="${sale.id}">${sale.order_no} - ${customerName}</option>`;
                                        });

                                        select.addEventListener('change', function() {
                                            const saleId = this.value;
                                            if (saleId) {
                                                const sale = data.sales.find(s => s.id == saleId);
                                                console.log('Selected sale:', sale);
                                                autoFillSaleData(sale);
                                            } else {
                                                autoInfoSection.style.display = 'none';
                                                clearItems();
                                            }
                                        });
                                    } else {
                                        select.innerHTML = '<option value="">No sales found</option>';
                                    }
                                }

                                function showProjectsSelection() {
                                    let html = `
                <div class="form-group">
                    <label class="form-label">Select Project</label>
                    <select class="form-control" name="project_id" id="project_select">
                        <option value="">Select a Project</option>
                    </select>
                </div>
            `;
                                    dynamicSelection.innerHTML = html;

                                    console.log('Fetching projects data...');

                                    fetch(`${baseUrl}/get-projects`)
                                        .then(response => {
                                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                                            return response.json();
                                        })
                                        .then(data => {
                                            console.log('Projects API response:', data);
                                            populateProjectsSelect(data);
                                        })
                                        .catch(error => {
                                            console.error('Error loading projects:', error);
                                            document.getElementById('project_select').innerHTML =
                                                '<option value="">Error loading projects</option>';
                                        });
                                }

                                function populateProjectsSelect(data) {
                                    const select = document.getElementById('project_select');

                                    if (data.projects && data.projects.length > 0) {
                                        select.innerHTML = '<option value="">Select a Project</option>';

                                        data.projects.forEach(project => {
                                            const clientName = project.client?.name || 'Unknown Client';
                                            select.innerHTML +=
                                                `<option value="${project.id}">${project.name} - ${clientName}</option>`;
                                        });

                                        select.addEventListener('change', function() {
                                            const projectId = this.value;
                                            if (projectId) {
                                                const project = data.projects.find(p => p.id == projectId);
                                                console.log('Selected project:', project);
                                                autoFillProjectData(project);
                                            } else {
                                                autoInfoSection.style.display = 'none';
                                                clearItems();
                                            }
                                        });
                                    } else {
                                        select.innerHTML = '<option value="">No projects found</option>';
                                    }
                                }

                                function autoFillSaleData(sale) {
                                    console.log('Auto-filling sale data:', sale);

                                    document.getElementById('selected_sale_id').value = sale.id;
                                    document.getElementById('selected_project_id').value = '';

                                    // Auto-fill recipient information
                                    if (sale.customer) {
                                        document.getElementById('recipient_organization').value = sale.customer.name || '';
                                        document.getElementById('recipient_address').value = sale.customer.address || '';
                                    }

                                    // Update auto-info section
                                    document.getElementById('info-title').innerHTML = 'Sale Information';
                                    document.getElementById('detail-reference').textContent = sale.order_no || 'N/A';
                                    document.getElementById('detail-date').textContent = sale.date || 'N/A';
                                    document.getElementById('detail-amount').textContent = (sale.items?.length || 0) + ' items';

                                    autoInfoSection.style.display = 'block';

                                    // Populate items - they are now in sale.items
                                    const items = sale.items || [];
                                    console.log('Sale items:', items);
                                    populateItems(items, 'sale');
                                }

                                function autoFillProjectData(project) {
                                    console.log('Auto-filling project data:', project);

                                    document.getElementById('selected_project_id').value = project.id;
                                    document.getElementById('selected_sale_id').value = '';

                                    // Auto-fill recipient information
                                    if (project.client) {
                                        document.getElementById('recipient_organization').value = project.client.name || '';
                                        document.getElementById('recipient_address').value = project.client.address || '';
                                    }

                                    // Update auto-info section
                                    document.getElementById('info-title').innerHTML = 'Project Information';
                                    document.getElementById('detail-reference').textContent = project.reference || project.name ||
                                        'N/A';
                                    document.getElementById('detail-date').textContent = project.date || 'N/A';
                                    document.getElementById('detail-amount').textContent = (project.items?.length || 0) + ' items';

                                    autoInfoSection.style.display = 'block';

                                    // Populate items - they are now in project.items
                                    const items = project.items || [];
                                    console.log('Project items:', items);
                                    populateItems(items, 'project');
                                }

                                function populateItems(items, type) {
                                    console.log('Populating items:', items);

                                    noItemsMessage.style.display = 'none';
                                    itemsContainer.innerHTML = '';

                                    if (!items || items.length === 0) {
                                        console.log('No items to display');
                                        itemsContainer.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        No items found in the selected ${type}.
                    </div>
                `;
                                        document.getElementById('items-count').textContent = '0 items';
                                        return;
                                    }

                                    items.forEach((item, index) => {
                                        console.log(`Processing item ${index}:`, item);

                                        const description = item.description || 'Item ' + (index + 1);
                                        const quantity = item.quantity || 1;
                                        const unit = item.unit || 'Piece';

                                        const itemHtml = `
                    <div class="item-card card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Item Description *</label>
                                        <textarea class="form-control item-description" name="items[${index}][description]" rows="3" required>${description}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Quantity *</label>
                                        <input type="number" class="form-control item-quantity" 
                                            name="items[${index}][quantity]" value="${quantity}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">Unit</label>
                                        <input type="text" class="form-control" name="items[${index}][unit]" value="${unit}">
                                    </div>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                `;
                                        itemsContainer.innerHTML += itemHtml;
                                    });

                                    document.getElementById('items-count').textContent = items.length + ' item(s)';
                                    console.log(`Successfully populated ${items.length} items`);
                                }

                                function clearItems() {
                                    itemsContainer.innerHTML = '';
                                    noItemsMessage.style.display = 'block';
                                    document.getElementById('items-count').textContent = '0 items';
                                }

                                function resetButtonState() {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = 'Generate Challan';
                                }

                                // Simple form submission
                                challanForm.addEventListener('submit', function(e) {
                                    // Check if items are present
                                    const itemCards = document.querySelectorAll('.item-card');
                                    if (itemCards.length === 0) {
                                        e.preventDefault();
                                        alert('ERROR: No items found! Please select a sale or project first.');
                                        return false;
                                    }

                                    // Show loading state
                                    submitBtn.disabled = true;
                                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';

                                    // Let the form submit normally
                                    return true;
                                });

                                console.log('Challan form handler ready');
                            });
                        </script> --}}

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                console.log('Challan form script loaded');

                                const challanTypeSelect = document.getElementById('challan_type');
                                const dynamicSelection = document.getElementById('dynamic-selection');
                                const autoInfoSection = document.getElementById('auto-info-section');
                                const itemsContainer = document.getElementById('items-container');
                                const noItemsMessage = document.getElementById('no-items-message');
                                const challanForm = document.getElementById('challanForm');
                                const submitBtn = document.getElementById('generateBtn');

                                const baseUrl = window.location.origin;
                                let formSubmitted = false;

                                // Challan type change handler
                                challanTypeSelect.addEventListener('change', function() {
                                    const challanType = this.value;
                                    console.log('Challan type selected:', challanType);
                                    dynamicSelection.innerHTML = '';
                                    autoInfoSection.style.display = 'none';
                                    clearItems();

                                    if (challanType === 'sale') {
                                        showSalesSelection();
                                    } else if (challanType === 'project') {
                                        showProjectsSelection();
                                    }
                                });

                                function showSalesSelection() {
                                    let html = `
            <div class="form-group">
                <label class="form-label">Select Sale *</label>
                <select class="form-control" name="sale_id" id="sale_select" required>
                    <option value="">Select a Sale</option>
                </select>
            </div>
        `;
                                    dynamicSelection.innerHTML = html;

                                    fetch(`${baseUrl}/get-sales`)
                                        .then(response => {
                                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                                            return response.json();
                                        })
                                        .then(data => {
                                            populateSalesSelect(data);
                                        })
                                        .catch(error => {
                                            console.error('Error loading sales:', error);
                                            document.getElementById('sale_select').innerHTML =
                                                '<option value="">Error loading sales</option>';
                                        });
                                }

                                function populateSalesSelect(data) {
                                    const select = document.getElementById('sale_select');

                                    if (data.sales && data.sales.length > 0) {
                                        select.innerHTML = '<option value="">Select a Sale</option>';

                                        // Filter only INV-* orders
                                        const invSales = data.sales.filter(sale => {
                                            return sale.order_no && sale.order_no.startsWith('INV-');
                                        });

                                        if (invSales.length > 0) {
                                            invSales.forEach(sale => {
                                                const customerName = sale.customer?.name || 'Unknown Customer';
                                                select.innerHTML +=
                                                    `<option value="${sale.id}">${sale.order_no} - ${customerName}</option>`;
                                            });
                                        } else {
                                            select.innerHTML = '<option value="">No invoice sales found</option>';
                                        }

                                        select.addEventListener('change', function() {
                                            const saleId = this.value;
                                            if (saleId) {
                                                const sale = invSales.find(s => s.id == saleId);
                                                autoFillSaleData(sale);
                                            } else {
                                                autoInfoSection.style.display = 'none';
                                                clearItems();
                                            }
                                        });
                                    } else {
                                        select.innerHTML = '<option value="">No sales found</option>';
                                    }
                                }

                                function showProjectsSelection() {
                                    let html = `
            <div class="form-group">
                <label class="form-label">Select Project *</label>
                <select class="form-control" name="project_id" id="project_select" required>
                    <option value="">Select a Project</option>
                </select>
            </div>
        `;
                                    dynamicSelection.innerHTML = html;

                                    fetch(`${baseUrl}/get-projects`)
                                        .then(response => {
                                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                                            return response.json();
                                        })
                                        .then(data => {
                                            populateProjectsSelect(data);
                                        })
                                        .catch(error => {
                                            console.error('Error loading projects:', error);
                                            document.getElementById('project_select').innerHTML =
                                                '<option value="">Error loading projects</option>';
                                        });
                                }

                                function populateProjectsSelect(data) {
                                    const select = document.getElementById('project_select');

                                    if (data.projects && data.projects.length > 0) {
                                        select.innerHTML = '<option value="">Select a Project</option>';

                                        data.projects.forEach(project => {
                                            const clientName = project.client?.name || 'Unknown Client';
                                            select.innerHTML +=
                                                `<option value="${project.id}">${project.name} - ${clientName}</option>`;
                                        });

                                        select.addEventListener('change', function() {
                                            const projectId = this.value;
                                            if (projectId) {
                                                const project = data.projects.find(p => p.id == projectId);
                                                autoFillProjectData(project);
                                            } else {
                                                autoInfoSection.style.display = 'none';
                                                clearItems();
                                            }
                                        });
                                    } else {
                                        select.innerHTML = '<option value="">No projects found</option>';
                                    }
                                }

                                function autoFillSaleData(sale) {
                                    document.getElementById('selected_sale_id').value = sale.id;
                                    document.getElementById('selected_project_id').value = '';

                                    // Auto-fill recipient information
                                    if (sale.customer) {
                                        document.getElementById('recipient_organization').value = sale.customer.name || '';
                                        document.getElementById('recipient_address').value = sale.customer.address || '';
                                    }

                                    // Update auto-info section
                                    document.getElementById('info-title').innerHTML = 'Sale Information';
                                    document.getElementById('detail-reference').textContent = sale.order_no || 'N/A';
                                    document.getElementById('detail-date').textContent = sale.date || 'N/A';
                                    document.getElementById('detail-amount').textContent = (sale.items?.length || 0) + ' items';

                                    autoInfoSection.style.display = 'block';
                                    populateItems(sale.items || [], 'sale');
                                }

                                function autoFillProjectData(project) {
                                    document.getElementById('selected_project_id').value = project.id;
                                    document.getElementById('selected_sale_id').value = '';

                                    // Auto-fill recipient information
                                    if (project.client) {
                                        document.getElementById('recipient_organization').value = project.client.name || '';
                                        document.getElementById('recipient_address').value = project.client.address || '';
                                    }

                                    // Update auto-info section
                                    document.getElementById('info-title').innerHTML = 'Project Information';
                                    document.getElementById('detail-reference').textContent = project.reference || project.name ||
                                        'N/A';
                                    document.getElementById('detail-date').textContent = project.date || 'N/A';
                                    document.getElementById('detail-amount').textContent = (project.items?.length || 0) + ' items';

                                    autoInfoSection.style.display = 'block';
                                    populateItems(project.items || [], 'project');
                                }

                                function populateItems(items, type) {
                                    noItemsMessage.style.display = 'none';
                                    itemsContainer.innerHTML = '';

                                    if (!items || items.length === 0) {
                                        itemsContainer.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    No items found in the selected ${type}.
                </div>
            `;
                                        document.getElementById('items-count').textContent = '0 items';
                                        return;
                                    }

                                    items.forEach((item, index) => {
                                        const description = item.description || 'Item ' + (index + 1);
                                        const quantity = item.quantity || 1;
                                        const unit = item.unit || 'Piece';

                                        const itemHtml = `
                <div class="item-card card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Item Description *</label>
                                    <textarea class="form-control item-description" name="items[${index}][description]" rows="3" required>${description}</textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Quantity *</label>
                                    <input type="number" class="form-control item-quantity" 
                                        name="items[${index}][quantity]" value="${quantity}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Unit</label>
                                    <input type="text" class="form-control" name="items[${index}][unit]" value="${unit}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                                        itemsContainer.innerHTML += itemHtml;
                                    });

                                    document.getElementById('items-count').textContent = items.length + ' item(s)';
                                }

                                function clearItems() {
                                    itemsContainer.innerHTML = '';
                                    noItemsMessage.style.display = 'block';
                                    document.getElementById('items-count').textContent = '0 items';
                                }

                                function setLoadingState(isLoading) {
                                    const spinner = submitBtn.querySelector('.spinner-border');
                                    const btnText = submitBtn.querySelector('.btn-text');

                                    if (isLoading) {
                                        submitBtn.disabled = true;
                                        spinner.classList.remove('d-none');
                                        btnText.textContent = 'Generating Challan...';
                                    } else {
                                        submitBtn.disabled = false;
                                        spinner.classList.add('d-none');
                                        btnText.textContent = 'Generate Challan';
                                    }
                                }

                                // Form submission handler
                                function handleFormSubmit(e) {
                                    e.preventDefault();
                                    e.stopImmediatePropagation();

                                    if (formSubmitted) {
                                        return false;
                                    }

                                    // Check if items are present
                                    const itemCards = document.querySelectorAll('.item-card');
                                    if (itemCards.length === 0) {
                                        alert('ERROR: No items found! Please select a sale or project first.');
                                        return false;
                                    }

                                    // Validate required fields
                                    const challanType = document.getElementById('challan_type').value;
                                    const recipientOrganization = document.getElementById('recipient_organization').value;
                                    const recipientAddress = document.getElementById('recipient_address').value;

                                    if (!challanType) {
                                        alert('Please select challan type');
                                        return false;
                                    }

                                    if (!recipientOrganization.trim()) {
                                        alert('Please enter organization name');
                                        return false;
                                    }

                                    if (!recipientAddress.trim()) {
                                        alert('Please enter recipient address');
                                        return false;
                                    }

                                    formSubmitted = true;
                                    setLoadingState(true);

                                    const formData = new FormData(this);

                                    fetch(this.action, {
                                            method: 'POST',
                                            body: formData,
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                            }
                                        })
                                        .then(response => {
                                            if (!response.ok) throw new Error('Network response was not ok: ' + response.status);

                                            const contentType = response.headers.get('content-type');
                                            if (contentType && contentType.includes('application/pdf')) {
                                                return response.blob().then(blob => {
                                                    const url = window.URL.createObjectURL(blob);
                                                    const a = document.createElement('a');
                                                    a.style.display = 'none';
                                                    a.href = url;

                                                    const contentDisposition = response.headers.get('content-disposition');
                                                    let filename = 'challan.pdf';
                                                    if (contentDisposition) {
                                                        const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                                                        if (filenameMatch) filename = filenameMatch[1];
                                                    }

                                                    a.download = filename;
                                                    document.body.appendChild(a);
                                                    a.click();

                                                    setTimeout(() => {
                                                        window.URL.revokeObjectURL(url);
                                                        document.body.removeChild(a);
                                                        alert('Challan generated and downloaded successfully!');
                                                        setLoadingState(false);
                                                        formSubmitted = false;
                                                    }, 100);
                                                });
                                            } else {
                                                return response.text().then(text => {
                                                    throw new Error('Server returned an unexpected response');
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error generating challan: ' + error.message);
                                            setLoadingState(false);
                                            formSubmitted = false;
                                        });

                                    return false;
                                }

                                // Add event listener to form
                                challanForm.addEventListener('submit', handleFormSubmit);
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
