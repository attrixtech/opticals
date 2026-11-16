<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $query = SalesOrder::with(['customer', 'prescription', 'items', 'payments']);

        if ($status) {
            $query->where('order_status', $status);
        }

        if ($request->has('search')) {
            $s = $request->get('search');
            $query->where('order_number', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($q) use ($s) {
                      $q->where('name', 'like', "%{$s}%")
                        ->orWhere('phone', 'like', "%{$s}%");
                  });
        }

        $orders = $query->latest('order_date')->paginate(15);

        return view('orders.index', compact('orders', 'status'));
    }

    public function show($id)
    {
        $order = SalesOrder::with([
            'customer',
            'prescription',
            'eyeMeasurement',
            'items.product',
            'payments.collectedBy',
            'invoices',
            'labJobCard.technician',
            'qualityControls',
            'warranties',
        ])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function addPayment(Request $request, $id)
    {
        $order = SalesOrder::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|in:cash,upi,card,bank_transfer',
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user() ?? User::first();
        $payAmount = min((float)$validated['amount'], (float)$order->balance_amount);

        Payment::create([
            'company_id' => $order->company_id,
            'branch_id' => $order->branch_id,
            'sales_order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'receipt_number' => 'RCT-' . date('Ymd') . '-' . rand(1000, 9999),
            'payment_date' => now()->toDateString(),
            'amount' => $payAmount,
            'payment_mode' => $validated['payment_mode'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'notes' => $validated['notes'] ?? 'Balance Payment',
            'collected_by' => $user->id,
        ]);

        $newPaid = $order->paid_amount + $payAmount;
        $newBalance = max(0, $order->total_amount - $newPaid);
        $order->update([
            'paid_amount' => $newPaid,
            'balance_amount' => $newBalance,
            'payment_status' => $newBalance <= 0 ? 'paid' : 'partially_paid',
        ]);

        return redirect()->back()->with('success', 'Payment of ₹' . number_format($payAmount, 2) . ' collected successfully!');
    }

    public function markDelivered($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->update([
            'order_status' => 'delivered',
            'delivered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Order #' . $order->order_number . ' marked as Delivered to customer!');
    }
}

