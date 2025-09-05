@extends('layoutOwner')
@section('title')
    SellManagement
@endsection
@section('contents')
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    @if(session('status') == 'success')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เพิ่มรายการซื้อสำเร็จ</h3>
                    @elseif(session('status') == 'delete')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">ลบรายการซื้อสำเร็จ</h3>
                    @elseif(session('status') == 'update')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">อัปเดตรายการซื้อสำเร็จ</h3>
                    @else
                        <img src="images/cancel.png" alt="Error" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เกิดข้อผิดพลาด</h3>
                    @endif
                </div>
                <div class="modal-footer @if(session('status') == 'success' or session('status') == 'delete' or session('status') == 'update') bg-success @else bg-danger @endif">
                    <div class="w-100 text-center">
                        <button type="button" class="btn" data-dismiss="modal" style="color: #fff; width: 100%">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard">
        <div class="db-card">
            <div class="db-icon">
                <i class="fa-solid fa-receipt icon"></i>
            </div>
            <div class="db-content">
                <h6>จำนวนรายการขายทั้งหมด</h6>
                <h4>{{ $TotalOrders }} รายการ</h4>
            </div>
        </div>
        <div class="db-card">
            <div class="db-icon">
                <i class="fa-solid fa-cubes icon"></i>
            </div>
            <div class="db-content">
                <h6>จำนวนสินค้าที่ขายทั้งหมด</h6>
                <h4>{{ $TotalPieces }} ชิ้น</h4>
            </div>
        </div>
        <div class="db-card">
            <div class="db-icon">
                <i class="fa-solid fa-baht-sign icon"></i>
            </div>
            <div class="db-content">
                <h6>มูลค่าทั้งหมด</h6>
                <h4>{{ number_format($TotalPrices) }} บาท</h4>
            </div>
        </div>
    </div>

    <div class="list-product">
        <div class="list-headSell">
            <form action="{{ route('sellM.filter') }}" method="GET">
                <div class="filter-date" style="text-align: left">
                    <div style="position: relative;">
                        <input type="text" id="date_range" name="date_range" value="{{ request('date_range') }}"
                            placeholder="เลือกช่วงวันที่" class="form-control"
                            style="width: 240px; max-width: 100%; padding: 10px; margin-right: 10px; background-color: #ddaf81; color: white; padding-right: 30px;">
                        <i class="fas fa-calendar-alt " id="calendar-icon"
                            style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: white; "></i>
                    </div>
        
                    <button type="submit" class="btn-filter" style="background-color: #ddaf81">กรอง</button>
                </div>
            </form>
            <a href="/CreateOrder" class="btn btn-primary">สร้างรายการ +</a>
        </div>
        <div class="mainT">
            <table class="table-listP">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">รายการ</th>
                        
                        <th scope="col">การชำระเงิน</th>
                        <th scope="col">ราคารวม(บาท)</th>
                        <th scope="col">วันที่ขาย</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bakeryOrders as $key => $order)
                        <tr>
                            <td>{{ $bakeryOrders->firstItem() + $key }}</td>
                            <td>{{ 'OR-' . str_pad($order->BakeryOrder_ID, 4, '0', STR_PAD_LEFT) }}</td>
                            
                            <td>{{ $order->payment->Payment_Type }}</td>
                            <td>{{ $order->Total_price }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="edit-del-con">
                                    <form action="{{ route('OrderD', $order->BakeryOrder_ID) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn detail" id="orderDetail_pre">
                                            ดูรายละเอียด
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination-his">
        {{ $bakeryOrders->links('pagination::bootstrap-4') }}
    </div>
    <script>
        setTimeout(function() {
            var statusMessage = document.getElementById('status-message');
            if (statusMessage) {
                statusMessage.style.display = 'none';
            }
        }, 3000); 
        @if(session('status'))
                $('#statusModal').modal('show');  
            @endif

        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#date_range", {
                mode: "range", 
                dateFormat: "Y-m-d", 
                onChange: function(selectedDates, dateStr, instance) {
                    document.querySelector('input[name="date_range"]').value = dateStr;
                }
            });
            document.getElementById("calendar-icon").addEventListener("click", function() {
                document.getElementById("date_range").focus();
            });
        });
    </script>
@endsection

