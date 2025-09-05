<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('is_admin', '!=', 2)->paginate(8);
        return view('user', compact('user'));
    }
    public function create()
    {
        return view('createuser');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'usname' => 'required|string|max:255',
            'usemail' => 'required|string|email|max:255|unique:users,email',
            'uspass' => 'required|string|min:4',
            'ustype' => 'required|in:0,1', 
            'ustime' => 'required|date'
        ], [
            'uspass.min' => 'กรุณากรอกรหัสผ่านอย่างน้อย 4 ตัวอักษร.',
            'usemail.unique' => 'อีเมลนี้ถูกใช้งานแล้ว กรุณากรอกอีเมลใหม่.',
            'ustype.required' => 'กรุณาเลือกตำแหน่ง.',
        ]);

        $user = new User();
        $user->name = $request->usname;
        $user->email = $request->usemail;
        $user->password = Hash::make($request->uspass); 
        $user->is_admin = $request->ustype; 
        $user->work = $request->ustime; 
        $user->save(); 

        return redirect('/User')->with('status', 'success');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('edituser', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'usname' => 'required|string|max:255',
            'usemail' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
            'uspass' => 'nullable|string|min:4', 
            'ustype' => 'required|in:0,1', 
            'ustime' => 'required|date'
        ], [
            'uspass.min' => 'กรุณากรอกรหัสผ่านอย่างน้อย 4 ตัวอักษร.',
            'usemail.unique' => 'อีเมลนี้ถูกใช้งานแล้ว กรุณากรอกอีเมลใหม่.',
            'ustype.required' => 'กรุณาเลือกตำแหน่ง.',
        ]);

        $user->name = $request->usname;
        $user->email = $request->usemail;
        $user->password = Hash::make($request->uspass); 
        $user->is_admin = $request->ustype; 
        $user->work = $request->ustime; 
        $user->save(); 

        return redirect('/User')->with('status', 'update');
    }
    public function delete(Request $request) {
        $user = User::find($request->delete_user_id);
        $user->delete();
        return redirect('/User')->with('status','delete');
        
    }
}