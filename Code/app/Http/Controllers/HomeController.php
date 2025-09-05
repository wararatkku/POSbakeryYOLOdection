<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller

{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
{
    // ดึงข้อมูลชื่อผู้ใช้
    $userName = auth()->user()->name;

    // ส่งข้อมูลไปยัง view
    return view('home', ['userName' => $userName]);
}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ownerHome()
    {
        return view('ownerHome');
    }

}