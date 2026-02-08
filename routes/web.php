<?php

use App\Http\Controllers\{
    BillController, BrandController, ChallanController, ClientController,
    CostCategoryController, CustomerController, EmployeeController,
    EmployeeTaDaController, ExpenseCategoryController, ExpenseController,
    FrontendController, InventoryController, ProductContoller,
    ProjectBillController, ProjectController, ProjectCostController,
    ProjectItemController, PurchaseController, QuotationController,
    RevenueController, RoleController, PermissionController,
    SalaryController, SalesController, ServiceController,
    TaDaController, UserController, VendorController, BankDetailController, 
    CompanyDetailController, InventoryItemController, BarcodeController, PurchaseItemController
};

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

// 1. DASHBOARD + EMPLOYEE-ONLY ROUTES → accessible by Super Admin AND Employee
// Route::middleware(['auth', 'role:Super Admin|Employee'])->group(function () {

//     // Dashboard - now accessible by both roles
    Route::get('/', [FrontendController::class, 'index'])->name('index')->middleware('auth');

//     // Employee TA/DA section (they need this too)
//     Route::prefix('employee')->name('employee.')->group(function () {
//         Route::get('tada', [EmployeeTaDaController::class, 'index'])->name('tada.index');
//         Route::get('tada/create', [EmployeeTaDaController::class, 'create'])->name('tada.create');
//         Route::post('tada/store', [EmployeeTaDaController::class, 'store'])->name('tada.store');
//         Route::get('tada/{id}/edit', [EmployeeTaDaController::class, 'edit'])->name('tada.edit');
//         Route::put('tada/{id}', [EmployeeTaDaController::class, 'update'])->name('tada.update');
//     });
// });

// 2. EVERYTHING ELSE → ONLY Super Admin
Route::middleware(['auth', 'role:Super Admin'])->group(function () {

    // === Administration ===
    Route::resource('users', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
    Route::get('/user/pin', [UserController::class, 'pin'])->name('users.pin');
    Route::post('/user/pin', [UserController::class, 'pinStore'])->name('users.pin_store');

    // === Products, Inventory, Purchases, Sales, Services, etc. ===
    Route::resource('brands', BrandController::class);
    Route::prefix('product')->group(function () {
        Route::resource('products', ProductContoller::class);
    });
    Route::prefix('inventory')->group(function () {
        Route::resource('inventory', InventoryController::class);
    });
    Route::resource('customers', CustomerController::class);
    Route::resource('vendors', VendorController::class);

    Route::prefix('purchase')->group(function () {
        Route::resource('purchase', PurchaseController::class);
        Route::get('latest-price/{id}', [PurchaseController::class, 'getLatestPrice'])->name('purchase.latest_price');
    });

    Route::resource('sales', SalesController::class);
    Route::get('sales/invoice/{id}', [SalesController::class, 'makeInvoice'])->name('sales.invoice');
    Route::get('/sales/payments/{saleId?}', [SalesController::class, 'payments'])->name('sales.payments');
    Route::get('sales/{id}/details', [SalesController::class, 'getSaleDetails'])->name('sales.details');

    Route::resource('service', ServiceController::class);
    Route::get('service/invoice/{id}', [ServiceController::class, 'makeInvoice'])->name('service.invoice');
    Route::get('complated/service', [ServiceController::class, 'complatedService'])->name('service.complated');
    Route::post('service/makecomplate/{id}', [ServiceController::class, 'makeComplate'])->name('service.makecomplate');
    Route::get('service-payments', [ServiceController::class, 'payments'])->name('service.payments');
    Route::post('/submit-rating', [ServiceController::class, 'storeRating'])->name('submit.rating');

    // === Projects, Bills, Challans, Quotations, etc. ===
    // Route::resource('projects', ProjectController::class);
    // Route::get('/projects/payments/{project}', [ProjectController::class, 'payments'])->name('projects.payments');
    Route::resource('clients', ClientController::class);
    Route::resource('cost-categories', CostCategoryController::class);
    // Route::resource('project-costs', ProjectCostController::class);
    // Route::resource('project-items', ProjectItemController::class);

    Route::prefix('bills')->group(function () {
        Route::get('/', [BillController::class, 'index'])->name('bills.index');
        Route::get('/create', [BillController::class, 'create'])->name('bills.create');
        Route::get('/get-sales', [BillController::class, 'getSales'])->name('api.sales');
        Route::get('/get-projects', [BillController::class, 'getProjects'])->name('api.projects');
        Route::post('/', [BillController::class, 'store'])->name('bills.store');
        Route::get('/{bill}', [BillController::class, 'show'])->name('bills.show');
        Route::get('/{bill}/preview', [BillController::class, 'preview'])->name('bills.preview');
        Route::get('/{bill}/download', [BillController::class, 'download'])->name('bills.download');
        Route::post('/{bill}/status', [BillController::class, 'updateStatus'])->name('bills.status.update');
        Route::delete('/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');
    });

    Route::resource('challans', ChallanController::class);
    Route::get('/challans/{challan}/download', [ChallanController::class, 'download'])->name('challans.download');
    Route::get('/get-sales', [ChallanController::class, 'getSales'])->name('challans.get-sales');
    Route::get('/get-projects', [ChallanController::class, 'getProjects'])->name('challans.get-projects');

    Route::resource('quotations', QuotationController::class);
    Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'generatePDF'])->name('quotations.pdf');
    Route::get('/quotations/{quotation}/download', [QuotationController::class, 'download'])->name('quotations.download');
    Route::post('quotations/{quotation}/send', [QuotationController::class, 'sendQuotation'])->name('quotations.send');

    Route::get('/projects/{project}/bills/create', [ProjectBillController::class, 'createBill'])->name('projects.bills.create');
    Route::post('/projects/{project}/bills/', [ProjectBillController::class, 'storeBill'])->name('projects.bills.store');

    // === HR (Super Admin only) ===
    // Route::resource('employees', EmployeeController::class);
    // Route::get('employees/{id}', [EmployeeController::class, 'show'])->name('employees.view');
    // Route::resource('ta-da', TaDaController::class);
    // Route::resource('salary', SalaryController::class);
    Route::resource('dailyExpenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);

    Route::post('/salary/get-tada-data-ajax', [SalaryController::class, 'getTaDaDataAjax'])->name('salary.get-tada-data-ajax');
    Route::get('/employee/{id}/advance-sum-by-month', [EmployeeController::class, 'getAdvanceSumByMonth']);
    Route::get('/employee/{id}/advance-sum', [ExpenseController::class, 'getAdvanceSum']);

    // === Reports & Payments ===
    Route::get('purchase-report', [PurchaseController::class, 'reportIndex'])->name('purchase.report');
    Route::get('purchase/report', [PurchaseController::class, 'report'])->name('purchase.report.get');
    Route::get('purchase/report/download', [PurchaseController::class, 'downloadPurchaseReport'])->name('purchase.report.download');
    Route::get('sales-report', [SalesController::class, 'report'])->name('sales.report');
    Route::get('sales/report/download', [SalesController::class, 'downloadSalesReport'])->name('sales.report.download');
    Route::get('/revenues', [RevenueController::class, 'index'])->name('revenues.index');
    Route::post('/revenues/generate', [RevenueController::class, 'generate'])->name('revenues.generate');
    Route::get('/revenues/export/{id}', [RevenueController::class, 'export'])->name('revenues.export');
    Route::get('/due-payments', [SalesController::class, 'duePayments'])->name('due-payments.index');

    Route::post('/sales/process-payment', [SalesController::class, 'processPayment'])->name('sales.process-payment');
    Route::post('/projects/process-payment', [ProjectController::class, 'processPayment'])->name('projects.process-payment');
    Route::get('/sales/search-orders', [SalesController::class, 'searchOrders'])->name('sales.search-orders');

    Route::resource('bank-details', BankDetailController::class);
    Route::post('bank-details/{bankDetail}/set-default', [BankDetailController::class, 'setDefault'])->name('bank-details.set-default');
    
    Route::resource('company-details', CompanyDetailController::class);
    Route::post('company-details/{companyDetail}/set-default', [CompanyDetailController::class, 'setDefault'])->name('company-details.set-default');

Route::prefix('inventory-items')->name('inventory-items.')->group(function () {
    Route::get('/', [InventoryItemController::class, 'index'])->name('index');
    Route::get('/create', [InventoryItemController::class, 'create'])->name('create');
    Route::post('/', [InventoryItemController::class, 'store'])->name('store');
    Route::get('/{id}', [InventoryItemController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [InventoryItemController::class, 'edit'])->name('edit');
    Route::put('/{id}', [InventoryItemController::class, 'update'])->name('update');
    Route::delete('/{id}', [InventoryItemController::class, 'destroy'])->name('destroy');
    
    // Barcode routes
    Route::get('/scan', [InventoryItemController::class, 'scan'])->name('scan');
    Route::post('/process-scan', [InventoryItemController::class, 'processScan'])->name('process-scan');
    Route::get('/{id}/download-barcode', [InventoryItemController::class, 'downloadBarcode'])->name('download-barcode');
    Route::get('/{id}/print-barcode', [InventoryItemController::class, 'printBarcode'])->name('print-barcode');
    Route::post('/bulk-generate', [InventoryItemController::class, 'bulkGenerate'])->name('bulk-generate');
    Route::post('/bulk-download', [InventoryItemController::class, 'bulkDownload'])->name('bulk-download');
    Route::post('/bulk-download-pdf', [InventoryItemController::class, 'bulkDownloadPdf'])->name('bulk-download-pdf');
    });

    Route::get('/scan-add-product-page', [ProductContoller::class, 'showScanPage'])->name('products.scan-page');
    Route::post('/scan-add-product', [ProductContoller::class, 'scanAndAdd'])->name('products.scan-add');


    Route::post('/barcode/lookup', [ProductContoller::class, 'lookupBarcode'])->name('barcode.lookup');
    Route::get('/barcode/generate/{barcode}', [ProductContoller::class, 'generateBarcode']);

    Route::post('/scan-barcode', [InventoryController::class, 'scanBarcode']);
    Route::post('/inventory/check-duplicate', [InventoryController::class, 'checkDuplicate'])
    ->name('inventory.check-duplicate');

    Route::post('/inventory/receive-by-scan', [InventoryController::class, 'receiveByScan'])
    ->name('inventory.receive-by-scan');

    Route::get('/receive-stock', [PurchaseItemController::class, 'index'])
     ->name('receive-stock');
    Route::post('/receive-stock', [PurchaseItemController::class, 'store'])
    ->name('receive-stock.store');
});