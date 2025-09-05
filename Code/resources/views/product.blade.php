@extends('layoutOwner')
@section('title')
    Product
@endsection
@section('contents')
    <!-- Modal -->

    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    @if (session('status') == 'success')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เพิ่มเบเกอรี่สำเร็จ</h3>
                    @elseif(session('status') == 'delete')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">ลบเบเกอรี่สำเร็จ</h3>
                    @else
                        <img src="images/cancel.png" alt="Error" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เกิดข้อผิดพลาด</h3>
                    @endif
                </div>
                <div class="modal-footer @if (session('status') == 'success' or session('status') == 'delete') bg-success @else bg-danger @endif">
                    <div class="w-100 text-center">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="color: #fff; width: 100%">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard">
        <div class="db-card">
            @foreach ($bakeryBestsell as $best)
                <div class="db-img">
                    <img src="{{ asset('uploads/bakeries/' . $best->Bakery_image) }}" alt="" width="70px"
                        height="65px">
                </div>
                <div class="db-content">
                    <h6>สินค้าขายดี</h6>
                    <h4>{{ $best->Bakery_name }}</h4>
                </div>
            @endforeach
        </div>
        <div class="db-card">
            <div class="db-icon">
                <i class="fa-solid fa-bread-slice icon"></i>
            </div>
            <div class="db-content">
                <h6>จำนวนสินค้าทั้งหมด</h6>
                <h4>{{ $TotalBakery }} ชิ้น</h4>
            </div>
        </div>
        <div class="db-card">
            <div class="db-icon">
                <i class="fa-solid fa-cubes icon"></i>
            </div>
            <div class="db-content">
                <h6>จำนวนการผลิตสินค้าในเดือนนี้</h6>
                <h4>{{ $totalStock }} ชิ้น</h4>
            </div>
        </div>
    </div>
    <div class="list-product">
        <div class="list-head">
            <div class="product-btn">
                <a href="{{ url('Product') }}" class="btn btn-primary">
                    สินค้าทั้งหมด
                </a>
                <a href="{{ url('lowInStock') }}" class="btn btn-danger active">
                    สินค้าเหลือน้อย &#40;{{ $countLIS }}&#41;
                </a>
            </div>
            <div class="searchP">
                <input type="text" name="findB" class="form-control" id="findB" placeholder="ค้นหาเบเกอรี่...">
            </div>
            <a href="{{ url('AddProduct') }}" class="btn btn-primary">เพิ่มสินค้าใหม่ +</a>
        </div>
        <div class="mainT">
            <table class="table-listP">
                <thead>
                    <tr>
                        <th scope="col" style="width: 1px;">รหัสสินค้า</th>
                        <th scope="col" style="width: 1px;"></th>
                        <th scope="col" style="width: 30px;">ชื่อสินค้า</th>
                        <th scope="col" style="width: 5px;">ราคา(บาท)</th>
                        <th scope="col" style="width: 25px;">คงเหลือ(ชิ้น)</th>
                        <th scope="col" style="width: 25px;">ระบบตรวจจับ</th>
                        <th scope="col" style="width: 25px;">สต็อก</th>
                        <th scope="col" style="width: 32px;"></th>
                    </tr>
                </thead>
                <tbody id="BakeryPBody">
                    @foreach ($bakery as $item)
                        <tr>
                            <td>{{ 'B-' . str_pad($item->Bakery_ID, 4, '0', STR_PAD_LEFT) }}</td>
                            <td><img src="{{ asset('uploads/bakeries/' . $item->Bakery_image) }}" alt=""
                                    width="50px" height="50px"></td>
                            <td>{{ $item->Bakery_name }}</td>
                            <td>{{ $item->Bakery_price }}</td>
                            <td>
                                @if ($item->stock->isEmpty())
                                    0
                                @else
                                    {{ $item->totalS_quantity ?? 0 }}
                                @endif
                            </td>
                            <td><img src="{{ $IPS_array[$item->Bakery_ID] }}" alt="Status Image"
                                    style="width: 30px; height: 30px;"> </td>
                            <td>
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $Prostat = 'ปกติ';
                                    $daycheck = 0;

                                    foreach ($item->stock as $stockItem) {
                                        if (!empty($stockItem->Bakery_exp)) {
                                            $now = \Carbon\Carbon::now()->startOfDay(); // ใช้ startOfDay() เพื่อลดปัญหาเรื่องเวลา
                                            $expDate = \Carbon\Carbon::parse($stockItem->Bakery_exp)->startOfDay();

                                            if ($expDate->lessThan($now)) {
                                                $Prostat = 'หมดอายุ';
                                                break;
                                            } elseif ($expDate->equalTo($now)) {
                                                $Prostat = 'จะหมดอายุในวันนี้';
                                            } elseif ($now->diffInDays($expDate, false) < 3) {
                                                if ($Prostat === 'จะหมดอายุในวันนี้') {
                                                    break;
                                                }
                                                $daysRemaining = $now->diffInDays($expDate, false);
                                                if ($daycheck === 0) {
                                                    $daycheck = $daysRemaining;
                                                }
                                                if ($daycheck < $daysRemaining) {
                                                    $Prostat = "ใกล้หมดอายุ ($daycheck วัน)";
                                                } else {
                                                    $Prostat = "ใกล้หมดอายุ ($daysRemaining วัน)";
                                                }  
                                            }
                                        }
                                    }
                                @endphp


                                @if ($Prostat == 'ปกติ')
                                    <span style="color: green;">{{ $Prostat }}</span>
                                @elseif ($Prostat == 'หมดอายุ')
                                    <span style="color: red;">{{ $Prostat }}</span>
                                @elseif (Str::startsWith($Prostat, 'ใกล้หมดอายุ') || 'จะหมดอายุในวันนี้')
                                    <span style="color: orange;">{{ $Prostat }}</span>
                                @endif

                            </td>

                            <td>
                                <form action="{{ route('detailB', $item->Bakery_ID) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" id="editP">
                                        ดูรายละเอียด
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            var lowInStockPage = {{ $lowInStock ? 'true' : 'false' }};

            @if (session('status'))
                $('#statusModal').modal('show');
            @endif

            $('#findB').on('keyup', function() {
                var query = $(this).val();

                console.log('Query:', query);
                console.log('LowInStock:', lowInStockPage);

                $.ajax({
                    url: '{{ route('search-bakery') }}',
                    method: 'GET',
                    data: {
                        query: query,
                        lowInStock: lowInStockPage
                    },
                    success: function(response) {
                        console.log(response);

                        $('#BakeryPBody').html(''); 

                        if (response.bakery.length > 0) {
                            response.bakery.forEach(function(item) {
                                var quantity = (item.stock && item.stock.length > 0) ?
                                    item.totalS_quantity : 0;

                                var prostat = item.Prostat;
                                var prostatColor;
                                if (prostat === 'ปกติ') {
                                    prostatColor = 'green';
                                } else if (prostat === 'หมดอายุ') {
                                    prostatColor = 'red';
                                } else if (prostat.startsWith('ใกล้หมดอายุ')) {
                                    prostatColor = 'orange';
                                }

                                var row = `
                        <tr>
                            <td>${'B-' + item.Bakery_ID.toString().padStart(4, '0')}</td>
                            <td><img src="{{ asset('uploads/bakeries/') }}/${item.Bakery_image}" alt="" width="50px" height="50px"></td>
                            <td>${item.Bakery_name}</td>
                            <td>${item.Bakery_price}</td>
                            <td>${quantity}</td>
                            <td><img src="${response.IPS_array[item.Bakery_ID]}" alt="Status Image" style="width: 30px; height: 30px;"></td>
                            <td><span style="color: ${prostatColor};">${prostat}</span></td>
                            <td>
                                <form action="{{ route('detailB', '') }}/${item.Bakery_ID}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" id="editP">
                                        ดูรายละเอียด
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;
                                $('#BakeryPBody').append(row);
                            });
                        } else {
                            $('#BakeryPBody').append(
                                '<tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('เกิดข้อผิดพลาดในการค้นหา กรุณาลองใหม่อีกครั้ง');
                    }
                });
            });
        });
    </script>
@endsection
