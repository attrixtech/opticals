<?php

namespace Tests\Unit;

use App\Services\GstTaxService;
use PHPUnit\Framework\TestCase;

class GstTaxServiceTest extends TestCase
{
    protected GstTaxService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new GstTaxService();
    }

    public function test_intra_state_gst_splits_into_cgst_and_sgst(): void
    {
        // Branch in Maharashtra (27) and Customer in Maharashtra (27)
        // Taxable 10,000 at 12% GST => 6% CGST (600) + 6% SGST (600), IGST 0
        $result = $this->service->calculateLineTax(10000.00, 12.00, '27', '27');

        $this->assertFalse($result['is_inter_state']);
        $this->assertEquals(600.00, $result['cgst']);
        $this->assertEquals(600.00, $result['sgst']);
        $this->assertEquals(0.00, $result['igst']);
        $this->assertEquals(1200.00, $result['total_tax']);
        $this->assertEquals(11200.00, $result['total_amount']);
    }

    public function test_inter_state_gst_applies_single_igst(): void
    {
        // Branch in Maharashtra (27) and Customer in Karnataka (29)
        // Taxable 5,000 at 18% GST => IGST 900, CGST 0, SGST 0
        $result = $this->service->calculateLineTax(5000.00, 18.00, '27', '29');

        $this->assertTrue($result['is_inter_state']);
        $this->assertEquals(0.00, $result['cgst']);
        $this->assertEquals(0.00, $result['sgst']);
        $this->assertEquals(900.00, $result['igst']);
        $this->assertEquals(900.00, $result['total_tax']);
        $this->assertEquals(5900.00, $result['total_amount']);
    }
}

