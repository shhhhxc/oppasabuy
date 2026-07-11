<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\SellerVerification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Product;
use App\Models\SellerTarget;
use App\Models\Transaction;

class SellerDashboardController extends Controller
{
    private function getRevenueStatuses()
    {
        return [
            'paid',
            'completed',
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $store = SellerVerification::where('user_id', $user->id)->first();

        if ($store) {
            $store = $store->fresh();
        }

        $totalIncome = (float) ($user->balance ?? 0);
        $validStatuses = $this->getRevenueStatuses();

        $pendingOrders = Order::where('seller_id', $user->id)
            ->whereIn('status', [
                Order::STATUS_AWAITING_VIDEO,
                Order::STATUS_VIDEO_UPLOADED,
                Order::STATUS_AWAITING_PAYMENT_QR,
                Order::STATUS_RECEIPT_UPLOADED
            ])
            ->with(['items.product', 'buyer'])
            ->latest()
            ->get();

        $incomingReservations = Reservation::where('seller_id', $user->id)
            ->where('status', 'pending')
            ->with(['product', 'slot', 'buyer'])
            ->latest()
            ->get();

        $months = [];
        $salesValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $salesValues[] = (float) Order::where('seller_id', $user->id)
                ->whereIn('status', $validStatuses)
                ->whereNotNull('paid_at')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('total_price');
        }

        $salesToday = (float) Order::where('seller_id', $user->id)
            ->whereIn('status', $validStatuses)
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', today())
            ->sum('total_price');

        $itemsSoldToday = (int) OrderItem::whereHas('order', function($q) use ($user, $validStatuses) {
                $q->where('seller_id', $user->id)
                  ->whereIn('status', $validStatuses)
                  ->whereNotNull('paid_at')
                  ->whereDate('paid_at', today());
            })->sum('quantity');

        $totalExpenses = 0;
        $inStockCount = Product::where('seller_id', $user->id)->count();

        return view('seller.dashboard', compact(
            'store',
            'pendingOrders',
            'incomingReservations',
            'totalIncome',
            'months',
            'salesValues',
            'salesToday',
            'itemsSoldToday',
            'totalExpenses',
            'inStockCount'
        ));
    }

    public function dailyReport()
    {
        $user = auth()->user();
        $sellerId = $user->id;
        $store = SellerVerification::where('user_id', $sellerId)->first();
        $validStatuses = $this->getRevenueStatuses();

        $todayOrders = Order::where('seller_id', $sellerId)
            ->whereIn('status', $validStatuses)
            ->whereNotNull('paid_at')
            ->whereDate('paid_at', today())
            ->get();

        $totalOrders = $todayOrders->count();
        $totalProfit = (float) $todayOrders->sum('total_price');

        $productPerformance = OrderItem::whereHas('order', function($q) use ($sellerId, $validStatuses) {
                $q->where('seller_id', $sellerId)
                  ->whereIn('status', $validStatuses)
                  ->whereNotNull('paid_at')
                  ->whereDate('paid_at', today());
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(price * quantity) as total_revenue'))
            ->groupBy('product_id')
            ->with('product')
            ->get();

        $dailyLabels = [];
        $dailySales = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyLabels[] = $date->format('d M');

            $sum = Order::where('seller_id', $sellerId)
                ->whereIn('status', $validStatuses)
                ->whereNotNull('paid_at')
                ->whereDate('paid_at', $date->format('Y-m-d'))
                ->sum('total_price');

            $dailySales[] = (float) $sum;
        }

        $monthlyLabels = [];
        $monthlySales = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyLabels[] = $date->format('M');

            $monthlySales[] = (float) Order::where('seller_id', $sellerId)
                ->whereIn('status', $validStatuses)
                ->whereNotNull('paid_at')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('total_price');
        }

        return view('seller.reports.daily', compact(
            'store',
            'totalOrders',
            'totalProfit',
            'productPerformance',
            'dailyLabels',
            'dailySales',
            'monthlyLabels',
            'monthlySales'
        ));
    }

    public function targetGoals()
    {
        $user = auth()->user();
        $store = SellerVerification::where('user_id', $user->id)->first();

        $monthlyTargets = SellerTarget::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('period', 'monthly')
                      ->orWhere('type', 'Task');
            })
            ->get();

        $annualTargets = SellerTarget::where('user_id', $user->id)
            ->where('period', 'annual')
            ->where('type', '!=', 'Task')
            ->get();

        return view('seller.reports.goals', compact('store', 'monthlyTargets', 'annualTargets'));
    }

    public function storeGoal(Request $request)
    {
        if ($request->input('is_milestone') == '1') {
            $request->validate([
                'period' => 'required|string|max:255',
            ]);

            auth()->user()->targets()->create([
                'type'          => 'Task',
                'target_value'  => 1,
                'current_value' => 0,
                'period'        => $request->period,
                'user_id'       => auth()->id(),
            ]);
        } else {
            $request->validate([
                'type'         => 'required|string|max:255',
                'target_value' => 'required|numeric|min:1',
                'period'       => 'required|in:monthly,annual',
            ]);

            auth()->user()->targets()->create([
                'type'          => $request->type,
                'target_value'  => $request->target_value,
                'current_value' => 0,
                'period'        => $request->period,
                'user_id'       => auth()->id(),
            ]);
        }

        return back()->with('success', 'Entry saved successfully!');
    }

    public function update(Request $request, $id)
    {
        $goal = SellerTarget::where('user_id', auth()->id())->findOrFail($id);
        $newValue = $goal->current_value >= 1 ? 0 : 1;
        $goal->update(['current_value' => $newValue]);

        return redirect()->back();
    }

    public function invoices()
    {
        $user = auth()->user();
        $store = SellerVerification::where('user_id', $user->id)->first();

        $orders = Order::where('seller_id', $user->id)
            ->with('buyer')
            ->latest()
            ->get();

        return view('seller.reports.invoice_index', compact('orders', 'store'));
    }

    public function generateInvoice($orderId)
    {
        $user = auth()->user();

        $order = Order::where('seller_id', $user->id)
            ->where('id', $orderId)
            ->with(['items.product', 'buyer'])
            ->firstOrFail();

        $store = SellerVerification::where('user_id', $user->id)->first();

        return view('seller.reports.invoice', compact('order', 'store'));
    }

    public function financialStatement()
    {
        $user = auth()->user();
        $store = SellerVerification::where('user_id', $user->id)->first();
        $validStatuses = $this->getRevenueStatuses();

        $revenue = (float) Order::where('seller_id', $user->id)
            ->whereIn('status', $validStatuses)
            ->whereNotNull('paid_at')
            ->sum('total_price');

        $expenses = 0;

        $cashFlow = Order::where('seller_id', $user->id)
            ->whereIn('status', $validStatuses)
            ->whereNotNull('paid_at')
            ->with('buyer')
            ->latest('paid_at')
            ->take(10)
            ->get();

        return view('seller.reports.financial', compact('store', 'revenue', 'expenses', 'cashFlow'));
    }

    public function storeFinancial(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        Transaction::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'is_manual' => true
        ]);

        return back()->with('success', 'Transaction recorded successfully.');
    }

    public function uploadIntroVideo(Request $request)
    {
        $request->validate([
            'intro_video' => 'required|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        $user = Auth::user();
        $verification = SellerVerification::where('user_id', $user->id)->first();

        if (!$verification) {
            return redirect()->back()->with('error', 'Seller profile not found.');
        }

        if ($request->hasFile('intro_video')) {
            if ($verification->video_intro_path) {
                Storage::disk('public')->delete($verification->video_intro_path);
            }

            $path = $request->file('intro_video')->store('seller_intros', 'public');

            $verification->update([
                'video_intro_path' => $path
            ]);
        }

        return redirect()->route('seller.dashboard')->with('success', 'Store video uploaded successfully!');
    }

    public function upgradePage()
    {
        $user = auth()->user();
        $store = SellerVerification::where('user_id', $user->id)->first();
        $currentPlan = $store->seller_plan ?? 'free';

        return view('seller.upgrade', compact('store', 'currentPlan'));
    }

    public function updatePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:free,basic,pro,premium'
        ]);

        $user = auth()->user();
        $store = SellerVerification::where('user_id', $user->id)->first();

        if ($store) {
            $store->update([
                'plan' => $request->plan
            ]);

            return redirect()->back()->with('success', 'Plan upgraded to ' . ucfirst($request->plan) . ' successfully!');
        }

        return redirect()->back()->with('error', 'Seller profile not found.');
    }
}