<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::latest()->get();
        $products = Product::with('latestPurchase')->latest()->get();
        $vendors = Vendor::latest()->get();
        return view('frontend.pages.purchase.index', compact('purchases', 'products', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'quantity' => 'required|integer|min:1',
    //         'unit_price' => 'required|numeric|min:0',
    //         'sub_price' => 'nullable|numeric|min:0',
    //         'total_price' => 'required|numeric|min:0',
    //         'payment' => 'nullable|numeric|min:0',
    //         'vendor_id' => 'required|exists:vendors,id',
    //         'serials' => 'required|array|min:1',
    //         'serials.*' => 'required|string|distinct|unique:inventory_items,serial_number',
    //     ]);

    //     DB::transaction(function () use ($request) {
    //         $subPrice = $request->sub_price ?? ($request->quantity * $request->unit_price);
    //         $payment = $request->payment ?? 0;
    //         $due = max(0, $request->total_price - $payment);

    //         // 1. Save purchase header
    //         $purchase = Purchase::create([
    //             'product_id' => $request->product_id,
    //             'vendor_id' => $request->vendor_id,
    //             'quantity' => $request->quantity,
    //             'unit_price' => $request->unit_price,
    //             'sub_price' => $subPrice,
    //             'total_price' => $request->total_price,
    //             'payment' => $payment,
    //             'due' => $due,
    //             'created_by' => auth()->id(),
    //         ]);

    //         // 2. Save each unit to inventory_items
    //         foreach ($request->serials as $serial) {
    //             InventoryItem::create([
    //                 'product_id' => $request->product_id,
    //                 'purchase_id' => $purchase->id,
    //                 'barcode_data' => $request->barcodeInput ?? null,   // optional
    //                 'serial_number' => $serial,
    //                 'unit_status' => 'in_stock',
    //                 'purchase_date' => now(),
    //             ]);
    //         }

    //         // 3. Increase stock
    //         Inventory::updateOrCreate(
    //             ['product_id' => $request->product_id],
    //             ['current_stock' => DB::raw('current_stock + ' . $request->quantity)]
    //         );
    //     });

    //     return response()->json(['message' => 'Purchase saved successfully!']);
    // }
    
      public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'sub_price' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'payment' => 'nullable|numeric|min:0',
            'vendor_id' => 'required|exists:vendors,id',
            'serials' => 'nullable|array',
            'serials.*' => 'nullable|string|distinct|unique:inventory_items,serial_number',
            'barcodes' => 'nullable|array',
            'barcodes.*' => 'nullable|string|distinct|unique:inventory_items,barcode_data',
        ]);

        DB::transaction(function () use ($request) {
            $subPrice = $request->sub_price ?? ($request->quantity * $request->unit_price);
            $payment = $request->payment ?? 0;
            $due = max(0, $request->total_price - $payment);

            // 1. Save purchase header
            $purchase = Purchase::create([
                'product_id' => $request->product_id,
                'vendor_id' => $request->vendor_id,
                'quantity' => $request->quantity,
                'unit_price' => $request->unit_price,
                'sub_price' => $subPrice,
                'total_price' => $request->total_price,
                'payment' => $payment,
                'due' => $due,
                'created_by' => auth()->id(),
            ]);

            // 2. Save each unit to inventory_items
            $serials = $request->serials ?? [];
            $barcodes = $request->barcodes ?? [];

            for ($i = 0; $i < $request->quantity; $i++) {
                $serial = $serials[$i] ?? null;
                $barcode = $barcodes[$i] ?? null;

                InventoryItem::create([
                    'product_id' => $request->product_id,
                    'purchase_id' => $purchase->id,
                    'barcode_data' => $barcode,
                    'serial_number' => $serial,
                    'unit_status' => 'in_stock',
                    'purchase_date' => now(),
                ]);
            }

            // 3. Increase stock
            Inventory::updateOrCreate(
                ['product_id' => $request->product_id],
                ['current_stock' => DB::raw('current_stock + ' . $request->quantity)]
            );
        });

        return response()->json(['message' => 'Purchase saved successfully!']);
    }

    private function generateBarcodeData($product, $purchaseId, $index)
    {
        return $product->id . '-' . $purchaseId . '-' . time() . '-' . $index;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
            'sub_price' => 'nullable|numeric',
            'total_price' => 'required|numeric|min:0',
            'payment' => 'required|numeric|min:0',
            'due' => 'required|numeric|min:0',
            'vendor_id' => 'required|exists:vendors,id',
        ]);



        $purchase = Purchase::findOrFail($purchase->id);
        $inventory = Inventory::where('product_id', $purchase->product_id)->first();
        if ($inventory) {
            $inventory->current_stock -= $purchase->quantity;
            $inventory->current_stock += $request->quantity;
            $inventory->update();
        } else {
            $newInventory = new Inventory();
            $newInventory->product_id = $request->product_id;
            $newInventory->current_stock = $request->quantity;
            $newInventory->opening_stock = $request->quantity;
            $newInventory->notes = 'Opening stock entry';
            $newInventory->save();
        }
        $purchase->product_id = $request->product_id;
        $purchase->quantity = $request->quantity;
        $purchase->unit_price = $request->unit_price;
        $purchase->sub_price = $request->sub_price ?? ($request->quantity * $request->unit_price);
        $purchase->total_price = $request->total_price;
        $purchase->payment = $request->payment;
        $purchase->due = $request->due;
        $purchase->vendor_id = $request->vendor_id;
        $purchase->updated_by = Auth::id();

        $purchase->update();

        return redirect()->back()->with('success', 'Purchase updated and inventory adjusted successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->back()->with('success', 'Purchase deleted successfully.');
    }

    public function getLatestPrice($id)
    {
        $product = Product::with('latestPurchase')->find($id);

        if (!$product) {
            return response()->json(['price' => 0]);
        }

        $price = $product->latestPurchase ? $product->latestPurchase->unit_price : 0;

        return response()->json(['price' => $price]);
    }




    public function reportIndex(Request $request)
    {
        $query = Purchase::query();

        // Apply filters if provided
        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->filled('item_name')) {
            $query->where('product_id', $request->item_name);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $purchases = $query
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_amount')
            ->groupBy('product_id')
            ->with('product')
            ->get();

        $products = Product::with('brand')->latest()->get();
        $vendors = Vendor::latest()->get();

        return view('frontend.pages.report.purchase.index', compact('purchases', 'products', 'vendors'));
    }


    public function report(Request $request)
    {
        $request->all(); // For debugging purposes, you can remove this later
        $query = Purchase::query();

        // Apply filters
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('item_name')) {
            $query->where('product_id', $request->item_name);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        // Group by product to get item-wise purchase data
        $purchases = $query
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_amount')
            ->groupBy('product_id')
            ->with('product') // Eager load product details
            ->get();

        $products = Product::latest()->get();
        $vendors = Vendor::latest()->get();

        return view('frontend.pages.report.purchase.index', compact('purchases', 'products', 'vendors', 'request'));
    }

    public function downloadPurchaseReport(Request $request)
    {
        $query = Purchase::query();

        if ($request->filled('vendor')) {
            $query->where('vendor_id', $request->vendor);
        }

        if ($request->filled('item_name')) {
            $query->where('product_id', $request->item_name);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $purchases = $query
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_amount')
            ->groupBy('product_id')
            ->with('product')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('frontend.pages.report.purchase.pdf', compact('purchases'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('Purchase_Report_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}


