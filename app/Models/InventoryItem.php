<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Milon\Barcode\DNS1D;

class InventoryItem extends Model
{
    protected $fillable = [
        'product_id',
        'serial_number',
        'barcode_data',
        'unit_status',
        'purchase_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'product_id', 'product_id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventoryItem) {
            // Auto-generation disabled to allow null values
            // // Generate serial number if empty
            // if (empty($inventoryItem->serial_number)) {
            //     $inventoryItem->serial_number = self::generateSerialNumber();
            // }

            // // Generate barcode data if empty
            // if (empty($inventoryItem->barcode_data)) {
            //     $inventoryItem->barcode_data = self::generateBarcodeData();
            // }
        });
    }

    // Generate unique serial number
    private static function generateSerialNumber()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return $prefix . '-' . $date . '-' . $random;
    }

    // Generate barcode data
    private static function generateBarcodeData()
    {
        return 'ITM-' . time() . '-' . rand(1000, 9999);
    }

    // Status options
    public static function statusOptions()
    {
        return [
            'available' => 'Available',
            'in_use' => 'In Use',
            'under_maintenance' => 'Under Maintenance',
            'retired' => 'Retired',
            'lost' => 'Lost',
            'sold' => 'Sold'
        ];
    }

    // Generate barcode HTML (simple method without Service class)
    public function getBarcodeHtmlAttribute()
    {
        if (empty($this->barcode_data))
            return '';
        $dns1d = new DNS1D();
        return $dns1d->getBarcodeHTML($this->barcode_data, 'C128');
    }

    // Generate QR code HTML
    public function getQrCodeHtmlAttribute()
    {
        if (empty($this->barcode_data))
            return '';
        $dns2d = new \Milon\Barcode\DNS2D();
        return $dns2d->getBarcodeHTML($this->barcode_data, 'QRCODE');
    }

    // Generate SVG barcode
    public function getBarcodeSvgAttribute()
    {
        if (empty($this->barcode_data))
            return '';
        $dns1d = new DNS1D();
        return $dns1d->getBarcodeSVG($this->barcode_data, 'C128', 2, 60);
    }

    // Generate PNG barcode (base64)
    public function getBarcodePngAttribute()
    {
        if (empty($this->barcode_data))
            return '';
        $dns1d = new DNS1D();
        return $dns1d->getBarcodePNG($this->barcode_data, 'C128', 2, 60);
    }
}