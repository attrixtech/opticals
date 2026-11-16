<?php

namespace App\Http\Controllers;

use App\Models\LabJobCard;
use App\Models\QualityControl;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Http\Request;

class QualityControlController extends Controller
{
    public function store(Request $request, $jobId)
    {
        $job = LabJobCard::findOrFail($jobId);

        $validated = $request->validate([
            'qc_status' => 'required|in:passed,rejected',
            'check_lens_power_sph_cyl_axis' => 'nullable|boolean',
            'check_optical_center_pd' => 'nullable|boolean',
            'check_fitting_height' => 'nullable|boolean',
            'check_lens_edging_bevel_fit' => 'nullable|boolean',
            'check_lens_surface_scratches_coating' => 'nullable|boolean',
            'check_frame_four_point_alignment' => 'nullable|boolean',
            'check_screws_and_lens_security' => 'nullable|boolean',
            'check_cleanliness_and_presentation' => 'nullable|boolean',
            'rejection_reason' => 'nullable|string',
        ]);

        $user = auth()->user() ?? User::first();

        $qc = QualityControl::updateOrCreate(
            ['lab_job_card_id' => $job->id],
            [
                'sales_order_id' => $job->sales_order_id,
                'inspector_id' => $user->id,
                'check_lens_power_sph_cyl_axis' => $request->has('check_lens_power_sph_cyl_axis'),
                'check_optical_center_pd' => $request->has('check_optical_center_pd'),
                'check_fitting_height' => $request->has('check_fitting_height'),
                'check_lens_edging_bevel_fit' => $request->has('check_lens_edging_bevel_fit'),
                'check_lens_surface_scratches_coating' => $request->has('check_lens_surface_scratches_coating'),
                'check_frame_four_point_alignment' => $request->has('check_frame_four_point_alignment'),
                'check_screws_and_lens_security' => $request->has('check_screws_and_lens_security'),
                'check_cleanliness_and_presentation' => $request->has('check_cleanliness_and_presentation'),
                'qc_status' => $validated['qc_status'],
                'rejection_reason' => $validated['rejection_reason'] ?? null,
                'inspected_at' => now(),
            ]
        );

        if ($validated['qc_status'] === 'passed') {
            $job->transitionStage('completed', 'Passed 8-point Optical Quality Inspection', $user->id);
            $job->salesOrder->update(['order_status' => 'ready_for_delivery']);
            return redirect()->back()->with('success', 'Optical Quality Control Approved! Order is now ready for customer fitting and delivery.');
        } else {
            $job->transitionStage('rework', 'QC Rejected: ' . ($validated['rejection_reason'] ?? 'Defects found'), $user->id);
            $job->salesOrder->update(['order_status' => 'in_lab']);
            return redirect()->back()->with('error', 'QC Failed! Job routed to Rework pipeline.');
        }
    }
}

