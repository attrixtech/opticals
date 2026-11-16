<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['salesOrder.items', 'customer'])
            ->latest('invoice_date')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['salesOrder.items.product', 'salesOrder.payments', 'customer', 'branch.company'])->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }
}

