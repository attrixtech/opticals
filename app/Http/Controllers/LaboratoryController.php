<?php

namespace App\Http\Controllers;

use App\Models\LabJobCard;
use App\Models\LabJobLog;
use App\Models\QualityControl;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $stage = $request->get('stage');
        
        $query = LabJobCard::with(['salesOrder.customer', 'technician']);
        if ($stage && $stage !== 'all') {
            $query->where('stage', $stage);
        }

        $jobs = $query->latest()->get();

        // Pipeline stage statistics
        $counts = [
            'total' => LabJobCard::count(),
            'job_created' => LabJobCard::where('stage', 'job_created')->count(),
            'surfacing' => LabJobCard::where('stage', 'surfacing')->count(),
            'edging' => LabJobCard::where('stage', 'edging')->count(),
            'qc_pending' => LabJobCard::where('stage', 'qc_pending')->count(),
            'completed' => LabJobCard::where('stage', 'completed')->count(),
        ];

        return view('laboratory.index', compact('jobs', 'counts', 'stage'));
    }

    public function show($id)
    {
        $job = LabJobCard::with(['salesOrder.customer', 'salesOrder.items', 'technician', 'logs.user', 'qualityControl'])->findOrFail($id);
        $technicians = User::where('role', 'lab_technician')->orWhere('role', 'admin')->get();

        return view('laboratory.show', compact('job', 'technicians'));
    }

    public function updateStage(Request $request, $id)
    {
        $job = LabJobCard::findOrFail($id);
        
        $validated = $request->validate([
            'stage' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $user = auth()->user() ?? User::first();
        $job->transitionStage($validated['stage'], $validated['remarks'] ?? 'Stage advanced via Lab Portal', $user->id);

        // If completed and QC passed, update sales order status to ready for delivery
        if ($validated['stage'] === 'completed') {
            $job->salesOrder->update(['order_status' => 'ready_for_delivery']);
        }

        return redirect()->back()->with('success', 'Lab job transitioned to ' . str_replace('_', ' ', $validated['stage']) . ' stage successfully!');
    }
}

