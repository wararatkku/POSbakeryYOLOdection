<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bakery;
use App\Models\StockBakery;
use App\Models\BakeryOrder;
use App\Models\OrderItem;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index(){
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->paginate(10);
        return view('history', compact('bakeryOrders'));
    }
    public function filter(Request $request)
    {
        $dateRange = $request->input('date_range');
        
        // หากมีการเลือกช่วงวันที่
        if ($dateRange) {
            // แยกวันที่เริ่มต้นและวันที่สิ้นสุดจาก string
            list($startDate, $endDate) = explode(' to ', $dateRange);

            // แปลงวันที่ให้เป็น Carbon instance
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            // กรองข้อมูลตามช่วงวันที่
            $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->paginate(10);
        } else {
            // ถ้าไม่มีการเลือกช่วงวันที่ ก็แสดงข้อมูลทั้งหมด
            $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->paginate(10);
        }

        // ส่งข้อมูลไปยัง view
        return view('history', compact('bakeryOrders'));
    }
    public function filterSellM(Request $request)
    {
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->paginate(8);
        $TotalOrders = BakeryOrder::count();
        $TotalPieces = OrderItem::sum('Sum_quantity');
        $TotalPrices = BakeryOrder::sum('Total_price');
        $dateRange = $request->input('date_range');
        
        // หากมีการเลือกช่วงวันที่
        if ($dateRange) {
            // แยกวันที่เริ่มต้นและวันที่สิ้นสุดจาก string
            list($startDate, $endDate) = explode(' to ', $dateRange);

            // แปลงวันที่ให้เป็น Carbon instance
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            // กรองข้อมูลตามช่วงวันที่
            $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->paginate(10);
        } else {
            $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->paginate(10);
        }

        return view('sellM', compact('bakeryOrders','TotalOrders','TotalPieces','TotalPrices'));
    }


    public function sellManage(){
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->paginate(8);
        $TotalOrders = BakeryOrder::count();
        $TotalPieces = OrderItem::sum('Sum_quantity');
        $TotalPrices = BakeryOrder::sum('Total_price');
        return view('sellM', compact('bakeryOrders','TotalOrders','TotalPieces','TotalPrices'));
    }

    public function OrderDetail(Request $request, $order_id){
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->find($order_id);
        return view('OrderDetail', compact('bakeryOrders'));
    }

    public function create() {
        $Bakery = Bakery::with(['stock' => function ($query) {
            $query->select('Bakery_ID', 'Bakery_quantity')
                  ->selectRaw('(SELECT SUM(COALESCE(sb.Bakery_quantity, 0)) 
                              FROM stock_bakeries sb 
                              WHERE sb.Bakery_ID = stock_bakeries.Bakery_ID 
                              AND sb.deleted_at IS NULL) AS totalS_quantity')
                  ->where('Bakery_quantity', '>', 0);
        }])->get();
        
        return view('createOrder', compact('Bakery'));
    }

    public function insert(Request $request) {
        $validatedData = $request->validate([
            'payment_type' => 'required|string',
            'sale_date' => 'required|date_format:Y-m-d\TH:i',
            'selectedBakeryIds' => 'required|string',
            'finalTotalPrice' => 'required|numeric',
            'b-quantity' => 'required|array',
            'b-ttp' => 'required|array',
        ]);

        $bakeryIds = explode(',', $validatedData['selectedBakeryIds']);
        $quantities = $validatedData['b-quantity'];
        $totalPrices = $validatedData['b-ttp'];
    
        foreach ($bakeryIds as $index => $bakeryId) {
            $totalStock = StockBakery::where('Bakery_ID', $bakeryId)->sum('Bakery_quantity');
            if ($totalStock < $quantities[$index]) {
                return redirect()->route('BakeryOrder')->with('error', 'Stock not enough');
            }
        }
    
        $payment = Payment::create([
            'Payment_Type' => $validatedData['payment_type'],
            'Payment_Total' => $validatedData['finalTotalPrice'],
            'created_at' => $validatedData['sale_date']
        ]);
    
        $bakery_order = BakeryOrder::create([
            'Total_price' => $validatedData['finalTotalPrice'],
            'Payment_ID' => $payment->Payment_ID,
            'created_at' => $validatedData['sale_date']
        ]);
    
        foreach ($bakeryIds as $index => $bakeryId) {
            $quantityNeeded = $quantities[$index];
            $stocks = StockBakery::where('Bakery_ID', $bakeryId)->orderBy('created_at', 'asc')->get();
    
            foreach ($stocks as $stock) {
                if ($quantityNeeded <= 0) break; 
    
                $deductAmount = min($stock->Bakery_quantity, $quantityNeeded);
                $stock->Bakery_quantity -= $deductAmount;
                $stock->Sell_quantity += $deductAmount;
                $quantityNeeded -= $deductAmount;
                $stock->save();
                if ($stock->Bakery_quantity == 0) {
                    $stock->delete();
                } 
            }
    
            OrderItem::create([
                'BakeryOrder_ID' => $bakery_order->BakeryOrder_ID,
                'Bakery_ID' => $bakeryId,
                'Sum_quantity' => $quantities[$index], 
                'Sum_price' => $totalPrices[$index], 
                'created_at' => $validatedData['sale_date']
            ]);
        }
        return redirect()->route('sellM')->with('status', 'success');
    }

    public function edit($order_id) {
        $bakeryOrders = BakeryOrder::with([
            'orderItems.bakery.stock' => function ($query) {
                $query->select('Bakery_ID', 'Bakery_quantity')
                    ->selectRaw('(SELECT SUM(COALESCE(sb.Bakery_quantity, 0)) 
                        FROM stock_bakeries sb 
                        WHERE sb.Bakery_ID = stock_bakeries.Bakery_ID 
                        AND sb.deleted_at IS NULL) AS totalS_quantity')
                    ->where('Bakery_quantity', '>', 0);
            },
            'payment'
        ])->find($order_id);
        $Bakery = Bakery::with(['stock' => function ($query) {
            $query->select('Bakery_ID', 'Bakery_quantity')
                    ->selectRaw('(SELECT SUM(COALESCE(sb.Bakery_quantity, 0)) 
                    FROM stock_bakeries sb 
                    WHERE sb.Bakery_ID = stock_bakeries.Bakery_ID 
                    AND sb.deleted_at IS NULL) AS totalS_quantity')
                  ->where('Bakery_quantity', '>', 0);
        }])->get();
        return view('editOrderDetail', compact('bakeryOrders', 'Bakery'));
    }

    public function update(Request $request, $order_id) {
        // dd($request->all());
        $validatedData = $request->validate([
            'payment_type' => 'required|string',
            'selectedBakeryIds' => 'required|string',
            'finalTotalPrice' => 'required|numeric',
            'b-quantity' => 'required|array',
            'b-ttp' => 'required|array',
        ]);
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery', 'payment'])->find($order_id);

        $payment = Payment::find($bakeryOrders->Payment_ID);
        $payment->Payment_Type = $validatedData['payment_type'];
        $payment->Payment_Total = $validatedData['finalTotalPrice'];
        $payment->save();

        $bakeryOrders->Total_price = $validatedData['finalTotalPrice'];
        $bakeryOrders->Payment_ID = $payment->Payment_ID;
        $bakeryOrders->save();

        $bakeryIds = explode(',', $validatedData['selectedBakeryIds']);
        $quantities = $validatedData['b-quantity'];
        $totalPrices = $validatedData['b-ttp'];
        $updatedBakeryIds = [];

        foreach ($bakeryIds as $index => $bakeryId) {
            $bakeryS = StockBakery::where('Bakery_ID', $bakeryId)->first();
            if (isset($quantities[$index]) && isset($totalPrices[$index])) {
                $orderItem = OrderItem::where('Bakery_ID', $bakeryId)
                                      ->where('BakeryOrder_ID', $order_id)
                                      ->first();
                
                if ($orderItem) {
                    if ($quantities[$index] > $orderItem->Sum_quantity) {
                        // กรณีเพิ่มจำนวนสินค้า ต้องลดจากสต็อก
                        $needed = $quantities[$index] - $orderItem->Sum_quantity;
                        
                        while ($needed > 0) {
                            $stock = StockBakery::where('Bakery_ID', $bakeryId)
                                                ->where('Bakery_quantity', '>', 0)
                                                ->orderBy('StockBakery_ID') // เรียงจากเก่าสุดไปใหม่สุด
                                                ->first();

                            if (!$stock) break; // ถ้าไม่มีสต็อกที่พอให้หยุด

                            $reduce = min($needed, $stock->Bakery_quantity);
                            $stock->Bakery_quantity -= $reduce;
                            $stock->Sell_quantity += $reduce;
                            $stock->save();

                            $needed -= $reduce;
                        }
                    } else {
                        // กรณีลดจำนวนสินค้า ต้องคืนกลับไปยังสต็อก
                        $returnQty = $orderItem->Sum_quantity - $quantities[$index];

                        while ($returnQty > 0) {
                            $stock = StockBakery::where('Bakery_ID', $bakeryId)
                                                ->where('Sell_quantity', '>', 0)
                                                ->orderByDesc('StockBakery_ID') // เรียงจากใหม่สุดไปเก่าสุด
                                                ->first();

                            if (!$stock && $returnQty > 0) {
                                // ถ้าไม่มีสต็อกที่เหลืออยู่ ให้คืนไปยังสต็อกที่ถูกลบด้วย soft delete ล่าสุด
                                $deletedStock = StockBakery::onlyTrashed()
                                                        ->where('Bakery_ID', $bakeryId)
                                                        ->latest('deleted_at')
                                                        ->first();

                                if ($deletedStock) {
                                    $restoreAmount = min($returnQty, $deletedStock->Sell_quantity);
                                    $deletedStock->restore(); // กู้คืนสต็อก
                                    $deletedStock->Bakery_quantity += $restoreAmount;
                                    $deletedStock->Sell_quantity -= $restoreAmount;
                                    $deletedStock->save();
                                    $returnQty -= $restoreAmount;
                                }
                                break;
                            }

                            $restoreAmount = min($returnQty, $stock->Sell_quantity);
                            $stock->Sell_quantity -= $restoreAmount;
                            $stock->Bakery_quantity += $restoreAmount;
                            $stock->save();

                            $returnQty -= $restoreAmount;
                        }
                    }

                    // อัปเดต OrderItem
                    $orderItem->Sum_quantity = $quantities[$index];
                    $orderItem->Sum_price = $totalPrices[$index];
                    $orderItem->save();
                } else {
                    $needed = $quantities[$index];

                    while ($needed > 0) {
                        $stock = StockBakery::where('Bakery_ID', $bakeryId)
                                            ->where('Bakery_quantity', '>', 0)
                                            ->orderBy('StockBakery_ID') // เลือกจากเก่าสุดก่อน
                                            ->first();

                        if (!$stock) break; // ถ้าไม่มีสต็อกเหลืออยู่ให้หยุด

                        $useQty = min($needed, $stock->Bakery_quantity);
                        $stock->Bakery_quantity -= $useQty;
                        $stock->Sell_quantity += $useQty;
                        $stock->save();

                        $needed -= $useQty;
                    }

                    // สร้าง OrderItem ใหม่
                    OrderItem::create([
                        'Bakery_ID' => $bakeryId,
                        'BakeryOrder_ID' => $order_id,
                        'Sum_quantity' => $quantities[$index],
                        'Sum_price' => $totalPrices[$index],
                    ]);
                }
            
                // Track the bakery ID that was updated or added
                $updatedBakeryIds[] = $bakeryId;
            }
        }
    
        // Delete items that were not updated or added (if any)
        $orderItems = OrderItem::where('BakeryOrder_ID', $order_id)
                               ->whereNotIn('Bakery_ID', $updatedBakeryIds)
                               ->get();

        foreach ($orderItems as $restore) {
            $returnQty = $restore->Sum_quantity;

            // คืนสินค้ากลับไปที่สต็อกที่ยังมีอยู่ก่อน
            while ($returnQty > 0) {
                $stock = StockBakery::where('Bakery_ID', $restore->Bakery_ID)
                                    ->where('Sell_quantity', '>', 0) // ค้นหาสต็อกที่ยังขายออกไป
                                    ->orderByDesc('StockBakery_ID') // คืนไปที่ใหม่สุดก่อน
                                    ->first();

                if ($stock) {
                    $restoreAmount = min($returnQty, $stock->Sell_quantity);
                    $stock->Sell_quantity -= $restoreAmount;
                    $stock->Bakery_quantity += $restoreAmount;
                    $stock->save();

                    $returnQty -= $restoreAmount;
                } else {
                    // ถ้าไม่มีสต็อกที่รับคืนได้แล้ว ให้คืนไปยังสต็อกที่ถูกลบ (Soft Delete)
                    $deletedStock = StockBakery::where('Bakery_ID', $restore->Bakery_ID)
                                            ->onlyTrashed() // ค้นหาเฉพาะสต็อกที่ถูกลบ
                                            ->orderByDesc('deleted_at') // เอาสต็อกที่ถูกลบล่าสุดก่อน
                                            ->first();

                    if ($deletedStock) {
                        $restoreAmount = min($returnQty, abs($deletedStock->Sell_quantity));
                        $deletedStock->restore(); // กู้คืนสต็อกก่อน
                        $deletedStock->Sell_quantity -= $restoreAmount;
                        $deletedStock->Bakery_quantity += $restoreAmount;
                        $deletedStock->save();

                        $returnQty -= $restoreAmount;
                    } else {
                        break; // ถ้าไม่มีสต็อกที่ลบแล้วให้หยุด
                    }
                }
            }
        }
        OrderItem::where('BakeryOrder_ID', $order_id)->whereNotIn('Bakery_ID', $updatedBakeryIds)->delete();

        return redirect()->route('sellM', $order_id)->with('status', 'update');
    }

    public function delete(Request $request) {
        $bakeryOrders = BakeryOrder::with(['orderItems.bakery.stock', 'payment'])->find($request->delete_order_id);
        if ($bakeryOrders) {
            foreach ($bakeryOrders->orderItems as $orderlist) {
                $bakeryS = $orderlist->bakery->stock->first();
                $bakeryS->Bakery_quantity += $orderlist->Sum_quantity;
                $bakeryS->Sell_quantity -= $orderlist->Sum_quantity;
                $bakeryS->save();
            }
            $bakeryOrders->orderItems()->delete();  
            $bakeryOrders->payment()->delete();     
            $bakeryOrders->delete();
        }
        return redirect('/SellManage')->with('status','delete');
    }
}
