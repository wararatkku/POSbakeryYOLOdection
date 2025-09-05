<?php

namespace App\Http\Controllers;

use App\Models\ProductBuy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductBuyController extends Controller
{
    public function index(){
        $productbuy = ProductBuy::paginate(10);
        $Datepb = [];
    
        foreach ($productbuy as $productbuys) {
            $createDate = $productbuys->created_at;
            $formatDate = date('d-m-Y', strtotime($createDate));
            $yearInBE = date('Y', strtotime($createDate)) + 543;
            $Datepb[] = date('d/m/', strtotime($createDate)).$yearInBE;
        }
        
        return view('productbuy', compact('productbuy', 'Datepb'));
    }
    

    public function create(){
        return view('productbuycreate');
    }
    
    public function selectProduct(){
        return view('productbuyselect');
    }

    public function add(Request $request){
        $productbuy = new ProductBuy;
        $productbuy->Product_Name = $request->input('pbname');
        if($request->hasfile('pbimg')){
            $file = $request->file('pbimg');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/productbuys/', $filename);
            $productbuy->Product_image = $filename;
        }
        $productbuy->Product_price = $request->input('pbprice');
        $productbuy->Product_quantity = $request->input('pbquan');
        $productbuy->Product_unit = $request->input('pbunit');
        $productbuy->save();
        return redirect('/ProductBuy')->with('status','success');
    }

    public function edit($product_id){
        $productbuy = ProductBuy::find($product_id);
        $productbuy_type = ['ชิ้น', 'กิโลกรัม','มิลลิกรัม', 'กรัม', 'ลิตร','มิลลิลิตร','แพ็ค','ฟอง'];
        return view('productbuyedit', compact('productbuy','productbuy_type'));
    }

    public function update(Request $request, $product_id){
        $productbuy = ProductBuy::find($product_id);
        $productbuy->Product_id = $product_id;
        $productbuy->Product_Name = $request->input('pbname');
        if($request->hasfile('pbimg')){
            $destination = 'uploads/productbuys/'.$productbuy->Product_image;
            if(File::exists($destination)){
                File::delete($destination);
            }
            $file = $request->file('pbimg');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('uploads/productbuys/', $filename);
            $productbuy->Product_image = $filename;
        }
        $productbuy->Product_price = $request->input('pbprice');
        $productbuy->Product_quantity = $request->input('pbquan');
        $productbuy->Product_unit = $request->input('pbunit');
        $productbuy->update();
        return redirect('/ProductBuy')->with('status','update');
    }

    public function delete(Request $request) {
        $productbuy = ProductBuy::find($request->delete_product_id);
        $productbuy->delete();
        return redirect('/ProductBuy')->with('status','delete');
    }

    public function searchProductBuy(Request $request) {
        $query = $request->input('findPB');  // Accept the search input
        $productbuy = ProductBuy::where('Product_Name', 'LIKE', '%' . $query . '%')->get(); // Perform the search
    
        $output = '';
        if ($productbuy->isEmpty()) {
            $output = '<tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>';
        } else {
            foreach ($productbuy as $item) {
                $output .= '
                    <tr>
                        <td>' . 'PB-' . str_pad($item->Product_ID, 4, '0', STR_PAD_LEFT) . '</td>
                        <td><img src="' . asset('uploads/productbuys/' . $item->Product_image) . '" alt="" width="50px" height="50px"></td>
                        <td>' . $item->Product_Name . '</td>
                        <td>' . $item->Product_price . '</td>
                        <td>' . $item->Product_quantity . '</td>
                        <td>' . $item->Product_unit . '</td>
                        <td>' . $item->created_at->format('d/m/Y') . '</td>
                        <td>
                            <div class="edit-del-con">
                                <form action="' . route('editPb', $item->Product_ID) . '" method="POST">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bx bx-edit icon"></i>
                                    </button>
                                </form>
                                <button type="button" value="' . $item->Product_ID . '" class="btn btn-danger delePbBtn">
                                    <i class="bx bx-x-circle icon"></i>
                                </button>
                            </div>
                        </td>
                    </tr>';
            }
        }
        return response()->json($output);
    }
    
}