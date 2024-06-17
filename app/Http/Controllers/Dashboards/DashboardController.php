<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\SheetOrder;
use App\Models\Unit;

use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $orders = Order::count();
        $products = Product::count();
$sheetorder = SheetOrder::count();
$dispatchedCount = SheetOrder::where('status', 'dispatched')->count();
$cancelledCount = SheetOrder::where('status', 'cancelled')->count();
$deliveredCount = SheetOrder::where('status', 'delivered')->count();
        $purchases = Purchase::where("user_id", auth()->id())->count();
        $todayPurchases = Purchase::whereDate('date', today()->format('Y-m-d'))->count();
        $todayProducts = Product::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayQuotations = Quotation::whereDate('created_at', today()->format('Y-m-d'))->count();
        $todayOrders = Order::whereDate('created_at', today()->format('Y-m-d'))->count();
$unit = Unit::count();
$rider= Distributor::where('type','rider')->count();
$agent= Distributor::where('type','agent')->count();


        $categories = Category::count();
        $quotations = Quotation::where("user_id", auth()->id())->count();

        return view('dashboard', [
            'products' => $products,
            'orders' => $orders,
            'purchases' => $purchases,
            'todayPurchases' => $todayPurchases,
            'todayProducts' => $todayProducts,
            'todayQuotations' => $todayQuotations,
            'todayOrders' => $todayOrders,
            'categories' => $categories,
            'quotations' => $quotations,
            'sheetorder' =>$sheetorder,
            'cancelledCount'=>$cancelledCount,
            'deliveredCount'=>$deliveredCount,
            'dispatchedCount'=>$dispatchedCount,
            'unit'=>$unit,
            'rider'=>$rider,
            'agent'=>$agent
        ]);
    }
}
