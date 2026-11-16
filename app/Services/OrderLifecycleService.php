<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\InventoryStock;
use App\Models\InventoryMovement;
use App\Models\Invoice;
use App\Models\LabJobCard;
use App\Models\Payment;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\DB;

class OrderLifecycleService
{
    public function __construct(
        protected GstTaxService $gstService
    ) {}

    public function createSalesOrder(array $orderData, array $items, ?array $advancePayment = null): SalesOrder
    {
        return DB::transaction(function () use ($orderData, $items, $advancePayment) {
            $customer = Customer::findOrFail($orderData['customer_id']);
            $branch = DB::table('branches')->where('id', $orderData['branch_id'])->first();

            $subtotal = 0.00;
            $totalTaxable = 0.00;
            $totalCgst = 0.00;
            $totalSgst = 0.00;
            $totalIgst = 0.00;

            // Generate unique Order Number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $order = SalesOrder::create(array_merge($orderData, [
                'order_number' => $orderNumber,
                'subtotal' => 0,
                'taxable_amount' => 0,
                'cgst_amount' => 0,
                'sgst_amount' => 0,
                'igst_amount' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'balance_amount' => 0,
                'order_status' => 'confirmed',
            ]));

            $hasLensesRequiringLab = false;

            foreach ($items as $item) {
                $qty = (int) ($item['quantity'] ?? 1);
                $unitPrice = (float) $item['unit_price'];
                $discount = (float) ($item['discount_amount'] ?? 0.00);
                $taxRate = (float) ($item['tax_rate'] ?? 12.00);

                $taxable = round(($unitPrice * $qty) - $discount, 2);
                $taxCalc = $this->gstService->calculateLineTax(
                    $taxable,
                    $taxRate,
                    $branch->state_code ?? '27',
                    $customer->state_code ?? ($branch->state_code ?? '27')
                );

                $orderItem = SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'item_name' => $item['item_name'],
                    'item_type' => $item['item_type'] ?? 'frame',
                    'hsn_code' => $item['hsn_code'] ?? '9003',
                    'eye_target' => $item['eye_target'] ?? 'none',
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'discount_amount' => $discount,
                    'taxable_amount' => $taxable,
                    'tax_rate' => $taxRate,
                    'cgst_amount' => $taxCalc['cgst'],
                    'sgst_amount' => $taxCalc['sgst'],
                    'igst_amount' => $taxCalc['igst'],
                    'total_amount' => $taxCalc['total_amount'],
                ]);

                $subtotal += ($unitPrice * $qty);
                $totalTaxable += $taxable;
                $totalCgst += $taxCalc['cgst'];
                $totalSgst += $taxCalc['sgst'];
                $totalIgst += $taxCalc['igst'];

                if (in_array($item['item_type'] ?? '', ['lens', 'frame_with_lens'])) {
                    $hasLensesRequiringLab = true;
                }

                // Inventory Reservation
                if (!empty($item['product_id'])) {
                    $stock = InventoryStock::where('product_id', $item['product_id'])
                        ->where('branch_id', $orderData['branch_id'])
                        ->first();

                    if ($stock) {
                        $stock->increment('reserved_quantity', $qty);
                    }
                }
            }

            $grandTotal = round($totalTaxable + $totalCgst + $totalSgst + $totalIgst, 2);
            $order->update([
                'subtotal' => $subtotal,
                'taxable_amount' => $totalTaxable,
                'cgst_amount' => $totalCgst,
                'sgst_amount' => $totalSgst,
                'igst_amount' => $totalIgst,
                'total_amount' => $grandTotal,
                'balance_amount' => $grandTotal,
            ]);

            // Handle Advance Payment if provided
            if ($advancePayment && !empty($advancePayment['amount']) && $advancePayment['amount'] > 0) {
                $payAmount = min((float) $advancePayment['amount'], $grandTotal);
                Payment::create([
                    'company_id' => $order->company_id,
                    'branch_id' => $order->branch_id,
                    'sales_order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'receipt_number' => 'RCT-' . date('Ymd') . '-' . rand(1000, 9999),
                    'payment_date' => now()->toDateString(),
                    'amount' => $payAmount,
                    'payment_mode' => $advancePayment['payment_mode'] ?? 'cash',
                    'transaction_reference' => $advancePayment['transaction_reference'] ?? null,
                    'notes' => 'Advance Deposit for Order #' . $order->order_number,
                    'collected_by' => $orderData['salesperson_id'] ?? null,
                ]);

                $newBalance = round($grandTotal - $payAmount, 2);
                $order->update([
                    'paid_amount' => $payAmount,
                    'balance_amount' => $newBalance,
                    'payment_status' => $newBalance <= 0 ? 'paid' : 'partially_paid',
                ]);
            }

            // Create Lab Job Card if prescription lenses are included
            if ($hasLensesRequiringLab) {
                $prescription = $order->prescription;
                $measurements = $order->eyeMeasurement;

                LabJobCard::create([
                    'company_id' => $order->company_id,
                    'branch_id' => $order->branch_id,
                    'sales_order_id' => $order->id,
                    'job_number' => 'LJC-' . date('Ymd') . '-' . rand(1000, 9999),
                    'lab_type' => 'in_house',
                    'optical_specifications' => [
                        'prescription' => $prescription ? [
                            'od' => [
                                'sph' => $prescription->od_sph,
                                'cyl' => $prescription->od_cyl,
                                'axis' => $prescription->od_axis,
                                'add' => $prescription->od_add,
                            ],
                            'os' => [
                                'sph' => $prescription->os_sph,
                                'cyl' => $prescription->os_cyl,
                                'axis' => $prescription->os_axis,
                                'add' => $prescription->os_add,
                            ],
                            'type' => $prescription->prescription_type,
                        ] : null,
                        'measurements' => $measurements ? [
                            'pd_binocular' => $measurements->pd_binocular,
                            'pd_right' => $measurements->pd_right,
                            'pd_left' => $measurements->pd_left,
                            'fitting_height_r' => $measurements->fitting_height_right,
                            'fitting_height_l' => $measurements->fitting_height_left,
                        ] : null,
                    ],
                    'stage' => 'job_created',
                    'priority' => 'normal',
                ]);

                $order->update(['order_status' => 'in_lab']);
            }

            // Generate Official GST Tax Invoice
            Invoice::create([
                'company_id' => $order->company_id,
                'branch_id' => $order->branch_id,
                'sales_order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
                'invoice_date' => now()->toDateString(),
                'taxable_amount' => $totalTaxable,
                'cgst_amount' => $totalCgst,
                'sgst_amount' => $totalSgst,
                'igst_amount' => $totalIgst,
                'total_amount' => $grandTotal,
                'place_of_supply' => $branch->state_code ?? '27',
                'is_b2b' => !empty($customer->gstin),
                'customer_gstin' => $customer->gstin,
            ]);

            return $order->fresh(['items', 'payments', 'invoices', 'labJobCard']);
        });
    }
}

