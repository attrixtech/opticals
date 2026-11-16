<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\EyeMeasurement;
use App\Models\Prescription;
use App\Services\PrescriptionService;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function __construct(
        protected PrescriptionService $rxService
    ) {}

    public function index(Request $request)
    {
        $query = Prescription::with(['customer', 'verifiedBy']);

        if ($request->has('search')) {
            $s = $request->get('search');
            $query->whereHas('customer', function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            })->orWhere('prescription_number', 'like', "%{$s}%");
        }

        $prescriptions = $query->latest('prescription_date')->paginate(15);

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $selectedCustomer = null;
        if ($request->has('customer_id')) {
            $selectedCustomer = Customer::find($request->customer_id);
        }

        return view('prescriptions.create', compact('customers', 'selectedCustomer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'prescription_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'doctor_name' => 'nullable|string',
            'clinic_hospital' => 'nullable|string',
            'prescription_type' => 'required|string',
            'od_sph' => 'nullable|numeric',
            'od_cyl' => 'nullable|numeric',
            'od_axis' => 'nullable|integer|between:0,180',
            'od_add' => 'nullable|numeric',
            'od_prism' => 'nullable|numeric',
            'od_base' => 'nullable|string',
            'od_va_distance' => 'nullable|string',
            'od_va_near' => 'nullable|string',
            'os_sph' => 'nullable|numeric',
            'os_cyl' => 'nullable|numeric',
            'os_axis' => 'nullable|integer|between:0,180',
            'os_add' => 'nullable|numeric',
            'os_prism' => 'nullable|numeric',
            'os_base' => 'nullable|string',
            'os_va_distance' => 'nullable|string',
            'os_va_near' => 'nullable|string',
            'pd_distance' => 'nullable|numeric',
            'pd_near' => 'nullable|numeric',
            'clinical_notes' => 'nullable|string',
        ]);

        $user = auth()->user() ?? \App\Models\User::first();
        $companyId = $user->company_id ?? 1;
        $branchId = $user->branch_id ?? 1;

        $prescriptionNumber = 'RX-' . date('Ymd') . '-' . rand(1000, 9999);

        $rx = Prescription::create(array_merge($validated, [
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'prescription_number' => $prescriptionNumber,
            'is_verified' => true,
            'verified_by' => $user->id,
            'created_by' => $user->id,
        ]));

        return redirect()->route('prescriptions.show', $rx->id)
            ->with('success', 'Prescription ' . $rx->prescription_number . ' recorded and signed successfully!');
    }

    public function show($id)
    {
        $prescription = Prescription::with(['customer.prescriptions', 'verifiedBy', 'createdBy'])->findOrFail($id);
        
        // Transposition preview for optical staff
        $odTransposed = null;
        if ($prescription->od_sph !== null && $prescription->od_cyl !== null && $prescription->od_axis !== null) {
            $odTransposed = $this->rxService->transpose((float)$prescription->od_sph, (float)$prescription->od_cyl, (int)$prescription->od_axis);
        }

        $osTransposed = null;
        if ($prescription->os_sph !== null && $prescription->os_cyl !== null && $prescription->os_axis !== null) {
            $osTransposed = $this->rxService->transpose((float)$prescription->os_sph, (float)$prescription->os_cyl, (int)$prescription->os_axis);
        }

        return view('prescriptions.show', compact('prescription', 'odTransposed', 'osTransposed'));
    }
}

