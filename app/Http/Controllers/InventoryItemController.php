<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryItemController extends Controller
{
    protected $dns1d;
    protected $dns2d;

    public function __construct()
    {
        $this->dns1d = new DNS1D();
        $this->dns2d = new DNS2D();

        // Optional: improve barcode appearance
        $this->dns1d->setStorPath(storage_path('app/barcodes'));
    }

    public function index(Request $request)
{
    $query = InventoryItem::query()->with('product');

    // Search by serial number or barcode
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('serial_number', 'LIKE', "%{$search}%")
              ->orWhere('barcode_data', 'LIKE', "%{$search}%");
        });
    }

    // Filter by status
    if ($request->filled('status')) {
        $query->where('unit_status', $request->status);
    }

    // Filter by purchase date range
    if ($request->filled('date_from')) {
        $query->whereDate('purchase_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('purchase_date', '<=', $request->date_to);
    }

    // Order by latest first
    $query->orderBy('purchase_date', 'desc');

    // Paginate results
    $items = $query->paginate(25); 

    // This preserves all filters in pagination links
    $items->appends($request->query());

    return view('frontend.pages.inventory-items.index', compact('items'));
}

    // public function index(Request $request)
    // {
    //     // Start query with relationships
    //     $query = InventoryItem::with(['product', 'purchase']);
        
    //     // Search functionality
    //     if ($request->has('search') && !empty($request->search)) {
    //         $search = $request->search;
    //         $query->where(function($q) use ($search) {
    //             $q->where('serial_number', 'LIKE', "%{$search}%")
    //               ->orWhere('barcode_data', 'LIKE', "%{$search}%")
    //               ->orWhereHas('product', function($q) use ($search) {
    //                   $q->where('name', 'LIKE', "%{$search}%");
    //               });
    //         });
    //     }
        
    //     // Filter by product
    //     if ($request->has('product_id') && !empty($request->product_id)) {
    //         $query->where('product_id', $request->product_id);
    //     }
        
    //     // Filter by status
    //     if ($request->has('unit_status') && !empty($request->unit_status)) {
    //         $query->where('unit_status', $request->unit_status);
    //     }
        
    //     // Filter by purchase
    //     if ($request->has('purchase_id') && !empty($request->purchase_id)) {
    //         $query->where('purchase_id', $request->purchase_id);
    //     }
        
    //     // Date range filter
    //     if ($request->has('date_from') && !empty($request->date_from)) {
    //         $query->whereDate('purchase_date', '>=', $request->date_from);
    //     }
        
    //     if ($request->has('date_to') && !empty($request->date_to)) {
    //         $query->whereDate('purchase_date', '<=', $request->date_to);
    //     }
        
    //     // Order results
    //     $query->orderBy('created_at', 'desc');
        
    //     // Get paginated results
    //     $items = $query->paginate(20);
        
    //     // For filters dropdowns
    //     $products = Product::orderBy('name')->get();
    //     $purchases = Purchase::latest()->take(50)->get();
    //     $statusOptions = InventoryItem::statusOptions();
        
    //     return view('frontend.pages.inventory-items.index', compact('items', 'products', 'purchases', 'statusOptions'));
    // }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'serial_number' => 'nullable|unique:inventory_items,serial_number',
            'barcode_data' => 'nullable|unique:inventory_items,barcode_data',
            'unit_status' => 'required|in:available,in_use,under_maintenance,retired,lost,sold',
            'purchase_date' => 'required|date',
        ]);

        // Auto-generate if empty
        if (empty($request->serial_number)) {
            $request->merge(['serial_number' => 'SN-' . date('Ymd') . '-' . strtoupper(uniqid())]);
        }
        
        if (empty($request->barcode_data)) {
            $request->merge(['barcode_data' => 'BC-' . time() . '-' . rand(10000, 99999)]);
        }

        $item = InventoryItem::create([
            'product_id' => $request->product_id,
            'serial_number' => $request->serial_number,
            'barcode_data' => $request->barcode_data,
            'unit_status' => $request->unit_status,
            'purchase_date' => $request->purchase_date,
        ]);

        // Generate barcode after creation
        $this->generateBarcodeImage($item);

        return redirect()->route('inventory-items.show', $item->id)
            ->with('success', 'Inventory item added with barcode.');
    }

    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'serial_number' => 'required|unique:inventory_items,serial_number,' . $id,
            'barcode_data' => 'required|unique:inventory_items,barcode_data,' . $id,
            'unit_status' => 'required|in:available,in_use,under_maintenance,retired,lost,sold',
            'purchase_date' => 'required|date',
        ]);

        $item->update($request->all());

        return redirect()->route('inventory-items.show', $id)
            ->with('success', 'Inventory item updated.');
    }

    public function show($id)
    {
        $item = InventoryItem::with('product')->findOrFail($id);
        
        $barcode = [
            '1d' => $this->dns1d->getBarcodeHTML($item->barcode_data, 'C128'),
            'qr' => $this->dns2d->getBarcodeHTML($item->barcode_data, 'QRCODE'),
            'svg' => $this->dns1d->getBarcodeSVG($item->barcode_data, 'C128'),
        ];
        
        return view('frontend.pages.inventory-items.show', compact('item', 'barcode'));
    }

    // public function scan()
    // {
    //     return view('inventory-items.scan');
    // }

    // public function processScan(Request $request)
    // {
    //     $request->validate([
    //         'barcode' => 'required|string'
    //     ]);

    //     $item = InventoryItem::where('barcode_data', $request->barcode)
    //         ->orWhere('serial_number', $request->barcode)
    //         ->with('product')
    //         ->first();

    //     if ($item) {
    //         return response()->json([
    //             'success' => true,
    //             'item' => $item,
    //             'barcode_image' => $this->dns1d->getBarcodeHTML($item->barcode_data, 'C128')
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Item not found'
    //     ], 404);
    // }

    public function downloadBarcode($id)
    {
        $item = InventoryItem::findOrFail($id);
        $svg = $this->dns1d->getBarcodeSVG($item->barcode_data, 'C128', 2, 60);
        
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="barcode_' . $item->serial_number . '.svg"');
    }

    // Print barcode label
    public function printBarcode($id)
    {
        $item = InventoryItem::findOrFail($id);
        
        $barcode = [
            '1d' => $this->dns1d->getBarcodeHTML($item->barcode_data, 'C128'),
            'qr' => $this->dns2d->getBarcodeHTML($item->barcode_data, 'QRCODE'),
        ];
        
        return view('frontend.pages.inventory-items.print', compact('item', 'barcode'));
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:inventory_items,id'
        ]);

        $items = InventoryItem::whereIn('id', $request->item_ids)->get();
        
        return view('frontend.pages.inventory-items.bulk-print', compact('items'));
    }

    private function generateBarcodeImage($item)
    {
        return $this->dns1d->getBarcodeHTML($item->barcode_data, 'C128');
    }

public function bulkDownloadPdf(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:inventory_items,id'
        ]);

        $items = InventoryItem::whereIn('id', $request->item_ids)->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No items selected.');
        }

        $barcodes = [];
        foreach ($items as $item) {
            $barcodes[] = [
                // Use the instance from constructor → NO STATIC ERROR
                'barcode'      => $this->dns1d->getBarcodeHTML($item->barcode_data, 'C128', 2.5, 62),
                'barcode_data' => $item->barcode_data
            ];
        }

        $pdf = Pdf::loadView('pdf.barcode', compact('barcodes'))
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'defaultFont'       => 'DejaVu Sans',
                'isRemoteEnabled'   => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled'      => true,
                'dpi'               => 300,
            ]);

        $filename = 'barcodes_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

public function destroy($id)
{
    $item = InventoryItem::findOrFail($id);
    $item->delete();
    
    return redirect()->route('inventory-items.index')
        ->with('success', 'Item deleted successfully.');
}

}