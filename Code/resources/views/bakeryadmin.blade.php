@extends('layoutOwner')
@section('title')
    เบเกอรี่
@endsection
@section('contents')
    <div class="text-heading">เบเกอรี่</div>
    @foreach ($bakery as $item)
        <div class="cardbx">
            <div class="pcard">
                <div class="pimgbx">
                    <img src="{{ asset('uploads/bakeries/'.$item->Bakery_image) }}" alt="" width="300px">
                </div>
                <div class="contentbx">
                    <h2>{{$item->Bakery_name}}</h2>
                    <div class="pid">
                        <h5>รหัสสินค้า :</h5>
                        <label style="width: 80px;">{{$item->Bakery_ID}}</label>
                    </div>
                    <div class="pnum">
                        <h5>จำนวนสินค้า :</h5>
                        <label>{{$item->Bakery_quantity}}</label>
                    </div>
                    <h4>{{$item->Bakery_price}} ฿</h4>
                </div>
            </div>
        </div>
    @endforeach
    
@endsection