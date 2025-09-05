<?php

namespace App\Http\Controllers;

use App\Models\Bakery;
use App\Models\OrderItem;
use App\Models\StockBakery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ProductController extends Controller
{
    //
    public function index(){
        // $bakery = Bakery::with(['stock' => function ($query) {
        //     $query->select('Bakery_ID', DB::raw('SUM(COALESCE(Bakery_quantity, 0)) as total_quantity'))
        //           ->groupBy('Bakery_ID');
        // }])->get();     
        $bakery = Bakery::with(['stock'])
            ->selectRaw('*, (SELECT SUM(COALESCE(Bakery_quantity, 0)) FROM stock_bakeries WHERE stock_bakeries.Bakery_ID = bakeries.Bakery_ID AND stock_bakeries.deleted_at IS NULL) AS totalS_quantity')
            ->get();
        $LIS = $bakery->map(function($item) {
            // เช็กว่าใน stock มีข้อมูลหรือไม่
            $quantity = $item->stock->isEmpty() ? 0 : $item->totalS_quantity;
            // ถ้ามีข้อมูลใน stock และจำนวนต่ำกว่า 16 ให้เก็บไว้
            return $quantity < 16 ? $item : null;
        })->filter();
        $countLIS=$LIS->count();
        $IPS_array = [];
        foreach ($bakery as $BItem) {
            $IP = $BItem->IP_status;
            if($IP == 0) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/cancel.png');
            } if ($IP == 1) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/checked.png');
            }
        }
        $getBestsell = OrderItem::select('Bakery_ID', DB::raw('SUM(Sum_quantity) as total_quantity'))
            ->groupBy('Bakery_ID')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->value('Bakery_ID');
        $bakeryBestsell = $bakery->where('Bakery_ID', $getBestsell);
        $TotalBakery = Bakery::count();
        $totalStock = StockBakery::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('Bakery_quantity');
        return view('product', compact('bakery', 'countLIS', 'IPS_array', 'bakeryBestsell', 'TotalBakery', 'totalStock'))->with('lowInStock', false);
    }

    public function lowInStock() {
        #$bakery=Bakery::where("Bakery_quantity", "<", 16)->get();
        // $lis = Bakery::with(['stock' => function($query) {
        //     $query->select('Bakery_ID','Bakery_exp', DB::raw('COALESCE(Bakery_quantity, 0) as quantity'));
        // }])->get();
        $lis = Bakery::with(['stock'])
            ->selectRaw('*, (SELECT SUM(COALESCE(Bakery_quantity, 0)) FROM stock_bakeries WHERE stock_bakeries.Bakery_ID = bakeries.Bakery_ID AND stock_bakeries.deleted_at IS NULL) AS totalS_quantity')
            ->get();
        $bakery = $lis->map(function($item) {
            // เช็กว่าใน stock มีข้อมูลหรือไม่
            $quantity = $item->stock->isEmpty() ? 0 : $item->totalS_quantity;
            // ถ้ามีข้อมูลใน stock และจำนวนต่ำกว่า 16 ให้เก็บไว้
            return $quantity < 16 ? $item : null;
        })->filter();
        $bakeryAll = Bakery::all();
        $countLIS=$bakery->count();
        $IPS_array = [];
        foreach ($bakeryAll as $BItem) {
            $IP = $BItem->IP_status;
            if($IP == 0) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/cancel.png');
            } if ($IP == 1) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/checked.png');
            }    
        }
        $getBestsell = OrderItem::select('Bakery_ID', DB::raw('SUM(Sum_quantity) as total_quantity'))
            ->groupBy('Bakery_ID')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->value('Bakery_ID');
        $bakeryBestsell = $bakeryAll->where('Bakery_ID', $getBestsell);
        $TotalBakery = Bakery::count();
        $totalStock = StockBakery::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('Bakery_quantity');
        return view('product', compact('bakery', 'countLIS', 'IPS_array', 'bakeryBestsell', 'TotalBakery', 'totalStock'))->with('lowInStock', true);
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
        return view('bakery-detail', compact('bakery', 'IPS_array', 'rank', 'totalQuantity'));
    }

    public function addStock(Request $request, $bakery_id){
        // $validatedData = $request->validate([
        //     'exp-date' => 'required|date_format:Y-m-d\TH:i'
        // ]);
        $stock = new StockBakery;
        $stock->Bakery_quantity = $request->input('quantity-bakery');
        $stock->Bakery_exp = $request->input('exp-date');
        $stock->Bakery_ID = $bakery_id;
        $stock->save();
        return redirect()->back()->with('status','success');
    }


    public function deleteStock(Request $request) {
        $stockbakery = StockBakery::find($request->delete_stockbakery_id);
        $stockbakery->delete();
        return redirect()->back()->with('status','deleteS');
    }
    public function updateStock(Request $request, $id){

        $stock = StockBakery::findOrFail($id);
        $stock->Bakery_quantity = $request->input('quantity-bakery');
        $stock->Bakery_exp = $request->input('exp-date');
        $stock->save();
        return redirect()->back()->with('status','updateS');
    }

    public function create(){
        $bakery = Bakery::all();
        return view('AddProduct', compact('bakery'));
    }

    public function searchProducts(Request $request){
        $query = $request->input('query');
        $bakeries = Bakery::where('Bakery_name', 'like', '%' . $query . '%')->get();

        return response()->json(['bakeries' => $bakeries]);
    }

    public function searchBakery(Request $request) {
        $query = $request->input('query');
        $lowInStock = $request->has('lowInStock') ? filter_var($request->input('lowInStock'), FILTER_VALIDATE_BOOLEAN) : false;
        $now = Carbon::now()->startOfDay(); // ให้เริ่มต้นที่เวลา 00:00
    
        if ($lowInStock) {
            $bakery = Bakery::with('stock')
                ->selectRaw('*, COALESCE((SELECT SUM(Bakery_quantity) FROM stock_bakeries WHERE stock_bakeries.Bakery_ID = bakeries.Bakery_ID AND stock_bakeries.deleted_at IS NULL), 0) AS totalS_quantity')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('Bakery_name', 'LIKE', "%{$query}%")
                                 ->orWhere('Bakery_ID', 'LIKE', "%{$query}%");
                })
                ->havingRaw('totalS_quantity < 16')
                ->orderBy('Bakery_ID')
                ->get();
        } else {
            $bakery = Bakery::with('stock')
                ->selectRaw('*, COALESCE((SELECT SUM(Bakery_quantity) FROM stock_bakeries WHERE stock_bakeries.Bakery_ID = bakeries.Bakery_ID AND stock_bakeries.deleted_at IS NULL), 0) AS totalS_quantity')
                ->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('Bakery_name', 'LIKE', "%{$query}%")
                                 ->orWhere('Bakery_ID', 'LIKE', "%{$query}%");
                })
                ->orderBy('Bakery_ID')
                ->get();
        }
    
        foreach ($bakery as $BItem) {
            $Prostat = 'ปกติ';
            foreach ($BItem->stock as $stockItem) {
                if (!empty($stockItem->Bakery_exp)) {
                    $expDate = Carbon::parse($stockItem->Bakery_exp)->startOfDay();
                    if ($expDate->lessThan($now)) {
                        $Prostat = 'หมดอายุ';
                        break;
                    } else {
                        $daysRemaining = $now->diffInDays($expDate, false);
                        if ($daysRemaining < 3) {
                            $Prostat = "ใกล้หมดอายุ ($daysRemaining วัน)";
                        }
                    }
                }
            }
            $BItem->Prostat = $Prostat;
        }
    
        $IPS_array = [];
        foreach ($bakery as $BItem) {
            $IPS_array[$BItem->Bakery_ID] = asset($BItem->IP_status == 0 ? 'images/cancel.png' : 'images/checked.png');
        }
    
        return response()->json([
            'bakery' => $bakery,
            'IPS_array' => $IPS_array
        ]);
    }
    
    public function add(Request $request){
        $bakeryName = $request->input('pname');
        $checkName = Bakery::where('Bakery_name', $bakeryName)->first();
        if($checkName) {
            return redirect()->back()->with('status','error');
        }
        $bakery = new Bakery;
        $bakery->Bakery_name = $bakeryName;
        $bakery->Bakery_name_en = $bakeryName; // Set the Bakery_name_en value

        
        if($request->hasfile('pimg')){
            $file = $request->file('pimg');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/bakeries/', $filename);
            $bakery->Bakery_image = $filename;
        }
        $bakery->Bakery_price = $request->input('pprice');
        $bakery->save();
        return redirect('/Product')->with('status','success');
    }

    public function edit($bakery_id){
        $bakery = Bakery::find($bakery_id);
        return view('EditProduct', compact('bakery'));
    }

    public function update(Request $request, $bakery_id){
        $bakery = Bakery::find($bakery_id);
        $bakery->Bakery_ID = $bakery_id;
        $bakery->Bakery_name = $request->input('pname');
        $bakery->Bakery_name_en = $request->input('pnameen');
        if($request->hasfile('pimg')){
            $destination = 'uploads/bakeries/'.$bakery->Bakery_image;
            if(File::exists($destination)){
                File::delete($destination);
            }
            $file = $request->file('pimg');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/bakeries/', $filename);
            $bakery->Bakery_image = $filename;
        }
        $bakery->Bakery_price = $request->input('pprice');
        $bakery->update();
        // return redirect('/Product')->with('status','อัปเดตข้อมูลเบเกอรี่สำเร็จ');
        return redirect()->route('detailB', ['Bakery_ID' => $bakery_id])->with('status','update');
    }

    public function delete(Request $request) {
        $bakery = Bakery::find($request->delete_bakery_id);
        $bakery->delete();
        return redirect('/Product')->with('status','delete');
    }
}