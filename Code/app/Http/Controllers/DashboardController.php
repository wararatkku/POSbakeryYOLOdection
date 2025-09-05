<?php

namespace App\Http\Controllers;

use App\Models\Bakery;
use App\Models\OrderItem;
use App\Models\BakeryOrder;
use App\Models\StockBakery;
use App\Models\Productbuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function ownerHome(){
        $bakery = Bakery::all();
        // สินค้าขายดี
        $getBestsell = OrderItem::select('Bakery_ID', DB::raw('SUM(Sum_quantity) as total_quantity'))
            ->groupBy('Bakery_ID')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->value('Bakery_ID');
        $bakeryBestsell = $bakery->where('Bakery_ID', $getBestsell);
        // ยอดรวมต่อปี
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $thaiMonths = [
            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย', 5 => 'พ.ค',
            6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค', 9 => 'ก.ย', 10 => 'ต.ค',
            11 => 'พ.ย', 12 => 'ธ.ค'
        ];
        $dataSell = [];
        
        foreach ($thaiMonths as $month => $monthName) {
            // คำนวณยอดรวมจากฐานข้อมูล
            $total = BakeryOrder::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('Total_price'); 

            // เพิ่มข้อมูลในรูปแบบ [เดือน => ยอดรวม]
            $dataSell[] = [
                'month' => $monthName,
                'total' => $total,
            ];
        }
        $saleData = [];
        foreach ($thaiMonths as $month => $monthName) {
            // คำนวณยอดรวมจากฐานข้อมูล
            $total = Productbuy::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('Product_price'); // ใช้ 'Product_price' แทน 'Total_price'
    
            // เพิ่มข้อมูลในรูปแบบ [เดือน => ยอดรวม]
            $saleData[] = [
                'monthpb' => $monthName,
                'totalpb' => $total,
            ];
        }

            // ยอดรวมรายจ่ายในปีนี้ (ใช้ sum ของ Product_price)
            $totalMonthlyProductButyPrice = Productbuy::whereMonth('created_at', $currentMonth)
                ->sum(\DB::raw('Product_price'));
    
        return view('ownerHome', compact('bakeryBestsell','totalMonthlyProductButyPrice'), ['sellData' => $dataSell]+['saleData' => $saleData]);
    }

    public function dbSale() {
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->orderBy('created_at', 'desc')->get();
        // ยอดรวมต่อปี
        $currentYear = Carbon::now()->year;
        $thaiMonths = [
            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย', 5 => 'พ.ค',
            6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค', 9 => 'ก.ย', 10 => 'ต.ค',
            11 => 'พ.ย', 12 => 'ธ.ค'
        ];
        $dataSale = [];
        foreach ($thaiMonths as $month => $monthName) {
            // คำนวณยอดรวมจากฐานข้อมูล
            $total = BakeryOrder::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('Total_price'); 

            // เพิ่มข้อมูลในรูปแบบ [เดือน => ยอดรวม]
            $dataSale[] = [
                'month' => $monthName,
                'total' => $total,
            ];
        }
        $getOrders = BakeryOrder::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->with('orderItems')
            ->get();

        $totalQuantity = $getOrders->flatMap->orderItems->sum('Sum_quantity');
        $totalOrders = $getOrders->count();

        $avgItemsPerOrder = $totalOrders > 0 ? $totalQuantity / $totalOrders : 0;
        $avgItemsPerOrder = number_format($avgItemsPerOrder, 2);
        return view('db-sale', ['saleData' => $dataSale], compact('bakeryOrders', 'avgItemsPerOrder'));
    }

    public function dbBuy() {
        $productSales = Productbuy::orderBy('created_at', 'desc')->get();
        $TotalProductBuy = Productbuy::count();
    
        // ยอดรวมต่อปี
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $thaiMonths = [
            1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย', 5 => 'พ.ค',
            6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค', 9 => 'ก.ย', 10 => 'ต.ค',
            11 => 'พ.ย', 12 => 'ธ.ค'
        ];
        $saleData = [];
        foreach ($thaiMonths as $month => $monthName) {
            // คำนวณยอดรวมจากฐานข้อมูล
            $total = Productbuy::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->sum('Product_price'); // ใช้ 'Product_price' แทน 'Total_price'
    
            // เพิ่มข้อมูลในรูปแบบ [เดือน => ยอดรวม]
            $saleData[] = [
                'month' => $monthName,
                'total' => $total,
            ];
        }
        // ยอดรวมรายจ่ายในปีนี้ (ใช้ sum ของ Product_price)
        $totalMonthlyPrice = Productbuy::whereMonth('created_at', $currentMonth)
            ->sum(\DB::raw('Product_price')); // ใช้ sum ของ Product_price เท่านั้น

        // ยอดรวมรายจ่ายในปีนี้
        $totalYearPrice = Productbuy::whereYear('created_at', $currentYear)
            ->sum(\DB::raw('Product_price')); // คำนวณยอดรวมของสินค้าในปีนี้
    
        // ส่งข้อมูลไปยัง view
        return view('db-buy', compact('productSales', 'TotalProductBuy', 'saleData', 'totalMonthlyPrice', 'totalYearPrice'));
    }
    

    public function dbPro() {
        $bakery = Bakery::all();
        $TotalBakery = Bakery::count();
        // สินค้าขายดี
        $getBestsell = OrderItem::select('Bakery_ID', DB::raw('SUM(Sum_quantity) as total_quantity'))
            ->groupBy('Bakery_ID')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->value('Bakery_ID');
        $bakeryBestsell = $bakery->where('Bakery_ID', $getBestsell);
        // จำนวนสินค้าขายดี 5 อันดับ
        $bakeryChart = OrderItem::select('Bakery_ID', DB::raw('SUM(Sum_quantity) as total_quantity2'))
            ->groupBy('Bakery_ID')
            ->orderBy(DB::raw('SUM(Sum_quantity)'), 'desc')
            ->limit(5)
            ->with('bakery') 
            ->get();
        $filteredBakeryChart = $bakeryChart->filter(fn($item) => $item->bakery !== null);
        $labels = array_values($filteredBakeryChart->map(fn($item) => $item->bakery['Bakery_name'])->toArray()); 
        $data = array_values($filteredBakeryChart->map(fn($item) => $item->total_quantity2)->toArray());

        $totalStockM = StockBakery::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('Bakery_quantity');
        $totalStockY = StockBakery::whereYear('created_at', Carbon::now()->year)
            ->sum('Bakery_quantity');
        
        return view('db-pro', compact('bakeryBestsell', 'labels', 'data', 'TotalBakery', 'totalStockM', 'totalStockY'));
    }

    public function getMonthlySales() {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlySales = BakeryOrder::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('Total_price');
        return response()->json(['sales' => $monthlySales]);
    }
    public function getMonthlyOrders() {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlyOrders = BakeryOrder::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();
        return response()->json(['orders' => $monthlyOrders]);
    }
    public function getMonthlySaleQuan() {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $monthlySaleQuan = OrderItem::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('Sum_quantity');
        return response()->json(['saleQuan' => $monthlySaleQuan]);
    }
    public function MonthNow()
    {
        Carbon::setLocale('th'); // ตั้งค่าภาษาไทย
        $monthName = Carbon::now()->translatedFormat('F'); // ดึงชื่อเดือนภาษาไทย
        $yearNow = Carbon::now()->year;
        return response()->json(['monthName' => $monthName, 'year' => $yearNow]);
    }
}