<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount(['prescriptions', 'salesOrders']);

        if ($request->has('search')) {
            $s = $request->get('search');
            $query->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('customer_code', 'like', "%{$s}%");
        }

        $customers = $query->latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:25',
            'email' => 'nullable|email',
            'gender' => 'required|in:male,female,other,unspecified',
            'date_of_birth' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'state_code' => 'nullable|string|max:5',
            'pincode' => 'nullable|string|max:10',
            'gstin' => 'nullable|string|max:15',
            'medical_notes' => 'nullable|string',
        ]);

        $user = auth()->user() ?? User::first();
        $code = 'CUST-' . date('Y') . '-' . rand(1000, 9999);

        $customer = Customer::create(array_merge($validated, [
            'company_id' => $user->company_id ?? 1,
            'branch_id' => $user->branch_id ?? 1,
            'customer_code' => $code,
        ]));

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Customer profile ' . $customer->name . ' registered successfully!');
    }

    public function show($id)
    {
        $customer = Customer::with([
            'prescriptions',
            'eyeMeasurements',
            'salesOrders.items',
            'repairs',
        ])->findOrFail($id);

        return view('customers.show', compact('customer'));
    }
}

