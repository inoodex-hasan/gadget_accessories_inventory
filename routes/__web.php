<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CostCategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeTaDaController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaytrailController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductContoller;
use App\Http\Controllers\ProjectBillController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectCostController;
use App\Http\Controllers\ProjectItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TaDaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Mail\VerificationMail;
use App\Models\Admin\DelivaryCharge;
use App\Models\Extra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    Route::get('/', [FrontendController::class, 'index'])->name('index');

    //Product Management
    Route::group(['middleware' => ['permission:Product Management']], function () {
        Route::prefix('product')->middleware(['auth'])->group(function () {
            Route::resource('products', ProductContoller::class);
        });
        Route::resource('brands', BrandController::class);
    });

    //Customer Management
    Route::group(['middleware' => ['permission:Customer Management']], function () {
        Route::middleware(['auth'])->group(function () {
            Route::resource('customers', CustomerController::class);
        });
    });

    //Vendor Management
    Route::group(['middleware' => ['permission:Vendor Management']], function () {
        Route::middleware(['auth'])->group(function () {
            Route::resource('vendors', VendorController::class);
        });
    });

    //Purchase  Management
    Route::group(['middleware' => ['permission:Purchase Management']], function () {
        Route::prefix('purchase')->middleware(['auth'])->group(function () {
            Route::resource('purchase', PurchaseController::class);
            Route::get('latest-price/{id}', [PurchaseController::class, 'getLatestPrice'])->name('purchase.latest_price');
        });
    });

    //Inventory Management
    Route::group(['middleware' => ['permission:Inventory Management']], function () {
        Route::prefix('inventory')->middleware(['auth'])->group(function () {
            Route::resource('inventory', InventoryController::class);
        });
    });

    Route::group(['middleware' => ['permission:Administration']], function () {
        Route::resource('users', UserController::class);
        Route::resource('role', RoleController::class);
        Route::resource('permission', PermissionController::class);

        Route::get('/user/pin', [UserController::class, 'pin'])->name('users.pin');
        Route::post('/user/pin', [UserController::class, 'pinStore'])->name('users.pin_store');
    });

    Route::group(['middleware' => ['permission:Service Management']], function () {
        Route::resource('service', ServiceController::class);
        Route::get('service/invoice/{id}', [ServiceController::class, 'makeInvoice'])->name('service.invoice');
        Route::get('complated/service', [ServiceController::class, 'complatedService'])->name('service.complated');
        Route::post('service/makecomplate/{id}', [ServiceController::class, 'makeComplate'])->name('service.makecomplate');
        Route::get('service-payments', [ServiceController::class, 'payments'])->name('service.payments');
        Route::post('/submit-rating', [ServiceController::class, 'storeRating'])->name('submit.rating');
    });
    Route::group(['middleware' => ['permission:Sales Management']], function () {
        Route::resource('sales', SalesController::class);
        Route::get('sales/invoice/{id}', [SalesController::class, 'makeInvoice'])->name('sales.invoice');
        Route::get('/sales/payments/{saleId?}', [SalesController::class, 'payments'])->name('sales.payments');
        // Route::get('sales-payments', [SalesController::class, 'payments'])->name('sales.payments');
        Route::get('sales/{id}/details', [SalesController::class, 'getSaleDetails'])->name('sales.details');
    });
    Route::group(['middleware' => ['permission:Report Management']], function () {
        Route::get('purchase-report', [PurchaseController::class, 'reportIndex'])->name('purchase.report');
        Route::get('purchase/report', [PurchaseController::class, 'report'])->name('purchase.report.get');
        Route::get('sales-report', [SalesController::class, 'report'])->name('sales.report');
    });

    Route::group(['middleware' => ['permission:Report Management']], function () {
        Route::resource('expense-categories', ExpenseCategoryController::class);
        Route::resource('dailyExpenses', ExpenseController::class);
        Route::get('/employee/{id}/advance-sum', [ExpenseController::class, 'getAdvanceSum']);

    });

    Route::get('/revenues', [RevenueController::class, 'index'])->name('revenues.index')->middleware(['auth', 'role:Super Admin']);
    Route::post('/revenues/generate', [RevenueController::class, 'generate'])->name('revenues.generate')->middleware(['auth', 'role:Super Admin']);
    Route::get('/revenues/export/{id}', [RevenueController::class, 'export'])->name('revenues.export')->middleware(['auth', 'role:Super Admin']);


    Route::get('/due-payments', [SalesController::class, 'duePayments'])->name('due-payments.index');

    Route::resource('employees', EmployeeController::class)->middleware(['auth', 'role:Super Admin']);
    Route::get('employees/{id}', [EmployeeController::class, 'show'])->name('employees.view');


    Route::resource('ta-da', controller: TaDaController::class)->middleware(['auth', 'role:Super Admin']);

    Route::resource('salary', SalaryController::class)->middleware(['auth', 'role:Super Admin']);

    Route::resource('projects', ProjectController::class)->middleware(['auth', 'role:Super Admin']);
    Route::get('/projects/payments/{project}', [ProjectController::class, 'payments'])->name('projects.payments')->middleware(['auth', 'role:Super Admin']);


    Route::resource('clients', ClientController::class)->middleware(['auth', 'role:Super Admin']);

    Route::resource('cost-categories', CostCategoryController::class)->middleware(['auth', 'role:Super Admin']);

    Route::resource('project-costs', ProjectCostController::class)->middleware(['auth', 'role:Super Admin']);

    Route::resource('project-items', ProjectItemController::class)->middleware(['auth', 'role:Super Admin']);

Route::prefix('bills')->middleware(['auth', 'role:Super Admin'])->group(function () {
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

Route::resource('challans', ChallanController::class)->middleware(['auth', 'role:Super Admin']);
Route::get('/challans/{challan}/download', [ChallanController::class, 'download'])->name('challans.download');
Route::get('/get-sales', [ChallanController::class, 'getSales'])->name('challans.get-sales');
Route::get('/get-projects', [ChallanController::class, 'getProjects'])->name('challans.get-projects');

Route::resource('quotations', QuotationController::class)->middleware(['auth', 'role:Super Admin']);
Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'generatePDF'])->name('quotations.pdf');
Route::get('/quotations/{quotation}/download', [QuotationController::class, 'download'])->name('quotations.download');
Route::post('quotations/{quotation}/send', [QuotationController::class, 'sendQuotation'])->name('quotations.send');

// Bill generation routes
Route::get('/projects/{project}/bills/create', [ProjectBillController::class, 'createBill'])->name('projects.bills.create');
Route::post('/projects/{project}/bills/', [ProjectBillController::class, 'storeBill'])->name('projects.bills.store');

});

Route::middleware(['web'])->group(
    function () {
        Route::get('/sales/search-orders', [SalesController::class, 'searchOrders'])->name('sales.search-orders');
        Route::post('/sales/process-payment', [SalesController::class, 'processPayment'])->name('sales.process-payment');
        Route::post('/projects/process-payment', [ProjectController::class, 'processPayment'])->name('projects.process-payment');
    }
);

Route::prefix('employee')->middleware(['auth', 'role:Employee'])->group(function () {
    Route::get('tada', [EmployeeTaDaController::class, 'index'])->name('employee.tada.index');
    Route::get('tada/create', [EmployeeTaDaController::class, 'create'])->name('employee.tada.create');
    Route::post('tada/store', [EmployeeTaDaController::class, 'store'])->name('employee.tada.store');
    Route::get('tada/{id}/edit', [EmployeeTaDaController::class, 'edit'])->name('employee.tada.edit');
    Route::put('tada/{id}', [EmployeeTaDaController::class, 'update'])->name('employee.tada.update');
});

Route::post('/salary/get-tada-data-ajax', [SalaryController::class, 'getTaDaDataAjax'])->name('salary.get-tada-data-ajax');
Route::get('/employee/{id}/advance-sum-by-month', [EmployeeController::class, 'getAdvanceSumByMonth']);

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);