<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bakery;
use App\Models\OrderItem;

class BakeryController extends Controller
{
    public function index(){
        $bakery = Bakery::with('stock')->withSum('stock as totalS_quantity', 'Bakery_quantity')->get();
        $IPS_array = [];
        foreach ($bakery as $BItem) {
            $IP = $BItem->IP_status;
            if($IP == 0) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/cancel.png');
            } if ($IP == 1) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/checked.png');
            }
        }
        return view('bakery', compact('bakery', 'IPS_array'));
    }

    public function searchBakery(Request $request) {
        $searchTerm = $request->input('findB');
        $bakery = Bakery::with('stock')
            ->where('Bakery_name', 'LIKE', '%' . $searchTerm . '%')->get();
        $output = '';
        $IPS_array = [];
    
        if ($bakery->isEmpty()) {
            $output = '<p style="text-align: center; margin-top: 100px; margin-left: 100px; font-size: 20px;">ไม่พบข้อมูลเบเกอรี่ที่กำลังค้นหา</p>';
        } else {
            foreach ($bakery as $item) {
                $IP = $item->IP_status;
                if($IP == 0) {
                    $IPS_array[$item->Bakery_ID] = asset('images/cancel.png');
                } if ($IP == 1) {
                    $IPS_array[$item->Bakery_ID] = asset('images/checked.png');
                }
                $output .= '
                <div class="bakery-card-con" onclick="window.location=\'' . route('DetailB', $item->Bakery_ID) . '\';" style="cursor: pointer;">
                    <div class="bakery-card" style="background: url(\'/images/bakery_card.png\');">
                        <div class="card-detail">
                            <img class="mainPic" src="/uploads/bakeries/' . $item->Bakery_image . '" alt="">
                            <h3>' . $item->Bakery_name . '</h3>
                            <p>รหัสสินค้า : ' . $item->Bakery_ID . '<br />
                                จำนวนสินค้า : ' . ($item->stock->first()->Bakery_quantity ?? 0) . '<br /></p>
                            <h4>' . $item->Bakery_price . ' ฿</h4>
                            <h3><img src="'. $IPS_array[$item->Bakery_ID] .'" alt="Status Image" style="width: 40px; height: 40px;"></h3>
                        </div>
                    </div>
                </div>';
            }
        }
    
        return response()->json($output); // ส่งผลลัพธ์กลับในรูปแบบ JSON
    }

    public function detail($bakery_id){
        // $bakery = Bakery::find($bakery_id);
        // $stock = StockBakery::find($bakery_id);
        $IPS_array = [];
        $bakery = Bakery::with(['stock' => function ($query) {
            $query->withTrashed() 
                  ->orderByRaw('deleted_at IS NOT NULL') 
                  ->orderBy('created_at', 'desc'); 
        }])->findOrFail($bakery_id);
        $IP = $bakery->IP_status;
        if($IP == 0) {
            $IPS_array[$bakery->Bakery_ID] = asset('images/cancel.png');
        } if ($IP == 1) {
            $IPS_array[$bakery->Bakery_ID] = asset('images/checked.png');
        }    
        $getBestsell = OrderItem::select('Bakery_ID', DB::raw('SUM(Sum_quantity) as total_quantity'))
            ->groupBy('Bakery_ID')
            ->havingRaw('SUM(Sum_quantity) > 0')
            ->orderByDesc('total_quantity')
            ->get();
        
        $rank = $getBestsell->search(function ($item) use ($bakery_id) {
                return $item->Bakery_ID == $bakery_id;
        });
        if ($rank !== false) {
            $rank = $rank + 1;  
        } else {
            $rank = "ไม่มีอันดับ";  
        }
        $getBakery = $getBestsell->firstWhere('Bakery_ID', $bakery_id);
        $totalQuantity = $getBakery ? $getBakery->total_quantity : 0;
        return view('empBakery-detail', compact('bakery', 'IPS_array', 'rank', 'totalQuantity'));
    }
}
