<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PromptPayQR;
use App\Models\StockBakery;
use App\Models\Payment;
use App\Models\BakeryOrder;
use App\Models\OrderItem;
use App\Models\Bakery;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;


class EditOrderController extends Controller
{
    public function index(Request $request)
    {
        $productData = json_decode($request->input('product_data'), true);
        session(['productData' => $productData]);
        // คำนวณราคารวม
        $totalPrice = 0;
        foreach ($productData as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }
        return view('editorder', compact('productData', 'totalPrice'));
    }

    public function increaseQuantity(Request $request)
    {
        // ดึงข้อมูลสินค้าจาก session
        $productData = session('productData');
        $id = $request->input('id');
        $mask = $request->input('mask');

        // วนลูปหาสินค้าที่ต้องการอัปเดต
        foreach ($productData as &$item) {
            if ($id == $item['id']) {
                if ($mask == '+') {
                    // เพิ่มจำนวนสินค้า
                    $item['quantity'] = strval(intval($item['quantity']) + 1);
                } elseif ($mask == '-') {
                    // ลดจำนวนสินค้า
                    $item['quantity'] = strval(intval($item['quantity']) - 1);
                }
                break;
            }
        }

        // อัปเดตข้อมูลใน session
        session()->put('productData', $productData);

        $totalPrice = 0;
        foreach ($productData as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }
        $totalPrice = number_format($totalPrice, 2);

        // ส่งข้อมูลไปยัง view editorder.blade.php
        return view('editorder', compact('productData', 'totalPrice'));
    }

    public function genQR(Request $request)
    {
        require_once('C:\xampp\htdocs\bakery-PJ\lib\PromptPayQR.php');
        $PromptPayQR = new PromptPayQR(); // new object
        $amount = $request->input('sumPrice');
        $PromptPayQR->size = 8; // Set QR code size to 8
        $PromptPayQR->id = '0842477317'; // PromptPay ID
        $PromptPayQR->amount = (float)$amount; // Set amount (not necessary)
        $src = $PromptPayQR->generate();
        return response()->json(['qrSrc' => $src]);
    }

    public function CashPay(Request $request)
    {
        $productData = $request->input('items');
        // return response()->json(['savedOrder' => $items]);
        // $productData = session('productData');
        $totalPrice = 0;

        // Calculate total price and save payment information
        foreach ($productData as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }

        foreach ($productData as $orderItem) {
            $totalStock = StockBakery::where('Bakery_ID', $orderItem['id'])->sum('Bakery_quantity');
            if ($totalStock < $orderItem['quantity']) {
                return redirect()->route('detect')->with('error', 'Stock not enough for ' . $orderItem['id']);
            }
        }

        $payment = new Payment();
        $payment->Payment_Type = "เงินสด";
        $payment->Payment_Total = $totalPrice;
        $payment->save();

        // Save bakery order and associated order items
        $bakery_order = new BakeryOrder();
        $bakery_order->Total_price = $totalPrice;
        $bakery_order->Payment_ID = $payment->Payment_ID;
        $bakery_order->save();

        foreach ($productData as $orderItem) {
            // ตรวจสอบให้แน่ใจว่ามีจำนวนมากกว่าศูนย์
            if ($orderItem['quantity'] > 0) {
                $quantityNeeded = $orderItem['quantity'];
    
                // ดึงข้อมูลสต็อกของสินค้า
                $stocks = StockBakery::where('Bakery_ID', $orderItem['id'])->orderBy('created_at', 'asc')->get();
    
                // ลูปเพื่อลดจำนวนสต็อก
                foreach ($stocks as $stock) {
                    if ($quantityNeeded <= 0) break;
    
                    // หาจำนวนที่ต้องการลดจากสต็อก
                    $deductAmount = min($stock->Bakery_quantity, $quantityNeeded);
                    $stock->Bakery_quantity -= $deductAmount;
                    $stock->Sell_quantity += $deductAmount;
                    $quantityNeeded -= $deductAmount;
                    $stock->save();
    
                    // ถ้าสต็อกหมดให้ลบ
                    if ($stock->Bakery_quantity == 0) {
                        $stock->delete();
                    }
                }
    
                // ถ้าหากยังเหลือจำนวนที่ต้องการมากกว่า 0 หมายความว่าสต็อกไม่พอ
                if ($quantityNeeded > 0) {
                    // ยกเลิกการบันทึกคำสั่งซื้อและการชำระเงิน
                    $payment->delete();
                    $bakery_order->delete();
                    return view('detect'); // แสดงหน้าผิดพลาด
                }
    
                // สร้างหรืออัปเดตข้อมูล OrderItem
                $existingOrderItem = OrderItem::where('BakeryOrder_ID', $bakery_order->BakeryOrder_ID)
                    ->where('Bakery_ID', $orderItem['id'])
                    ->first();
    
                if ($existingOrderItem) {
                    // ถ้ามี order item อยู่แล้วให้ปรับปรุงข้อมูล
                    $existingOrderItem->Sum_quantity = $orderItem['quantity'];
                    $existingOrderItem->Sum_price = $orderItem['price'] * $orderItem['quantity'];
                    $existingOrderItem->save();
                } else {
                    // ถ้ายังไม่มี order item ให้สร้างใหม่
                    OrderItem::create([
                        'BakeryOrder_ID' => $bakery_order->BakeryOrder_ID,
                        'Bakery_ID' => $orderItem['id'],
                        'Sum_quantity' => $orderItem['quantity'],
                        'Sum_price' => $orderItem['price'] * $orderItem['quantity']
                    ]);
                }
            }
        }

        // Retrieve the saved order with related data and return as JSON response
        $savedOrder = BakeryOrder::with(['orderItems.bakery', 'payment'])->find($bakery_order->BakeryOrder_ID);

        return response()->json(['savedOrder' => $savedOrder]);
    }


    public function PromptPay(Request $request)
    {
        $productData = $request->input('items');
        $totalPrice = 0;

        // Calculate total price and save payment information
        foreach ($productData as $product) {
            $totalPrice += $product['price'] * $product['quantity'];
        }

        foreach ($productData as $checkItem) {
            $totalStock = StockBakery::where('Bakery_ID', $checkItem['id'])->sum('Bakery_quantity');
            if ($totalStock < $checkItem['quantity']) {
                return redirect()->route('detect')->with('error', 'Stock not enough for ' . $checkItem['id']);
            }
        }

        $payment = new Payment();
        $payment->Payment_Type = "PromptPay";
        $payment->Payment_Total = $totalPrice;
        $payment->save();

        // Save bakery order and associated order items
        $bakery_order = new BakeryOrder();
        $bakery_order->Total_price = $totalPrice;
        $bakery_order->Payment_ID = $payment->Payment_ID;
        $bakery_order->save();

        foreach ($productData as $orderItem) {
            // ตรวจสอบให้แน่ใจว่ามีจำนวนมากกว่าศูนย์
            if ($orderItem['quantity'] > 0) {
                $quantityNeeded = $orderItem['quantity'];
    
                // ดึงข้อมูลสต็อกของสินค้า
                $stocks = StockBakery::where('Bakery_ID', $orderItem['id'])->orderBy('created_at', 'asc')->get();
    
                // ลูปเพื่อลดจำนวนสต็อก
                foreach ($stocks as $stock) {
                    if ($quantityNeeded <= 0) break;
    
                    // หาจำนวนที่ต้องการลดจากสต็อก
                    $deductAmount = min($stock->Bakery_quantity, $quantityNeeded);
                    $stock->Bakery_quantity -= $deductAmount;
                    $stock->Sell_quantity += $deductAmount;
                    $quantityNeeded -= $deductAmount;
                    $stock->save();
    
                    // ถ้าสต็อกหมดให้ลบ
                    if ($stock->Bakery_quantity == 0) {
                        $stock->delete();
                    }
                }
    
                // ถ้าหากยังเหลือจำนวนที่ต้องการมากกว่า 0 หมายความว่าสต็อกไม่พอ
                if ($quantityNeeded > 0) {
                    // ยกเลิกการบันทึกคำสั่งซื้อและการชำระเงิน
                    $payment->delete();
                    $bakery_order->delete();
                    return view('detect'); // แสดงหน้าผิดพลาด
                }
    
                // สร้างหรืออัปเดตข้อมูล OrderItem
                $existingOrderItem = OrderItem::where('BakeryOrder_ID', $bakery_order->BakeryOrder_ID)
                    ->where('Bakery_ID', $orderItem['id'])
                    ->first();
    
                if ($existingOrderItem) {
                    // ถ้ามี order item อยู่แล้วให้ปรับปรุงข้อมูล
                    $existingOrderItem->Sum_quantity = $orderItem['quantity'];
                    $existingOrderItem->Sum_price = $orderItem['price'] * $orderItem['quantity'];
                    $existingOrderItem->save();
                } else {
                    // ถ้ายังไม่มี order item ให้สร้างใหม่
                    OrderItem::create([
                        'BakeryOrder_ID' => $bakery_order->BakeryOrder_ID,
                        'Bakery_ID' => $orderItem['id'],
                        'Sum_quantity' => $orderItem['quantity'],
                        'Sum_price' => $orderItem['price'] * $orderItem['quantity']
                    ]);
                }
            }
        }
        $savedOrder = BakeryOrder::with(['orderItems.bakery', 'payment'])->find($bakery_order->BakeryOrder_ID);

        return response()->json(['savedOrder' => $savedOrder]);;
    }

    public function viewInvoice(Request $request, $order_id)
    {
        $cusName = $request->input('cusName');
        $cusAdd = $request->input('cusAdd');
        $taxID = $request->input('taxID');
        $cusUser = $request->input('cusUser');
        if ($taxID == "") {
            $taxID = "-";
        }
        $InvoiceOR = BakeryOrder::with(['orderItems.bakery', 'payment'])->find($order_id);
        return view('genInvoice', compact('cusName', 'cusAdd', 'taxID', 'InvoiceOR', 'cusUser'));
    }

    public function printInvoice(Request $request, $order_id)
    {
        $cusName = $request->input('cusName');
        $cusAdd = $request->input('cusAdd');
        $taxID = $request->input('taxID');
        $cusUser = $request->input('cusUser');
        if ($taxID == "") {
            $taxID = "-";
        }
        $InvoiceOR = BakeryOrder::with(['orderItems.bakery', 'payment'])->find($order_id);
        $data = [
            'InvoiceOR' => $InvoiceOR,
            'cusName' => $cusName,
            'cusAdd' => $cusAdd,
            'taxID' => $taxID,
            'cusUser' => $cusUser
        ];
        $pdf = Pdf::loadView('genInvoice', $data);
        $today = Carbon::now()->format('d-m-Y');
        return $pdf->download('invoice-' . $InvoiceOR->BakeryOrder_ID . '-' . $today . '.pdf');
    }

    public function clearOrder(Request $request)
    {
        session()->forget('savedOrder');
        return response()->json(['status' => 'success']);
    }
}
