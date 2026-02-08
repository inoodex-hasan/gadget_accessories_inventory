<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Milon\Barcode\Facades\DNS1DFacade;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('frontend.pages.barcode.scanner');
    }

    // Process scanned barcode
    public function processScan(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|min:8'
        ]);

        $barcode = $request->barcode;
        
        // Clean barcode (remove any non-numeric except EAN-13, UPC-A, etc.)
        $barcode = preg_replace('/[^0-9]/', '', $barcode);
        
        // Validate barcode format
        if (!$this->validateBarcode($barcode)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid barcode format'
            ], 400);
        }

        // Check if product exists
        $product = Product::where('barcode', $barcode)->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'exists' => true,
                'product' => $product,
                'message' => 'Product found in database'
            ]);
        }

        // New product - prepare data
        return response()->json([
            'success' => true,
            'exists' => false,
            'barcode' => $barcode,
            'message' => 'New product detected'
        ]);
    }

    // Generate barcode image
    public function generate($barcode, $type = 'C128')
    {
        $validTypes = ['C128', 'C128A', 'C128B', 'C128C', 'EAN13', 'EAN8', 'UPCA', 'UPCE', 'ISBN', 'ISBN13'];
        
        if (!in_array($type, $validTypes)) {
            $type = 'C128';
        }

        try {
            $image = DNS1DFacade::getBarcodeHTML($barcode, $type);
            return response($image)->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            return response('Error generating barcode', 500);
        }
    }

    // Generate barcode PNG
    public function generatePNG($barcode)
    {
        $generator = new \Milon\Barcode\DNS1D();
        $image = $generator->getBarcodePNG($barcode, 'C128', 2, 60, array(0,0,0), true);
        
        return response(base64_decode($image))
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'inline; filename="barcode.png"');
    }

    // Generate barcode for product label
    public function generateLabel($productId)
    {
        $product = Product::findOrFail($productId);
        
        return view('barcode.label', [
            'product' => $product,
            'barcode' => DNS1DFacade::getBarcodeHTML($product->barcode, 'C128')
        ]);
    }

    // Bulk generate barcodes
    public function bulkGenerate(Request $request)
    {
        $products = Product::whereIn('id', $request->product_ids)->get();
        
        $barcodes = [];
        foreach ($products as $product) {
            $barcodes[] = [
                'product' => $product,
                'barcode_html' => DNS1DFacade::getBarcodeHTML($product->barcode, 'C128')
            ];
        }
        
        return view('barcode.bulk-print', compact('barcodes'));
    }

    // Validate barcode checksum
    private function validateBarcode($barcode)
    {
        $length = strlen($barcode);
        
        // Common barcode lengths
        if (!in_array($length, [8, 12, 13, 14])) {
            return false;
        }

        // EAN-13 validation
        if ($length === 13) {
            return $this->validateEAN13($barcode);
        }

        // UPC-A validation (12 digits)
        if ($length === 12) {
            return $this->validateUPCA($barcode);
        }

        return true; // Accept other lengths for now
    }

    private function validateEAN13($barcode)
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int)$barcode[$i] * ($i % 2 == 0 ? 1 : 3);
        }
        $checksum = (10 - ($sum % 10)) % 10;
        
        return $checksum == $barcode[12];
    }

    private function validateUPCA($barcode)
    {
        $sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $sum += (int)$barcode[$i] * ($i % 2 == 0 ? 3 : 1);
        }
        $checksum = (10 - ($sum % 10)) % 10;
        
        return $checksum == $barcode[11];
    }
}