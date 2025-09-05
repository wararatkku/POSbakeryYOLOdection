@extends('layoutCus')
@section('title')
    เบเกอรี่
@endsection
@section('contents')
    <div class="text-heading">เบเกอรี่</div>
    <div class="searchB">
        <input type="text" name="findB" class="form-control" id="findB" placeholder="ค้นหาเบเกอรี่...">
    </div>
    <div id="bakeryShow">
        @foreach ($bakery as $item)
            <div class="bakery-card-con" onclick="window.location='{{ route('DetailB', $item->Bakery_ID) }}';" style="cursor: pointer;">
                <div class="bakery-card" style="background: url('{{ asset('images/bakery_card.png') }}');">
                    <div class="card-detail">
                        <img class="mainPic" src="{{ asset('uploads/bakeries/' . $item->Bakery_image) }}" alt="">
                        <h3> {{ $item->Bakery_name }}</h3>
                        <p>รหัสสินค้า : {{ 'B-' . str_pad($item->Bakery_ID, 4, '0', STR_PAD_LEFT) }}<br />
                            จำนวนสินค้า : {{ $item->totalS_quantity ?? 0 }}<br /></p>
                        <h4>{{ $item->Bakery_price }} ฿</h4>
                        <h3><img src="{{ $IPS_array[$item->Bakery_ID] }}" alt="Status Image" style="width: 40px; height: 40px;"></h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#findB').on('keyup', function() {
                var query = $(this).val();

                // ส่งคำขอ AJAX เพื่อค้นหาเบเกอรี่
                $.ajax({
                    url: "{{ route('searchBakery') }}",
                    type: "GET",
                    data: {'findB': query},
                    success: function(data) {
                        // อัปเดต HTML ใน #bakeryResults ด้วยข้อมูลใหม่ที่ได้จาก controller
                        $('#bakeryShow').html(data);
                    }
                });
            });
        });
    </script>
@endsection