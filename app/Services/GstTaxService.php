<?php

namespace App\Services;

class GstTaxService
{
    /**
     * Calculate taxes based on supplier state and destination/customer state.
     */
    public function calculateLineTax(
        float $taxableAmount,
        float $taxRate,
        ?string $branchStateCode,
        ?string $customerStateCode
    ): array {
        $isInterState = !empty($customerStateCode) && 
                        !empty($branchStateCode) && 
                        ($customerStateCode !== $branchStateCode);

        $totalTax = round($taxableAmount * ($taxRate / 100), 2);

        if ($isInterState) {
            return [
                'is_inter_state' => true,
                'cgst' => 0.00,
                'sgst' => 0.00,
                'igst' => $totalTax,
                'total_tax' => $totalTax,
                'total_amount' => round($taxableAmount + $totalTax, 2),
            ];
        }

        $halfTax = round($totalTax / 2, 2);
        return [
            'is_inter_state' => false,
            'cgst' => $halfTax,
            'sgst' => round($totalTax - $halfTax, 2),
            'igst' => 0.00,
            'total_tax' => $totalTax,
            'total_amount' => round($taxableAmount + $totalTax, 2),
        ];
    }
}

