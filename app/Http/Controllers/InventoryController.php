<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productIdsInInventory = Inventory::pluck('product_id')->toArray();
        $products = Product::whereNotIn('id', $productIdsInInventory)
                        ->latest()
                        ->get();
         $inventories = Inventory::with(['product', 'inventory_items'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        return view('frontend.pages.inventory.index', compact('products','inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
       $request->validate([
            'product_id' => 'required|exists:products,id',
            'opening_stock' => 'required|integer|min:0',
        ]);

        $inventory = new Inventory();
        $inventory->product_id = $request->product_id;
        $inventory->opening_stock = $request->opening_stock;
        $inventory->current_stock = $request->opening_stock;
        $inventory->notes = 'Opening stock entry';
        $inventory->save();

        return redirect()->back()->with('success', 'Product with opening stock added successfully.');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

   public function scanBarcode(Request $request)
{
    $request->validate(['barcode' => 'required|string']);
    $barcode = $request->barcode;

    $existing = InventoryItem::where('barcode_data', $barcode)->first();
    if ($existing) {
        return response()->json([
            'success' => false,
            'message' => 'This item has already been scanned!',
            'product' => $existing->product->name ?? 'Unknown'
        ], 409);
    }


    $productCode = substr($barcode, 0, 8);
    $product = Product::where('code', $productCode)->first();


    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not recognized'], 404);
    }

    DB::transaction(function () use ($product, $barcode) {
        Inventory::updateOrCreate(
            ['product_id' => $product->id],
            ['current_stock' => DB::raw('current_stock + 1')]
        );

        InventoryItem::create([
            'product_id'    => $product->id,
            'barcode_data'  => $barcode,
            'serial_number' => $barcode,
            'unit_status'   => 'in_stock',
            'purchase_date' => now(),
        ]);
    });

    return response()->json([
        'success' => true,
        'message' => "{$product->name} added to inventory",
        'current_stock' => $product->inventory->refresh()->current_stock
    ]);
}

public function checkDuplicate(Request $request)
    {
        $exists = InventoryItem::where(function ($q) use ($request) {
            if ($request->filled('barcode_data')) {
                $q->where('barcode_data', $request->barcode_data);
            }
            if ($request->filled('serial_number')) {
                $q->orWhere('serial_number', $request->serial_number);
            }
        })->first();

        if ($exists) {
            return response()->json([
                'exists' => true,
                'message' => 'Already in inventory',
                'product'  => $exists->product->name . ' ' . ($exists->product->model ?? '')
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function receiveByScan(Request $request)
    {
        $items = $request->validate([
            'items' => 'required|array',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.barcode_data'   => 'nullable|string',
            'items.*.serial_number'  => 'nullable|string',
            'items.*.purchase_date'  => 'required|date',
        ])['items'];

        $savedCount = 0;

        DB::transaction(function () use ($items, &$savedCount) {
            foreach ($items as $item) {
                $productId = $item['product_id'];

                // 1. Increase current_stock in inventory table
                $inventory = Inventory::firstOrCreate(
                    ['product_id' => $productId],
                    [
                        'opening_stock' => 0,
                        'current_stock' => 0,
                        'notes' => 'Auto-created on first receive'
                    ]
                );

                $inventory->increment('current_stock');
                // Optional: only increase opening_stock on first ever receive
                if ($inventory->wasRecentlyCreated) {
                    $inventory->opening_stock = 1;
                    $inventory->save();
                }

                // 2. Save the individual unit
                InventoryItem::create([
                    'product_id'     => $productId,
                    'barcode_data'   => $item['barcode_data'] ?? null,
                    'serial_number'  => $item['serial_number'] ?? null,
                    'unit_status'    => 'in_stock',
                    'purchase_date'  => $item['purchase_date'],
                ]);

                $savedCount++;
            }
        });

        return response()->json([
            'success' => true,
            'message' => "$savedCount item(s) received successfully!",
            'count'   => $savedCount
        ]);
    }

}
