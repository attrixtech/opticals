<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Services\OrderLifecycleService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(
        protected OrderLifecycleService $orderService
    ) {}

    public function index(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        
        $products = Product::with(['brand', 'category', 'stocks', 'frame', 'lens'])
            ->where('is_active', true)
            ->get();

        return view('pos.index', compact('customers', 'categories', 'brands', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required|string',
            'items.*.item_type' => 'required|string',
            'items.*.hsn_code' => 'required|string',
            'items.*.eye_target' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|string|in:cash,upi,card,bank_transfer',
            'transaction_reference' => 'nullable|string',
            'delivery_due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user() ?? \App\Models\User::first();
        $companyId = $user->company_id ?? 1;
        $branchId = $user->branch_id ?? 1;

        $orderData = [
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'customer_id' => $validated['customer_id'],
            'prescription_id' => $validated['prescription_id'] ?? null,
            'salesperson_id' => $user->id,
            'order_date' => now()->toDateString(),
            'delivery_due_date' => $validated['delivery_due_date'] ?? now()->addDays(2)->toDateString(),
            'notes' => $validated['notes'] ?? null,
        ];

        $advancePayment = null;
        if (!empty($validated['advance_amount']) && $validated['advance_amount'] > 0) {
            $advancePayment = [
                'amount' => $validated['advance_amount'],
                'payment_mode' => $validated['payment_mode'] ?? 'cash',
                'transaction_reference' => $validated['transaction_reference'] ?? null,
            ];
        }

        $order = $this->orderService->createSalesOrder($orderData, $validated['items'], $advancePayment);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Optical Sales Order #' . $order->order_number . ' created successfully! Lab Job Card and Tax Invoice generated.');
    }

    public function getCustomerPrescriptions($customerId)
    {
        $prescriptions = Prescription::where('customer_id', $customerId)
            ->orderBy('prescription_date', 'desc')
            ->get();

        return response()->json($prescriptions);
    }
}

