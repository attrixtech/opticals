<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Repair;
use App\Models\User;
use App\Models\Warranty;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with(['customer', 'technician'])
            ->latest()
            ->paginate(15);

        $warranties = Warranty::with(['salesOrder.customer'])
            ->latest('start_date')
            ->take(10)
            ->get();

        $customers = Customer::where('is_active', true)->get();
        $technicians = User::where('role', 'lab_technician')->orWhere('role', 'admin')->get();

        return view('repairs.index', compact('repairs', 'warranties', 'customers', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'frame_description' => 'required|string',
            'issue_description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'advance_paid' => 'nullable|numeric|min:0',
            'delivery_due_date' => 'nullable|date',
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $user = auth()->user() ?? User::first();
        $repairNumber = 'REP-' . date('Ymd') . '-' . rand(100, 999);

        Repair::create(array_merge($validated, [
            'company_id' => $user->company_id ?? 1,
            'branch_id' => $user->branch_id ?? 1,
            'repair_number' => $repairNumber,
            'received_date' => now()->toDateString(),
            'status' => 'received',
        ]));

        return redirect()->back()->with('success', 'Frame Repair Job #' . $repairNumber . ' created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:received,in_repair,ready,delivered',
        ]);

        $repair->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Repair status updated to ' . ucfirst($validated['status']));
    }
}

