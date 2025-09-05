<?php

namespace App\Http\Controllers;

use App\Models\Bakery;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function index(){
        $bakery=Bakery::with('stock')->paginate(7);
        $IPS_array = [];
        foreach ($bakery as $BItem) {
            $IP = $BItem->IP_status;
            if($IP == 0) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/cancel.png');
            } if ($IP == 1) {
                $IPS_array[$BItem->Bakery_ID] = asset('images/checked.png');
            }
        }
        return view('ai', compact('bakery', 'IPS_array'));
    }
    public function updateStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|integer',
        ]);
        // Find the bakery item by ID
        $item = Bakery::find($id);

        if ($item) {
            $item->IP_status = $request->status; // Assuming you have a 'status' column
            $item->save();

            return redirect()->back()->with('status', 'success');
        }

        return redirect()->back()->with('status', 'error');
    }
    public function search(Request $request){
        $query = $request->input('query');

        $bakery = Bakery::where('Bakery_name', 'LIKE', "%{$query}%")
                        ->orWhere('Bakery_ID', 'LIKE', "%{$query}%")
                        ->with('stock')
                        ->get();

        return response()->json($bakery);
    }
}
