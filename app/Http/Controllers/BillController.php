<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Vendor;
use App\Models\BankDetail;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
 public function index(Request $request)
{
    $query = Bill::with(['client', 'vendor', 'project', 'purchase', 'items']);

    // Type filter
    if ($request->has('type') && $request->type != '') {
        $query->where('type', $request->type);
    }
    
    // Date range filter
    if ($request->has('date_from') && $request->date_from != '') {
        $query->whereDate('challan_date', '>=', $request->date_from);
    }
    
    if ($request->has('date_to') && $request->date_to != '') {
        $query->whereDate('challan_date', '<=', $request->date_to);
    }
    
    $bills = $query->latest()->paginate(10);
    // Statistics
    $totalAmount = Bill::sum('total_amount');
    // $paidCount = Bill::where('status', 'paid')->count();
    // $draftCount = Bill::where('status', 'draft')->count();
    // $overdueCount = Bill::where('status', 'overdue')->count();
    // $pendingCount = Bill::whereIn('status', ['draft', 'sent'])->count();

    return view('frontend.pages.bills.index', compact(
        'bills', 
        'totalAmount', 
        // 'paidCount', 
        // 'draftCount', 
        // 'overdueCount', 
        // 'pendingCount'
    ));
}

public function create()
{
    $bankDetails = BankDetail::where('is_active', true)->get();
    $companyDetails = CompanyDetail::where('is_active', true)->get();

    return view('frontend.pages.bills.create', compact('bankDetails', 'companyDetails'));
}

public function getSales()
{
    try {
        // DEBUG: Check what's happening
        // \Log::info('=== DEBUG GETSALES ===');
        
        // // Test 1: Check raw query
        // $testQuery = Sale::where('order_no', 'LIKE', 'INV-%');
        // \Log::info('SQL Query: ' . $testQuery->toSql());
        // \Log::info('Query Bindings: ', $testQuery->getBindings());
        
        // $testResults = $testQuery->get();
        // \Log::info('Test results count: ' . $testResults->count());
        // \Log::info('Test order_nos: ' . $testResults->pluck('order_no')->implode(', '));
        
        // // Test 2: Check all sales in database
        // $allSales = Sale::all();
        // \Log::info('All sales in DB: ' . $allSales->count());
        // \Log::info('All order_nos: ' . $allSales->pluck('order_no')->implode(', '));

        // Your actual query
        $sales = Sale::where('order_no', 'LIKE', 'INV-%')
                    ->with(['customer', 'product']) 
                    ->get()
                    ->map(function($sale) {
                        return [
                            'id' => $sale->id,
                            'order_no' => $sale->order_no,
                            'sale_type' => $sale->sale_type,
                            'date' => $sale->created_at->format('Y-m-d'),
                            'total_amount' => $sale->payble ?? $sale->total,
                            'due_payment' => $sale->due_payment,
                            'status' => $sale->status,
                            'customer' => $sale->customer ? [
                                'id' => $sale->customer->id,
                                'name' => $sale->customer->name ?? 'Unknown',
                                'email' => $sale->customer->email ?? 'N/A',
                                'phone' => $sale->customer->phone ?? 'N/A',
                                'address' => $sale->customer->address ?? 'N/A',
                            ] : null,
                            'items' => [
                                [
                                    'id' => $sale->product_id ?? $sale->id,
                                    'description' => $sale->product->name ?? 'Product #' . $sale->order_no, 
                                    'quantity' => $sale->qty ?? 1,
                                    'unit' => 'Piece', 
                                    'unit_price' => $sale->qty ? ($sale->total / $sale->qty) : $sale->total,
                                    'total' => $sale->total,
                                ]
                            ]
                        ];
                    });

        // \Log::info('Final sales count: ' . $sales->count());
        // \Log::info('=== END DEBUG ===');

        return response()->json(['sales' => $sales]);
    } catch (\Exception $e) {
        \Log::error('Error in getSales: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function getProjects()
{
    try {
        $projects = Project::with(['client', 'projectItems.product']) 
                    ->get()
                    ->map(function($project) {
                        return [
                            'id' => $project->id,
                            'name' => $project->project_name,
                            'reference' => 'PROJ-' . $project->id,
                            'date' => $project->start_date ?? $project->created_at->format('Y-m-d'),
                            'total_amount' => $project->budget,
                            'due_payment' => $project->due_payment,
                            'status' => $project->status,
                            'client' => $project->client ? [
                                'id' => $project->client->id,
                                'name' => $project->client->name ?? 'Unknown',
                                'email' => $project->client->email ?? 'N/A',
                                'phone' => $project->client->phone ?? 'N/A',
                                'address' => $project->client->address ?? 'N/A',
                            ] : null,
                            'items' => $project->projectItems->map(function($item) {
                                $productName = $item->product ? $item->product->name : null;
                                $productDescription = $item->product ? $item->product->description : null;
                                
                                return [
                                    'id' => $item->id,
                                    'description' => $item->description ?? $productDescription ?? $productName ?? 'Project Item',
                                    'quantity' => $item->quantity ?? 1,
                                    'unit' => $item->unit ?? 'Unit',
                                    'unit_price' => $item->unit_price ?? 0,
                                    'total' => $item->total ?? ($item->quantity * $item->unit_price),
                                ];
                            })->toArray()
                        ];
                    });

        return response()->json(['projects' => $projects]);
    } catch (\Exception $e) {
        \Log::error('getProjects Error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function store(Request $request)
{
    $request->validate([
        'bill_type' => 'required|in:sale,project',
        'reference_number' => 'required',
        'bill_date' => 'required|date',
        'selected_sale_id' => 'required_if:bill_type,sale',
        'selected_project_id' => 'required_if:bill_type,project',
        'work_order_number' => 'nullable|string|max:255',
        'items' => 'required|array',
        'total_amount' => 'required|numeric',
        'client_name' => 'required|string|max:255',
        'client_address' => 'required|string',
        'attention_to' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'terms_conditions' => 'required|string',
        'subject' => 'required|string|max:500',
        'bank_detail_id' => 'required|exists:bank_details,id',
        'company_detail_id' => 'required|exists:company_details,id',
    ]);

    $billNumber = 'BILL-' . date('Ymd') . '-' . str_pad(Bill::count() + 1, 4, '0', STR_PAD_LEFT);

    $customerId = null;
    $clientId = null;
    $clientName = null;
    $clientAddress = null;

    // Handle SALES
    if ($request->bill_type === 'sale' && $request->selected_sale_id) {
        $sale = Sale::with('customer')->find($request->selected_sale_id);
        if ($sale && $sale->customer) {
            $customerId = $sale->customer_id;
            $clientName = $sale->customer->name;
            $clientAddress = $sale->customer->address;
        }
    } 
    // Handle PROJECTS
    elseif ($request->bill_type === 'project' && $request->selected_project_id) {
        $project = Project::with('client')->find($request->selected_project_id);
        if ($project && $project->client) {
            $clientId = $project->client_id;
            $clientName = $project->client->name;
            $clientAddress = $project->client->address;
        }
    }

    // Fallback to form values
    if (empty($clientName)) {
        $clientName = $request->client_name;
    }
    if (empty($clientAddress)) {
        $clientAddress = $request->client_address;
    }

    // Get bank and company details
    $bankDetail = BankDetail::find($request->bank_detail_id);
    $companyDetail = CompanyDetail::find($request->company_detail_id);

    if (!$bankDetail) {
        return back()->with('error', 'Selected bank details not found.');
    }

    if (!$companyDetail) {
        return back()->with('error', 'Selected company details not found.');
    }

    // Create bill data
    $billData = [
        'bill_number' => $billNumber,
        'type' => $request->bill_type,
        'reference_number' => $request->reference_number,
        'bill_date' => $request->bill_date,
        'sale_id' => $request->bill_type === 'sale' ? (int)$request->selected_sale_id : null,
        'project_id' => $request->bill_type === 'project' ? (int)$request->selected_project_id : null,
        'customer_id' => $customerId,
        'client_id' => $clientId,
        'work_order_number' => $request->work_order_number,
        'subtotal' => (float)$request->subtotal,
        'total_amount' => (float)$request->total_amount,
        'notes' => $request->notes,
        'bank_detail_id' => (int)$bankDetail->id,
        'company_detail_id' => (int)$companyDetail->id,
        'terms_conditions' => $request->terms_conditions,
        'subject' => $request->subject,
        'attention_to' => $request->attention_to,
        'designation' => $request->designation,
    ];

    // Create the bill
    $bill = Bill::create($billData);

    // Create bill items
    foreach ($request->items as $item) {
        BillItem::create([
            'bill_id' => $bill->id,
            'description' => $item['description'],
            'quantity' => (int)$item['quantity'],
            'unit' => $item['unit'],
            'unit_price' => (float)$item['unit_price'],
            'total' => (float)$item['total'],
        ]);
    }

    // Generate PDF - Load relationships to get client data
    $billWithRelations = Bill::with([
        'billItems', 
        'bankDetail', 
        'companyDetail',
        'sale.customer',
        'project.client'
    ])->find($bill->id);

    // Determine client data for PDF
    $pdfClientName = $clientName;
    $pdfClientAddress = $clientAddress;

    // If we don't have the data, try to get from relationships
    if (empty($pdfClientName)) {
        if ($billWithRelations->sale && $billWithRelations->sale->customer) {
            $pdfClientName = $billWithRelations->sale->customer->name;
            $pdfClientAddress = $billWithRelations->sale->customer->address;
        } elseif ($billWithRelations->project && $billWithRelations->project->client) {
            $pdfClientName = $billWithRelations->project->client->name;
            $pdfClientAddress = $billWithRelations->project->client->address;
        }
    }

    // Final fallback to form values
    if (empty($pdfClientName)) {
        $pdfClientName = $request->client_name ?: 'N/A';
    }
    if (empty($pdfClientAddress)) {
        $pdfClientAddress = $request->client_address ?: 'N/A';
    }

    $pdfData = [
        'bill' => $billWithRelations,
        'amount_in_words' => $this->convertToWords($billWithRelations->total_amount),
        'bank_details' => [
            'account_name' => $billWithRelations->bankDetail->account_name,
            'bank_name' => $billWithRelations->bankDetail->bank_name,
            'branch' => $billWithRelations->bankDetail->branch,
            'account_number' => $billWithRelations->bankDetail->account_number,
            'account_type' => $billWithRelations->bankDetail->account_type,
            'routing_number' => $billWithRelations->bankDetail->routing_number,
        ],
        'company' => [
            'name' => $billWithRelations->companyDetail->name,
            'signatory_name' => $billWithRelations->companyDetail->signatory_name,
            'signatory_designation' => $billWithRelations->companyDetail->signatory_designation,
            'phone' => $billWithRelations->companyDetail->phone,
            'email' => $billWithRelations->companyDetail->email,
            'website' => $billWithRelations->companyDetail->website,
            'address' => $billWithRelations->companyDetail->address,
        ],
        'recipient_designation' => $billWithRelations->designation,
        'recipient_organization' => $pdfClientName,
        'recipient_address' => $pdfClientAddress,
        'attention_to' => $billWithRelations->attention_to,
        'terms_conditions' => $billWithRelations->terms_conditions,
    ];

    $pdf = Pdf::loadView('pdf.bill', $pdfData);
    return $pdf->download('bill-' . $bill->bill_number . '.pdf');
}
// public function store(Request $request)
// {
//     $request->validate([
//         'bill_type' => 'required|in:sale,project',
//         'reference_number' => 'required',
//         'bill_date' => 'required|date',
//         'selected_sale_id' => 'required_if:bill_type,sale',
//         'selected_project_id' => 'required_if:bill_type,project',
//         'work_order_number' => 'nullable|string|max:255',
//         'items' => 'required|array',
//         'total_amount' => 'required|numeric',
//         'client_name' => 'required|string|max:255',
//         'client_address' => 'required|string',
//         'attention_to' => 'nullable|string|max:255',
//         'terms_conditions' => 'required|string',
//         'subject' => 'required|string|max:500',
//     ]);

//     // Generate bill number
//     $billNumber = 'BILL-' . date('Ymd') . '-' . str_pad(Bill::count() + 1, 4, '0', STR_PAD_LEFT);

//     $customerId = null;
//     $clientId = null;

//     // Auto-populate client info from selected sale/project
//     if ($request->bill_type === 'sale' && $request->selected_sale_id) {
//         $sale = Sale::with('customer')->find($request->selected_sale_id);
//         $customerId = $sale->customer_id ?? null;
//     } elseif ($request->bill_type === 'project' && $request->selected_project_id) {
//         $project = Project::with('client')->find($request->selected_project_id);
//         $clientId = $project->client_id ?? null;
//     }

//     // Get default bank and company details
//     $defaultBank = BankDetail::where('is_default', true)->where('is_active', true)->first();
//     $defaultCompany = CompanyDetail::where('is_default', true)->where('is_active', true)->first();

//     if (!$defaultBank) {
//         return back()->with('error', 'No default bank details found. Please set up bank details first.');
//     }

//     if (!$defaultCompany) {
//         return back()->with('error', 'No default company details found. Please set up company details first.');
//     }

//     $bill = Bill::create([
//         'bill_number' => $billNumber, 
//         'type' => $request->bill_type, 
//         'reference_number' => $request->reference_number,
//         'bill_date' => $request->bill_date,
//         'sale_id' => $request->selected_sale_id,
//         'project_id' => $request->selected_project_id,
//         'customer_id' => $customerId,
//         'client_id' => $clientId, 
//         'work_order_number' => $request->work_order_number,
//         'subtotal' => $request->subtotal,
//         'total_amount' => $request->total_amount,
//         'notes' => $request->notes,
        
//         // Store the relationships
//         'bank_detail_id' => $defaultBank->id,
//         'company_detail_id' => $defaultCompany->id,
        
//         // Store the essential user-entered data
//         'terms_conditions' => $request->terms_conditions,
//         'subject' => $request->subject,
//         'attention_to' => $request->attention_to,
        
//         // Store client info (you mentioned you have these fields)
//         'client_name' => $request->client_name,
//         'client_address' => $request->client_address,
//     ]);

//     foreach ($request->items as $item) {
//         BillItem::create([
//             'bill_id' => $bill->id,
//             'description' => $item['description'],
//             'quantity' => $item['quantity'],
//             'unit' => $item['unit'],
//             'unit_price' => $item['unit_price'],
//             'total' => $item['total'],
//         ]);
//     }

//     $billWithRelations = Bill::with(['billItems', 'sale.customer', 'project.client', 'bankDetail', 'companyDetail'])->find($bill->id);
    
//     $pdfData = [
//         'bill' => $billWithRelations,
//         'amount_in_words' => $this->convertToWords($billWithRelations->total_amount),
//         'subject' => $billWithRelations->subject,
//         'bank_details' => [
//             'account_name' => $billWithRelations->bankDetail->account_name,
//             'bank_name' => $billWithRelations->bankDetail->bank_name,
//             'branch' => $billWithRelations->bankDetail->branch,
//             'account_number' => $billWithRelations->bankDetail->account_number,
//             'account_type' => $billWithRelations->bankDetail->account_type,
//             'routing_number' => $billWithRelations->bankDetail->routing_number,
//         ],
//         'company' => [
//             'name' => $billWithRelations->companyDetail->name,
//             'signatory_name' => $billWithRelations->companyDetail->signatory_name,
//             'signatory_designation' => $billWithRelations->companyDetail->signatory_designation,
//             'phone' => $billWithRelations->companyDetail->phone,
//             'email' => $billWithRelations->companyDetail->email,
//             'website' => $billWithRelations->companyDetail->website,
//             'address' => $billWithRelations->companyDetail->address,
//         ],
//         'recipient_designation' => $billWithRelations->client_designation,
//         'recipient_organization' => $billWithRelations->client_name,
//         'recipient_address' => $billWithRelations->client_address,
//         'attention_to' => $billWithRelations->attention_to,
//         'terms_conditions' => $billWithRelations->terms_conditions,
//     ];

//     $pdf = Pdf::loadView('pdf.bill', $pdfData);
//     return $pdf->download('bill-' . $bill->bill_number . '.pdf');
// }

public function show($id)
{
    $bill = Bill::with([
        'billItems',
        'sale.customer', 
        'project.client',
        'customer',
        'client'
    ])->findOrFail($id);

    $data = [
        'bill' => $bill,
        'amount_in_words' => $this->convertToWords($bill->total_amount),
        'subject' => $bill->subject ?? 'Bill for Supplying of Products/Services',
        'bank_details' => [
            'account_name' => $bill->bank_account_name ?? 'Intelligent Technology',
            'bank_name' => $bill->bank_name ?? 'Bank Asia Ltd.',
            'branch' => $bill->bank_branch ?? 'Satmosjid Road',
            'account_number' => $bill->bank_account_number ?? '06933000526',
            'account_type' => $bill->bank_account_type ?? 'Current',
        ],
        'company' => [
            'name' => $bill->company_name ?? 'Intelligent Technology',
            'signatory_name' => $bill->signatory_name ?? 'Engr. Shamsul Alam',
            'signatory_designation' => $bill->signatory_designation ?? 'Director (Technical)',
            'phone' => $bill->company_phone ?? '+880 XXXX-XXXXXX',
            'email' => $bill->company_email ?? 'info@intelligenttech.com',
            'website' => $bill->company_website ?? 'www.intelligenttech.com',
        ],
        'recipient_designation' => 'Director (IT)',
        'recipient_organization' => $bill->client_name ?? ($bill->client->name ?? 'N/A'),
        'recipient_address' => $bill->client_address ?? ($bill->client->address ?? 'N/A'),
        'attention_to' => $bill->attention_to,
        'terms_conditions' => $bill->terms_conditions,
    ];

    return view('frontend.pages.bills.show', $data);
}

private function convertToWords($number)
{
    $decimal = round($number - floor($number), 2) * 100;
    $whole_number = floor($number);
    
    $words = $this->convertNumberToWords($whole_number) . ' Taka';
    
    if ($decimal > 0) {
        $words .= ' and ' . $this->convertNumberToWords($decimal) . ' Paisa';
    }
    
    return $words;
}

private function convertNumberToWords($number)
{
    if ($number == 0) {
        return 'Zero';
    }
    
    $ones = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen'
    );
    
    $tens = array(
        2 => 'Twenty',
        3 => 'Thirty',
        4 => 'Forty',
        5 => 'Fifty',
        6 => 'Sixty',
        7 => 'Seventy',
        8 => 'Eighty',
        9 => 'Ninety'
    );
    
    $words = '';
    
    // Handle lakhs
    if ($number >= 100000) {
        $lakhs = floor($number / 100000);
        $words .= $this->convertNumberToWords($lakhs) . ' Lakh ';
        $number %= 100000;
    }
    
    // Handle thousands
    if ($number >= 1000) {
        $thousands = floor($number / 1000);
        $words .= $this->convertNumberToWords($thousands) . ' Thousand ';
        $number %= 1000;
    }
    
    // Handle hundreds
    if ($number >= 100) {
        $hundreds = floor($number / 100);
        $words .= $this->convertNumberToWords($hundreds) . ' Hundred ';
        $number %= 100;
    }
    
    // Handle tens and ones
    if ($number > 0) {
        if ($number < 20) {
            $words .= $ones[$number];
        } else {
            $words .= $tens[floor($number / 10)];
            if ($number % 10 > 0) {
                $words .= ' ' . $ones[$number % 10];
            }
        }
    }
    
    return trim($words);
}

private function updateRelatedEntities(Bill $bill, array $validated)
{
    try {
        switch ($validated['bill_type']) {
            case 'project':
                // Update project status or add bill reference
                $project = Project::find($validated['project_id']);
                if ($project) {
                    // You can update project status or add bill reference here
                    \Log::info("Bill {$bill->bill_number} created for project: {$project->name}");
                }
                break;

            case 'sale':
                // Update sale status or add bill reference
                $client = Client::find($validated['client_id']);
                if ($client) {
                    \Log::info("Bill {$bill->bill_number} created for client: {$client->name}");
                }
                break;

            case 'purchase':
                // Update purchase order status
                if (isset($validated['purchase_id'])) {
                    $purchase = Purchase::find($validated['purchase_id']);
                    if ($purchase) {
                        // Update purchase status to billed
                        $purchase->update(['status' => 'billed']);
                        \Log::info("Bill {$bill->bill_number} created for purchase: {$purchase->purchase_number}");
                    }
                }
                break;

            case 'vendor':
                $vendor = Vendor::find($validated['vendor_id']);
                if ($vendor) {
                    \Log::info("Vendor bill {$bill->bill_number} created for: {$vendor->name}");
                }
                break;
        }
    } catch (\Exception $e) {
        \Log::error('Error updating related entities: ' . $e->getMessage());
    }
}

   public function preview(Bill $bill)
{
    $bill->load(['client', 'vendor', 'project', 'purchase', 'items']);
    
    $company = $this->getCompanyDetails();
    $bankDetails = $this->getBankDetails();
    $amountInWords = $bill->amount_in_words;

    // Customize PDF title based on bill type
    $title = match($bill->type) {
        'project' => 'PROJECT BILL',
        'sale' => 'SALES INVOICE',
        'purchase' => 'PURASE BILL',
        'vendor' => 'VENDOR BILL',
        default => 'BILL'
    };

    $pdf = Pdf::loadView('bills.pdf.template', [
        'bill' => $bill,
        'company' => $company,
        'bank_details' => $bankDetails,
        'amount_in_words' => $amountInWords,
        'title' => $title,
    ]);

    return $pdf->stream("{$bill->bill_number}.pdf");
}

public function download($id)
{
    $bill = Bill::with(['billItems', 'sale.customer', 'project.client', 'bankDetail', 'companyDetail'])->findOrFail($id);
    
    // Determine client name and address from relationships
    $clientName = $bill->client_name;
    $clientAddress = $bill->client_address;
    
    // If not stored directly, get from relationships
    if (empty($clientName)) {
        if ($bill->sale && $bill->sale->customer) {
            $clientName = $bill->sale->customer->name;
            $clientAddress = $bill->sale->customer->address;
        } elseif ($bill->project && $bill->project->client) {
            $clientName = $bill->project->client->name;
            $clientAddress = $bill->project->client->address;
        }
    }
    
    // Final fallback
    if (empty($clientName)) $clientName = 'N/A';
    if (empty($clientAddress)) $clientAddress = 'N/A';

    $pdfData = [
        'bill' => $bill,
        'amount_in_words' => $this->convertToWords($bill->total_amount),
        'subject' => $bill->subject,
        'bank_details' => [
            'account_name' => $bill->bankDetail->account_name,
            'bank_name' => $bill->bankDetail->bank_name,
            'branch' => $bill->bankDetail->branch,
            'account_number' => $bill->bankDetail->account_number,
            'account_type' => $bill->bankDetail->account_type,
            'routing_number' => $bill->bankDetail->routing_number,
        ],
        'company' => [
            'name' => $bill->companyDetail->name,
            'signatory_name' => $bill->companyDetail->signatory_name,
            'signatory_designation' => $bill->companyDetail->signatory_designation,
            'phone' => $bill->companyDetail->phone,
            'email' => $bill->companyDetail->email,
            'website' => $bill->companyDetail->website,
            'address' => $bill->companyDetail->address,
        ],
        'recipient_designation' => $bill->designation ?: 'Director (IT)',
        'recipient_organization' => $clientName,    // Use determined name
        'recipient_address' => $clientAddress,      // Use determined address
        'attention_to' => $bill->attention_to,
        'terms_conditions' => $bill->terms_conditions,
    ];

    $pdf = Pdf::loadView('pdf.bill', $pdfData);
    return $pdf->download('bill-' . $bill->bill_number . '.pdf');
}
    public function updateStatus(Bill $bill, Request $request)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue,cancelled'
        ]);

        $bill->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Bill status updated successfully!'
        ]);
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'reference_number' => 'required|string|unique:bills,reference_number',
            'client_id' => 'nullable|exists:clients,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'purchase_id' => 'nullable|exists:purchases,id',
            'work_order_number' => 'nullable|string',
            'bill_date' => 'required|date',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
    }

    private function getSourceData($sourceType, $sourceId)
    {
        switch ($sourceType) {
            case 'project':
                return Project::with('client', 'items')->find($sourceId);
            case 'client':
                return Client::find($sourceId);
            case 'vendor':
                return Vendor::find($sourceId);
            case 'purchase':
                return Purchase::with('vendor', 'items')->find($sourceId);
            default:
                return null;
        }
    }

    private function getDefaultData($sourceType, $source)
    {
        $defaults = [
            'reference_number' => 'BIL-' . now()->format('Ymd-His'),
            'bill_date' => now()->format('Y-m-d'),
        ];

        if (!$source) return $defaults;

        switch ($sourceType) {
            case 'project':
                $defaults['project_id'] = $source->id;
                $defaults['client_id'] = $source->client_id;
                $defaults['work_order_number'] = $source->work_order_number;
                $defaults['notes'] = "Bill for project: {$source->name}";
                break;
            
            case 'purchase':
                $defaults['purchase_id'] = $source->id;
                $defaults['vendor_id'] = $source->vendor_id;
                $defaults['notes'] = "Bill for purchase order: {$source->purchase_number}";
                break;
            
            case 'client':
                $defaults['client_id'] = $source->id;
                $defaults['notes'] = "Bill for client: {$source->name}";
                break;
        }

        return $defaults;
    }

    private function determineBillType($data)
    {
        if ($data['project_id']) return 'project';
        if ($data['purchase_id']) return 'purchase';
        if ($data['vendor_id']) return 'vendor';
        if ($data['client_id']) return 'sale';
        return 'general';
    }

public function destroy($id)
{
    $bill = Bill::findOrFail($id);
    $bill->billItems()->delete(); 
    $bill->delete(); 

    return redirect()->route('bills.index')->with('success', 'Bill deleted successfully.');
 
}
}