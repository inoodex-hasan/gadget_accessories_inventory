<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log, Mail};
use App\Models\{Customer, CompanyDetail, DailySale, Inventory, Payment, Product, Project, Sale, SalesItem, Service, User};
use App\Mail\CreateSalesMail;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Twilio\Rest\Client;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'client', 'salesBy']);

        if ($request->from != "" && $request->to != "") {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $query = $query->whereBetween('sales.created_at', [$from, $to]);
        }

        if ($request->search_by == 'order_no' && $request->key != "") {
            $query = $query->where('sales.order_no', 'like', '%' . $request->key . '%');
        }

        if (in_array($request->search_by, ['name', 'phone', 'email']) && $request->key != "") {
            $query = $query->whereHas('customer', function ($subQuery) use ($request) {
                $subQuery->where($request->search_by, 'like', '%' . $request->key . '%');
            });
        }

        $services = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();


        $users = lib_salesMan();
        if ($request->search_for == 'pdf') {
            // return view('pdf.sales',compact('services','request'));
            $pdf = Pdf::loadView('pdf.sales', compact('services', 'request'))
                ->setPaper('A4', 'portrait');
            return $pdf->download('Sales.pdf');
        }


        //Report
        $todaysRevenue = Service::whereDate('created_at', Carbon::today())->where('status', '1')->sum('bill');
        $thisWeeksRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status', '1')->sum('bill');
        $thisMonthsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status', '1')->sum('bill');
        $thisYearsRevenue = Service::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status', '1')->sum('bill');
        $totalServiceDues = Service::where('status', '1')->where('due_amount', '>', 0)->sum('due_amount');

        $todaysSalesRevenue = Sale::whereDate('created_at', Carbon::today())->where('status', '1')->sum('bill');
        $thisWeeksSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status', '1')->sum('bill');
        $thisMonthsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status', '1')->sum('bill');
        $thisYearsSalesRevenue = Sale::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status', '1')->sum('bill');
        $totalSalesDues = 0;

        $todaysDailySalesRevenue = DailySale::whereDate('date', Carbon::today())->where('status', '1')->sum('total_amount');
        $thisWeeksDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('status', '1')->sum('total_amount');
        $thisMonthsDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->where('status', '1')->sum('total_amount');
        $thisYearsDailySalesRevenue = DailySale::whereBetween('date', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->where('status', '1')->sum('total_amount');

        $monthlyRevenue = Service::selectRaw('MONTH(created_at) as month, SUM(bill) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', '1')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->mapWithKeys(function ($total, $month) {
                $monthName = Carbon::createFromFormat('m', $month)->format('M');
                return [$monthName => $total];
            });

        $yearlyRevenue = Service::selectRaw('YEAR(created_at) as year, SUM(bill) as total')
            ->whereRaw('YEAR(created_at) >= YEAR(CURDATE()) - 9')
            ->where('status', '1')
            ->groupBy('year')
            ->pluck('total', 'year');


        return view('frontend.pages.sales.index', compact('services', 'request', 'users', 'todaysRevenue', 'thisWeeksRevenue', 'thisMonthsRevenue', 'thisYearsRevenue', 'monthlyRevenue', 'yearlyRevenue', 'todaysSalesRevenue', 'thisWeeksSalesRevenue', 'thisMonthsSalesRevenue', 'thisYearsSalesRevenue', 'totalServiceDues', 'totalSalesDues', 'todaysDailySalesRevenue', 'thisWeeksDailySalesRevenue', 'thisMonthsDailySalesRevenue', 'thisYearsDailySalesRevenue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::get();
        $products = Product::with('latestPurchase')->where('status', '1')->get();
        $existingClients = Customer::select('id', 'name', 'phone', 'address')->get();
        return view('frontend.pages.sales.create', compact('products', 'users', 'existingClients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'client_type' => 'required|in:new,existing',
            'existing_client_id' => 'nullable|required_if:client_type,existing|exists:customers,id',
            'name' => 'nullable|required_if:client_type,new|string',
            'phone' => 'nullable|required_if:client_type,new|string',
            'address' => 'nullable|required_if:client_type,new|string',
            'product' => 'required|array',
            'product.*' => 'required|integer|exists:products,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:1',
            'subTotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'grandTotal' => 'required|numeric|min:0',
            'advanced_payment' => 'nullable|numeric|min:0',
            'duePayment' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Handle customer logic based on client_type
            if ($validated['client_type'] === 'new') {
                // Create new customer
                $customer = Customer::create([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                ]);
            } else {
                // Use existing customer
                $customer = Customer::findOrFail($validated['existing_client_id']);
            }

            // Get warranties for products
            $warranties = Product::whereIn('id', $validated['product'])->pluck('warranty', 'id');

            // Calculate total bill from form data
            $totalBill = $validated['subTotal']; // Use the calculated subtotal from form

            // Calculate discount (ensure it's not greater than total bill)
            $discount = $validated['discount'] ?? 0;
            if ($discount > $totalBill) {
                $discount = $totalBill;
            }

            // Calculate payable amount
            $payble = $validated['grandTotal']; // Use grand total from form

            // Handle payments
            $advancedPayment = $validated['advanced_payment'] ?? 0;
            $duePayment = $validated['duePayment'] ?? 0;

            // Ensure advanced payment doesn't exceed payable amount
            if ($advancedPayment > $payble) {
                $advancedPayment = $payble;
                $duePayment = 0;
            }

            // Determine payment status
            if ($duePayment <= 0) {
                $status = 'paid';
            } elseif ($advancedPayment > 0) {
                $status = 'partial';
            } else {
                $status = 'credit';
            }

            // Generate invoice number using your existing format
            $invoiceNumber = 'INV-' . strtoupper(uniqid());

            // Create sale record
            $sale = Sale::create([
                'order_no' => $invoiceNumber,
                'customer_id' => $customer->id,
                'product_id' => $validated['product'][0], // First product as main product
                'qty' => array_sum($validated['qty']), // Total quantity
                'total' => $totalBill,
                'payble' => $payble,
                'bill' => $totalBill,
                'discount' => $discount,
                'advanced_payment' => $advancedPayment,
                'due_payment' => $duePayment,
                'sales_by' => auth()->id(),
                'status' => $status,
            ]);

            // Create sale items and update inventory
            $totalSalePrice = 0;

            foreach ($validated['product'] as $index => $productId) {
                $qty = $validated['qty'][$index];
                $unitPrice = $validated['unit_price'][$index];
                $total = $unitPrice * $qty;
                $totalSalePrice += $total;

                // Get purchase price for profit calculation
                $product = Product::find($productId);
                $purchasePrice = 0;
                if ($product->latestPurchase) {
                    $purchasePrice = $product->latestPurchase->unit_price ?? 0;
                }

                // Calculate profit for this item
                $profitPerUnit = $unitPrice - $purchasePrice;
                $itemProfit = $profitPerUnit * $qty;

                // Create Sale Item with warranty
                SalesItem::create([
                    'order_id' => $sale->id,
                    'product_id' => $productId,
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'total_price' => $total,
                    'warranty' => $warranties[$productId] ?? 0,
                    'purchase_price' => $purchasePrice,
                    'profit' => $itemProfit,
                ]);

                // Update inventory
                $inventory = Inventory::where('product_id', $productId)->first();
                if ($inventory) {
                    // Check stock availability
                    if ($inventory->current_stock < $qty) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$inventory->current_stock}, Requested: {$qty}");
                    }

                    $inventory->decrement('current_stock', $qty);
                } else {
                    throw new \Exception("Inventory not found for product ID: {$productId}");
                }
            }


            DB::commit();

            // Return to invoice page
            return redirect()->route('sales.invoice', $sale->id)
                ->with('success', 'Sale created successfully! Invoice #' . $invoiceNumber);

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Sale creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with(['error' => 'Failed to create sale: ' . $e->getMessage()]);
        }
    }


    private function getPaymentStatus($advancedPayment, $payble)
    {
        if ($advancedPayment == 0) {
            return 'pending';
        } elseif ($advancedPayment > 0 && $advancedPayment < $payble) {
            return 'partial';
        } elseif ($advancedPayment >= $payble) {
            return 'paid';
        }

        return 'pending';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sales = Sale::join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where('sales.id', $id)
            ->select('sales.*')
            ->first();
        if (!$sales)
            abort(404);
        $users = User::get();
        $products = Product::where('status', '1')->get();

        $customer = Customer::where('id', $sales->customer_id)->first();
        if (!$customer)
            abort(404);

        $items = SalesItem::where('order_id', $sales->id)->get();

        return view('frontend.pages.sales.edit', compact('sales', 'products', 'items', 'customer'));
    }


    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'product' => 'required|array',
            'product.*' => 'required|integer|exists:products,id',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0',
            'advanced_payment' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            // FirstOrCreate customer
            $customer = Customer::firstOrCreate(
                ['name' => $validated['name'], 'phone' => $validated['phone']],
                ['address' => $validated['address'] ?? null]
            );

            // Fetch sale
            $sale = Sale::where('id', $id)->first();
            if (!$sale)
                return redirect()->back()->with(['error' => 'Sale not found.']);

            // Restore old inventory
            $oldItems = SalesItem::where('order_id', $sale->id)->get();
            foreach ($oldItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->current_stock += $item->qty;
                    $inventory->save();
                }
            }

            // Delete old sale items
            SalesItem::where('order_id', $sale->id)->delete();

            // Create new sale items
            $totalBill = 0;
            $warranties = Product::whereIn('id', $validated['product'])->pluck('warranty', 'id');

            foreach ($validated['product'] as $index => $productId) {
                $qty = $validated['qty'][$index];
                $unitPrice = $validated['unit_price'][$index];

                $total = $unitPrice * $qty;
                $totalBill += $total;

                SalesItem::create([
                    'order_id' => $sale->id,
                    'product_id' => $productId,
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'total_price' => $total,
                    'warranty' => $warranties[$productId] ?? 0,
                ]);

                // Deduct new inventory
                $inventory = Inventory::where('product_id', $productId)->first();
                if ($inventory) {
                    $inventory->current_stock -= $qty;
                    $inventory->save();
                }
            }

            // Calculate totals
            $discount = $validated['discount'] ?? 0;
            if ($discount > $totalBill)
                $discount = $totalBill;

            $advancedPayment = $request->advanced_payment ?? 0;
            if ($advancedPayment > ($totalBill - $discount))
                $advancedPayment = $totalBill - $discount;

            $payble = $totalBill - $discount;
            $duePayment = $payble - $advancedPayment;

            // Update sale
            $sale->update([
                'bill' => $totalBill,
                'discount' => $discount,
                'payble' => $payble,
                'advanced_payment' => $advancedPayment,
                'due_payment' => $duePayment,
                'customer_id' => $customer->id,
            ]);

            DB::commit();

            return redirect()->route('sales.index', $sale->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Sale::where('id', $id)->first();
        if (!$service)
            abort(404);
        $service->delete();

        return redirect()->back()->with(['success' => getNotify(3)]);
    }

    public function makeInvoice(Request $request, $serviceId)
    {
        $sales = Sale::where('id', $serviceId)->first();
        if (!$sales)
            abort(404);
        $customer = Customer::where('id', $sales->customer_id)->first();
        if (!$customer)
            abort(404);
        $items = SalesItem::join('products', 'products.id', 'sales_items.product_id')
            ->where('order_id', $sales->id)
            ->select('sales_items.*', 'products.name', 'products.model')
            ->get();

      $companyInfo = CompanyDetail::where('is_default', '1')->where('is_active', '1')->first();



        return view('frontend.pages.sales.invoice', compact('sales', 'items', 'customer', 'companyInfo'));
    }


    public function payments(Request $request, $saleId = null)
    {
        // Base query: only service payments
        $paymentsQuery = Payment::where('payment_for', 2);

        $sale = null;
        if ($saleId) {
            $paymentsQuery->where('sale_id', $saleId);
            $sale = Sale::with('customer')->findOrFail($saleId);
        }

        $defaultFilter = true;

        // Filter by date
        if (!empty($request->from) && !empty($request->to)) {
            $from = date('Y-m-d 00:00:00', strtotime($request->from));
            $to = date('Y-m-d 23:59:59', strtotime($request->to));
            $paymentsQuery->whereBetween('payments.created_at', [$from, $to]);
            $defaultFilter = false;
        }

        // Filter by payment method
        if (!empty($request->payments_method)) {
            $paymentsQuery->where('payments.payment_method_id', $request->payments_method);
            $defaultFilter = false;
        }

        // Default filter: current month if no filters and no sale selected
        if ($defaultFilter && !$saleId) {
            $startOfMonth = date('Y-m-01 00:00:00');
            $endOfMonth = date('Y-m-t 23:59:59');
            $paymentsQuery->whereBetween('payments.created_at', [$startOfMonth, $endOfMonth]);
        }

        $payments = $paymentsQuery->get();

        // PDF export
        // if ($request->search_for === 'pdf') {
        //     $pdf = Pdf::loadView('pdf.service_payments', compact('payments', 'request'))
        //         ->setPaper('A4', 'portrait');
        //     return $pdf->download('service_payments.pdf');
        // }

        return view('frontend.pages.sales.payments', compact('payments', 'request', 'saleId', 'sale'));
    }

    public function report(Request $request)
    {
        $salesQuery = DB::table('sales_items')
            ->join('sales', 'sales.id', '=', 'sales_items.order_id')
            ->join('products', 'products.id', '=', 'sales_items.product_id')
            ->select(
                'products.name as product_name',
                'sales.created_at as sale_date',
                'sales_items.qty',
                'sales_items.unit_price',
                'sales_items.total_price',
            );

        if ($request->filled('item_name')) {
            $salesQuery->where('sales_items.product_id', $request->item_name);
        }

        if ($request->filled('from')) {
            $salesQuery->whereDate('sales.created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $salesQuery->whereDate('sales.created_at', '<=', $request->to);
        }

        $salesReport = $salesQuery->orderBy('sales.created_at', 'desc')->get();

        $products = DB::table('products')->select('id', 'name')->get();

        return view('frontend.pages.report.sales.index', [
            'salesReport' => $salesReport,
            'products' => $products,
            'request' => $request
        ]);
    }

    public function downloadSalesReport(Request $request)
    {
        $salesQuery = DB::table('sales_items')
            ->join('sales', 'sales.id', '=', 'sales_items.order_id')
            ->join('products', 'products.id', '=', 'sales_items.product_id')
            ->select(
                'products.name as product_name',
                'sales.created_at as sale_date',
                'sales_items.qty',
                'sales_items.unit_price',
                'sales_items.total_price',
            );

        if ($request->filled('item_name')) {
            $salesQuery->where('sales_items.product_id', $request->item_name);
        }

        if ($request->filled('from')) {
            $salesQuery->whereDate('sales.created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $salesQuery->whereDate('sales.created_at', '<=', $request->to);
        }

        $salesReport = $salesQuery->orderBy('sales.created_at', 'desc')->get();

        $pdf = Pdf::loadView('frontend.pages.report.sales.pdf', compact('salesReport'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Sales_Report_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    public function getSaleDetails($id)
    {
        // Get sale info with customer info
        $sale = Sale::select(
            'sales.*',
            'customers.name',
            'customers.phone',
            'customers.address'
        )
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where('sales.id', $id)
            ->firstOrFail();

        // Get items for this sale with warranty info
        $items = \DB::table('sales_items')
            ->select(
                'sales_items.*',
                'products.name',
                'products.model',
                'sales_items.warranty',
                'sales_items.unit_price',
                'sales_items.qty',
                'sales_items.total_price'
            )
            ->join('products', 'products.id', '=', 'sales_items.product_id')
            ->where('sales_items.order_id', $id)
            ->get()
            ->map(function ($item) use ($sale) {
                $warrantyStart = \Carbon\Carbon::parse($sale->created_at);
                $warrantyEnd = $warrantyStart->copy()->addDays($item->warranty);
                $daysLeft = $warrantyEnd->isFuture() ? now()->diffInDays($warrantyEnd) : 0;

                $item->warranty_days_left = $daysLeft;
                return $item;
            });

        return response()->json([
            'sale' => $sale,
            'items' => $items,
        ]);
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($request->sale_id);
            $paymentAmount = $request->payment_amount;

            // Check if payment amount exceeds due amount
            if ($paymentAmount > $sale->due_payment) {
                return redirect()->back()->with('error', 'Payment amount cannot exceed due amount!');
            }

            // Store due before payment for record
            $dueBeforePayment = $sale->due_payment;

            // Update sale payment information
            $newAdvancedPayment = $sale->advanced_payment + $paymentAmount;
            $newDuePayment = $sale->payble - $newAdvancedPayment;

            // Determine new payment status
            $paymentStatus = $this->getPaymentStatus($newAdvancedPayment, $sale->payble);

            // Update the sale
            $sale->update([
                'advanced_payment' => $newAdvancedPayment,
                'due_payment' => $newDuePayment,
                'payment_status' => 1,
            ]);

            // Create payment record
            Payment::create([
                'customer_id' => $sale->customer_id,
                'sale_id' => $sale->id,
                'amount' => $paymentAmount,
                'due_before_payment' => $dueBeforePayment,
                'due_after_payment' => $newDuePayment,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date ?: now(),
                'notes' => $request->notes,
                'payment_for' => 2, // Sales
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Payment of ৳' . number_format($paymentAmount, 2) . ' processed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }

    public function duePayments()
    {
        // Retail sales with due payments
        $sales = Sale::with('customer')
            // ->where('sale_type', 'retail')
            ->where('due_payment', '>', 0)
            ->get();

        // Projects with due payments
        // $projects = Project::with('client')
        //     ->where('due_payment', '>', 0)
        //     ->get()
        //     ->map(function ($project) {
        //         $sale = new Sale();
        //         $sale->id = $project->id;
        //         $sale->order_no = 'PRJ-' . $project->id;
        //         $sale->customer = null;
        //         $sale->client = $project->client;
        //         $sale->payble = $project->budget;
        //         $sale->advanced_payment = $project->advanced_payment;
        //         $sale->due_payment = $project->due_payment;
        //         $sale->sale_type = 'project';
        //         $sale->created_at = $project->created_at;
        //         return $sale;
        //     });

        // Merge retail sales and projects
        $allItems = $sales->sortByDesc('created_at');

        return view('frontend.pages.sales.due-payments', ['sales' => $allItems]);
    }


    // private function getPaymentStatus($advancedPayment, $payble)
    // {
    //     if ($advancedPayment == 0) {
    //         return 'pending';
    //     } elseif ($advancedPayment > 0 && $advancedPayment < $payble) {
    //         return 'partial';
    //     } elseif ($advancedPayment >= $payble) {
    //         return 'paid';
    //     }

    //     return 'pending';
    // }
}