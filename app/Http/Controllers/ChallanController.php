<?php

namespace App\Http\Controllers;

use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Sale;
use App\Models\Project;
use Illuminate\Http\Request;
use PDF;

class ChallanController extends Controller
{

public function index(Request $request)
{

    $query = Challan::with('challanItems');
    
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
    
    $challans = $query->latest()->paginate(10);

    
    return view('frontend.pages.challans.index', compact('challans'));
}

    public function create()
    {
        return view('frontend.pages.challans.create');
    }

    public function store(Request $request)
    {
        \Log::info('Challan store method called', $request->all());

        try {
            $validated = $request->validate([
                'type' => 'required|in:sale,project',
                'reference_number' => 'required',
                'challan_date' => 'required|date',
                'selected_sale_id' => 'required_if:type,sale',
                'selected_project_id' => 'required_if:type,project',
                'items' => 'required|array|min:1',
                'items.*.description' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit' => 'required|string',
            ]);

            // Generate challan number
            $challanNumber = 'CHALLAN-' . date('Ymd') . '-' . str_pad(Challan::count() + 1, 4, '0', STR_PAD_LEFT);

            // Set customer_id and client_id based on type
            $customerId = null;
            $clientId = null;

            if ($request->type === 'sale' && $request->selected_sale_id) {
                $sale = Sale::find($request->selected_sale_id);
                $customerId = $sale->customer_id ?? null;
            } elseif ($request->type === 'project' && $request->selected_project_id) {
                $project = Project::find($request->selected_project_id);
                $clientId = $project->client_id ?? null;
            }

            // Create the challan
            $challan = Challan::create([
                'challan_number' => $challanNumber,
                'reference_number' => $validated['reference_number'],
                'challan_date' => $validated['challan_date'],
                'type' => $validated['type'],
                'sale_id' => $request->type === 'sale' ? $validated['selected_sale_id'] : null,
                'project_id' => $request->type === 'project' ? $validated['selected_project_id'] : null,
                'customer_id' => $customerId,
                'client_id' => $clientId,
                'attention_to' => $request->attention_to,
                'designation' => $request->recipient_designation,
            ]);

            // Add challan items
            foreach ($validated['items'] as $item) {
                ChallanItem::create([
                    'challan_id' => $challan->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'serial' => $item['serial'] ?? null,
                ]);
            }

            // Load challan with relationships for PDF
            $challan->load('challanItems');

            // Prepare data for PDF
            $pdfData = [
                'challan' => $challan,
                'challan_items' => $challan->challanItems,
                'recipient_organization' => $request->recipient_organization,
                'recipient_designation' => $request->recipient_designation ?? 'The Managing Director',
                'recipient_address' => $request->recipient_address,
                'attention_to' => $request->attention_to,
                'subject' => $request->subject,
                'notes' => $request->notes,
                'company_name' => $request->company_name,
                'signatory_name' => $request->signatory_name,
                'signatory_designation' => $request->signatory_designation,
                'company_phone' => $request->company_phone,
                'company_email' => $request->company_email,
                'company_website' => $request->company_website,
            ];

            \Log::info('PDF data prepared', $pdfData);

            // Generate PDF with proper headers
            $pdf = PDF::loadView('pdf.challan', $pdfData);
            
            return $pdf->download('challan-' . $challan->challan_number . '.pdf');

        } catch (\Exception $e) {
            \Log::error('Challan creation error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            return back()->with('error', 'Error creating challan: ' . $e->getMessage())->withInput();
        }
    }
    public function show($id)
    {
        $challan = Challan::with(['challanItems', 'sale.customer', 'project.client'])->findOrFail($id);
        return view('challans.show', compact('challan'));
    }

public function download($id)
{
    $challan = Challan::with([
        'challanItems', 
        'sale.customer', 
        'project.client'
    ])->findOrFail($id);

    // Get client name from relationships (not from stored fields)
    $clientName = null;
    $clientAddress = null;

    if ($challan->type === 'sale' && $challan->sale && $challan->sale->customer) {
        $clientName = $challan->sale->customer->name;
        $clientAddress = $challan->sale->customer->address;
    } elseif ($challan->type === 'project' && $challan->project && $challan->project->client) {
        $clientName = $challan->project->client->name;
        $clientAddress = $challan->project->client->address;
    }

    $pdfData = [
        'challan' => $challan,
        'challan_items' => $challan->challanItems,
        'recipient_organization' => $clientName ?? 'N/A',           // From relationship
        'recipient_designation' => $challan->designation ?? 'The Managing Director', // From database
        'recipient_address' => $clientAddress ?? 'N/A',            // From relationship
        'attention_to' => $challan->attention_to,                  // From database
        'subject' => $challan->subject ?? 'Delivery Challan',
        'notes' => $challan->notes ?? '',
        'company_name' => $challan->company_name ?? 'Intelligent Technology',
        'signatory_name' => $challan->signatory_name ?? 'Engr. Shamsul Alam',
        'signatory_designation' => $challan->signatory_designation ?? 'Director (Technical)',
        'company_phone' => $challan->company_phone ?? '+880 XXXX-XXXXXX',
        'company_email' => $challan->company_email ?? 'info@intelligenttech.com',
        'company_website' => $challan->company_website ?? 'www.intelligenttech.com',
    ];

    $pdf = PDF::loadView('pdf.challan', $pdfData);
    return $pdf->download('challan-' . $challan->challan_number . '.pdf');
}
public function getSales()
{
    try {
      

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

        \Log::info('Final sales count: ' . $sales->count());
        \Log::info('=== END DEBUG ===');

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
                                // Get description from the product relationship
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

public function destroy($id)
{
    try {
        $challan = Challan::findOrFail($id);
        $challan->delete();

        return redirect()->route('challans.index')->with('success', 'Challan deleted successfully.');
    } catch (\Exception $e) {
        \Log::error('Error deleting challan: ' . $e->getMessage());
        return redirect()->route('challans.index')->with('error', 'Failed to delete challan.');
    }
}
}