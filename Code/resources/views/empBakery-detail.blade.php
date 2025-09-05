@extends('layoutCus')
@section('title')
    Bakery-Stock
@endsection
@section('contents')
    <div class="navlink" style="margin-top:50px;">
        <a href="/Bakery">เบเกอรี่</a>
        <label>-</label>
        <p>{{ $bakery->Bakery_name }}</p>
    </div>
    <div class="BD-head" style="margin-top: 30px">
        <div class="manageB" style="display: flex; justify-content:space-between; margin-bottom:20px">
            <h4>รายละเอียดสินค้า {{ $bakery->Bakery_name }}</h4>
        </div>
        <div class="bakeryD">
            <div class="bakeryContent">
                <img src="{{ asset('uploads/bakeries/' . $bakery->Bakery_image) }}" alt="" width="300px"
                    height="300px" class="bDetail">
                <div class="contentDetail">
                    <h5>{{ $bakery->Bakery_name }}</h5>
                    <div class="row">
                        <label>รหัสสินค้า</label>
                        <label>อันดับสินค้าขายดีในร้าน</label>
                        <p>{{ 'B-' . str_pad($bakery->Bakery_ID, 4, '0', STR_PAD_LEFT) }}</p>
                        <p>
                            @if($rank == "ไม่มีอันดับ")
                                ไม่มีอันดับ
                            @else
                                อันดับ {{ $rank }}
                            @endif
                        </p>
                    </div>
                    <div class="row">
                        <label>ราคา</label>
                        <label style="margin-left: 12px">จำนวนสินค้าที่ขายแล้ว</label>
                        <p>{{ $bakery->Bakery_price }} บาท</p>
                        <p style="margin-left: 14px">{{ $totalQuantity }} ชิ้น</p>
                    </div>
                    <label style="margin-left: 30px">AI</label>
                    <h2 style="margin-left: 25px"><img src="{{ $IPS_array[$bakery->Bakery_ID] }}" alt="Status Image"
                            style="width: 30px; height: 30px;"></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="BD-head" style="margin-top: 30px">
        <div class="manageB" style="display: flex; justify-content:space-between; margin-bottom:20px">
            <h4>รายละเอียดสต็อกสินค้า</h4>
        </div>
        <div class="bakeryS">
            <div class="Table-S">
                <table class="table-listS">
                    <thead>
                        <tr>
                            <th scope="col">สต็อกที่</th>
                            <th scope="col">จำนวนสินค้า(ชิ้น)</th>
                            <th scope="col">จำนวนสินค้าที่ขาย(ชิ้น)</th>
                            <th scope="col">วันหมดอายุ</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody id="BakeryPBody">
                        @foreach ($bakery->stock as $stock)
                            @php
                                $now = \Carbon\Carbon::now()->startOfDay(); // ใช้ startOfDay() เพื่อลดปัญหาเรื่องเวลา
                                $expDate = \Carbon\Carbon::parse($stock->Bakery_exp)->startOfDay();
                                $daysLeft = $now->diffInDays($expDate, false);
                                $backgroundColor = '#ffffff';
                                if ($stock->trashed()) {
                                    $backgroundColor = '#e0e0e0';
                                }
                            @endphp
                            <tr>
                                <td>{{ $bakery->stock->count() - $loop->iteration + 1 }}</td>
                                <td>{{ $stock->Bakery_quantity }}</td>
                                <td>{{ $stock->Sell_quantity }}</td>
                                <td
                                    style="color: 
                                    @if ($stock->trashed()) gray 
                                    @elseif ($daysLeft < 0) #d9534f
                                    @elseif ($daysLeft < 3) #f0ae4e
                                    @else gray @endif; 
                                    font-weight: 
                                    @if ($stock->trashed()) normal
                                    @elseif ($daysLeft < 0 || $daysLeft <= 2) bold @endif;">
                                    {{ \Carbon\Carbon::parse($stock->Bakery_exp)->format('d/m/Y') }}
                                    @if ($daysLeft < 0)
                                        <br><span style="font-size: 16px; color: #d9534f;">หมดอายุ</span>
                                    @elseif ($daysLeft < 3)
                                        <br><span style="font-size: 16px; color: #f0ae4e;">ใกล้หมดอายุ</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($stock->trashed())
                                        <img src="{{ asset('images/out-stock.png') }}" alt="" width="50px"
                                        height="50px">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
@endsection
