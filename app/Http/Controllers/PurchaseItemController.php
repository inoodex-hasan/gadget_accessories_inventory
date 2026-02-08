<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseItemController extends Controller
{
    // 1. Show the receive-stock page
    public function index()
    {
        $products = \App\Models\Product::where('status', 1)->orderBy('name')->get();
        $vendors  = \App\Models\Vendor::orderBy('name')->get(); // adjust if your model name is different

        return view('frontend.pages.purchase.receive-stock', compact('products', 'vendors'));
    }

    // 2. Save all scanned units
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'items'     => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.barcode'    => 'required|string',
            'items.*.serial'     => 'nullable|string',
            'items.*.price'      => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalAmount = collect($request->items)->sum('price');

            // Create the header in your existing purchases table
            $purchase = Purchase::create([
                'vendor_id'     => $request->vendor_id,
                'quantity'      => count($request->items),
                'unit_price'    => $request->items[0]['price'],
                'sub_price'     => $totalAmount,
                'total_price'   => $totalAmount,
                'payment'       => 0,
                'due'           => $totalAmount,
                'created_by'    => auth()->id() ?? 1,
                // 'product_id'  => remove this line if you don't want it in header
            ]);

            foreach ($request->items as $item) {
                // Save each physical unit with its barcode + serial
                PurchaseItem::create([
                    'purchase_id'    => $purchase->id,
                    'product_id'     => $item['product_id'],
                    'barcode_data'   => $item['barcode'],
                    'serial_number'  => $item['serial'],
                    'unit_price'     => $item['price'],
                ]);

                // Increase current stock
                Inventory::updateOrCreate(
                    ['product_id' => $item['product_id']],
                    ['current_stock' => DB::raw('COALESCE(current_stock, 0) + 1')]
                );
            }
        });

        return response()->json(['success' => true, 'message' => count($request->items) . ' units received!']);
    }
}