<?php

namespace Tests\Unit;

use App\Services\PrescriptionService;
use PHPUnit\Framework\TestCase;

class PrescriptionServiceTest extends TestCase
{
    protected PrescriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PrescriptionService();
    }

    public function test_spherical_equivalent_calculation(): void
    {
        // SphEq = SPH + (CYL / 2)
        // -2.00 SPH with -1.00 CYL => -2.50
        $result = $this->service->calculateSphericalEquivalent(-2.00, -1.00);
        $this->assertEquals(-2.50, $result);

        // +1.50 SPH with -0.50 CYL => +1.25
        $result = $this->service->calculateSphericalEquivalent(1.50, -0.50);
        $this->assertEquals(1.25, $result);
    }

    public function test_optical_transposition(): void
    {
        // Transposing +1.00 / +0.50 x 90 -> +1.50 / -0.50 x 180
        $transposed = $this->service->transpose(1.00, 0.50, 90);
        $this->assertEquals(1.50, $transposed['sph']);
        $this->assertEquals(-0.50, $transposed['cyl']);
        $this->assertEquals(180, $transposed['axis']);

        // Transposing -2.00 / -1.00 x 180 -> -3.00 / +1.00 x 90
        $transposed2 = $this->service->transpose(-2.00, -1.00, 180);
        $this->assertEquals(-3.00, $transposed2['sph']);
        $this->assertEquals(1.00, $transposed2['cyl']);
        $this->assertEquals(90, $transposed2['axis']);
    }

    public function test_optical_validation_catches_invalid_axis(): void
    {
        // Cylinder provided but axis missing
        $errors = $this->service->validateOpticalValues([
            'od_sph' => -1.50,
            'od_cyl' => -0.75,
            'od_axis' => null, // Missing!
        ]);

        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('Axis is required', $errors[0]);
    }
}

