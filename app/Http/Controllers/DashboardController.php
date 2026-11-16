<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Customer;
use App\Models\InventoryStock;
use App\Models\LabJobCard;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\QualityControl;
use App\Models\Repair;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto-authenticate default admin if not authenticated
        if (!Auth::check()) {
            $defaultUser = User::where('role', 'admin')->first() ?? User::first();
            if (!$defaultUser) {
                $defaultUser = User::create([
                    'name' => 'WebotApp Admin',
                    'email' => 'admin@webotapp.test',
                    'password' => bcrypt('secret'),
                    'role' => 'admin',
                ]);
            }
            Auth::login($defaultUser);
        }

        $user = Auth::user();
        $today = now()->toDateString();

        // 1. Data for Optometrist Clinical Dashboard
        if ($user && $user->isOptometrist()) {
            $todayPrescriptionsCount = Prescription::whereDate('prescription_date', $today)->count();
            $totalPrescriptionsCount = Prescription::count();
            $recentPrescriptions = Prescription::with('customer')
                ->latest('prescription_date')
                ->take(8)
                ->get();
            $patientsWaitingRefraction = Customer::doesntHave('prescriptions')->count();
            $progressivePrescriptionsCount = Prescription::where('prescription_type', 'progressive')->count();
            $singleVisionCount = Prescription::where('prescription_type', 'single_vision')->count();

            return view('dashboard.optometrist', compact(
                'user',
                'todayPrescriptionsCount',
                'totalPrescriptionsCount',
                'recentPrescriptions',
                'patientsWaitingRefraction',
                'progressivePrescriptionsCount',
                'singleVisionCount'
            ));
        }

        // 2. Data for Laboratory Technician Dashboard
        if ($user && $user->isLabTechnician()) {
            $totalLabJobs = LabJobCard::count();
            $jobsInSurfacing = LabJobCard::where('stage', 'surfacing')->count();
            $jobsInEdging = LabJobCard::where('stage', 'edging')->count();
            $jobsQcPending = LabJobCard::where('stage', 'qc_pending')->count();
            $jobsCompleted = LabJobCard::where('stage', 'completed')->count();
            $jobsRework = LabJobCard::where('stage', 'rework')->count();

            $activeLabJobs = LabJobCard::with(['salesOrder.customer', 'technician', 'qualityControl'])
                ->whereNotIn('stage', ['completed'])
                ->latest()
                ->get();

            $pendingRepairs = Repair::with('customer')
                ->whereIn('status', ['received', 'in_repair'])
                ->latest()
                ->get();

            return view('dashboard.laboratory', compact(
                'user',
                'totalLabJobs',
                'jobsInSurfacing',
                'jobsInEdging',
                'jobsQcPending',
                'jobsCompleted',
                'jobsRework',
                'activeLabJobs',
                'pendingRepairs'
            ));
        }

        // 3. Data for Cashier / Sales Dashboard
        if ($user && $user->isCashier()) {
            $todaySales = SalesOrder::whereDate('order_date', $today)->sum('total_amount');
            $todayPayments = Payment::whereDate('payment_date', $today)->sum('amount');
            $cashPayments = Payment::whereDate('payment_date', $today)->where('payment_mode', 'cash')->sum('amount');
            $upiPayments = Payment::whereDate('payment_date', $today)->where('payment_mode', 'upi')->sum('amount');
            $cardPayments = Payment::whereDate('payment_date', $today)->where('payment_mode', 'card')->sum('amount');

            $readyForDeliveryOrders = SalesOrder::with(['customer', 'items'])
                ->where('order_status', 'ready_for_delivery')
                ->latest()
                ->get();

            $recentReceipts = Payment::with(['customer', 'salesOrder'])
                ->whereDate('payment_date', $today)
                ->latest()
                ->take(8)
                ->get();

            $cashRegister = CashRegister::where('user_id', $user->id)
                ->where('status', 'open')
                ->latest()
                ->first();

            return view('dashboard.cashier', compact(
                'user',
                'todaySales',
                'todayPayments',
                'cashPayments',
                'upiPayments',
                'cardPayments',
                'readyForDeliveryOrders',
                'recentReceipts',
                'cashRegister'
            ));
        }

        // 4. Data for Store Manager
        if ($user && $user->isManager()) {
            $todaySales = SalesOrder::whereDate('order_date', $today)->sum('total_amount');
            $monthSales = SalesOrder::whereYear('order_date', now()->year)
                ->whereMonth('order_date', now()->month)
                ->sum('total_amount');
            $totalOrdersCount = SalesOrder::count();
            $ordersInLabCount = LabJobCard::whereNotIn('stage', ['completed'])->count();
            $readyForDeliveryCount = SalesOrder::where('order_status', 'ready_for_delivery')->count();
            $pendingRepairsCount = Repair::whereIn('status', ['received', 'in_repair'])->count();

            $recentOrders = SalesOrder::with(['customer', 'prescription', 'items'])
                ->latest()
                ->take(6)
                ->get();

            $activeLabJobs = LabJobCard::with(['salesOrder.customer', 'technician'])
                ->whereNotIn('stage', ['completed'])
                ->latest()
                ->take(5)
                ->get();

            $lowStockProducts = Product::with(['stocks'])
                ->where('is_active', true)
                ->get()
                ->filter(function ($product) {
                    return $product->totalQuantity() <= $product->min_stock_alert;
                });

            return view('dashboard.manager', compact(
                'user',
                'todaySales',
                'monthSales',
                'totalOrdersCount',
                'ordersInLabCount',
                'readyForDeliveryCount',
                'pendingRepairsCount',
                'recentOrders',
                'activeLabJobs',
                'lowStockProducts'
            ));
        }

        // 5. Default: Super Admin / Owner Dashboard (Full Enterprise Executive View)
        $todaySales = SalesOrder::whereDate('order_date', $today)->sum('total_amount');
        $monthSales = SalesOrder::whereYear('order_date', now()->year)
            ->whereMonth('order_date', now()->month)
            ->sum('total_amount');
        
        $totalOrdersCount = SalesOrder::count();
        $ordersInLabCount = LabJobCard::whereNotIn('stage', ['completed'])->count();
        $readyForDeliveryCount = SalesOrder::where('order_status', 'ready_for_delivery')->count();
        $pendingRepairsCount = Repair::whereIn('status', ['received', 'in_repair'])->count();
        $totalCustomersCount = Customer::count();

        $totalValuation = Product::with('stocks')->get()->reduce(function ($carry, $p) {
            return $carry + ($p->totalQuantity() * $p->purchase_price);
        }, 0);

        $lowStockProducts = Product::with(['stocks'])
            ->where('is_active', true)
            ->get()
            ->filter(function ($product) {
                return $product->totalQuantity() <= $product->min_stock_alert;
            });

        $recentOrders = SalesOrder::with(['customer', 'prescription', 'items'])
            ->latest()
            ->take(6)
            ->get();

        $recentPrescriptions = Prescription::with('customer')
            ->latest('prescription_date')
            ->take(5)
            ->get();

        $activeLabJobs = LabJobCard::with(['salesOrder.customer', 'technician'])
            ->whereNotIn('stage', ['completed'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'user',
            'todaySales',
            'monthSales',
            'totalOrdersCount',
            'ordersInLabCount',
            'readyForDeliveryCount',
            'pendingRepairsCount',
            'totalCustomersCount',
            'totalValuation',
            'lowStockProducts',
            'recentOrders',
            'recentPrescriptions',
            'activeLabJobs'
        ));
    }
}
