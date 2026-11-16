<?php

namespace App\Services;

class PrescriptionService
{
    /**
     * Validates optical parameters for standard optometric ranges.
     */
    public function validateOpticalValues(array $data): array
    {
        $errors = [];

        foreach (['od', 'os'] as $eye) {
            $sph = $data[$eye . '_sph'] ?? null;
            $cyl = $data[$eye . '_cyl'] ?? null;
            $axis = $data[$eye . '_axis'] ?? null;
            $add = $data[$eye . '_add'] ?? null;

            if ($sph !== null && ($sph < -25.00 || $sph > 25.00)) {
                $errors[] = strtoupper($eye) . ' Sphere out of clinical range (-25.00 to +25.00).';
            }

            if ($cyl !== null && $cyl != 0) {
                if ($cyl < -10.00 || $cyl > 10.00) {
                    $errors[] = strtoupper($eye) . ' Cylinder out of range (-10.00 to +10.00).';
                }
                if ($axis === null || $axis < 0 || $axis > 180) {
                    $errors[] = strtoupper($eye) . ' Axis is required and must be between 0 and 180 degrees when Cylinder is specified.';
                }
            }

            if ($add !== null && ($add < 0.50 || $add > 4.50)) {
                $errors[] = strtoupper($eye) . ' Add power out of range (+0.50 to +4.50).';
            }
        }

        return $errors;
    }

    /**
     * Computes Spherical Equivalent: SphEq = SPH + (CYL / 2)
     */
    public function calculateSphericalEquivalent(?float $sph, ?float $cyl): ?float
    {
        if ($sph === null) return null;
        $cylVal = $cyl ?? 0.0;
        return round($sph + ($cylVal / 2), 2);
    }

    /**
     * Performs optical transposition between plus and minus cylinder forms.
     * New SPH = SPH + CYL
     * New CYL = -CYL
     * New AXIS = (AXIS > 90) ? AXIS - 90 : AXIS + 90
     */
    public function transpose(float $sph, float $cyl, int $axis): array
    {
        $newSph = round($sph + $cyl, 2);
        $newCyl = round(-$cyl, 2);
        $newAxis = ($axis > 90) ? ($axis - 90) : ($axis + 90);
        if ($newAxis === 0) $newAxis = 180;

        return [
            'sph' => $newSph,
            'cyl' => $newCyl,
            'axis' => $newAxis,
        ];
    }
}

