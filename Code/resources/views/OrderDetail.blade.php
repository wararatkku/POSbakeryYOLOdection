@extends('layoutOwner')
@section('title')
    SellManagement
@endsection
@section('contents')
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('deleteOR') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">ลบรายการขาย</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_order_id" id="order_id">
                        <h5>ยืนยันการลบรายการที่เลือกหรือไม่</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-danger">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (session('status'))
        <h6 id="status-message" class="alert alert-success">{{ session('status') }}</h6>
    @endif
    <div class="navlink">
        <a href="/SellManage">รายการออเดอร์</a>
        <label>-</label>
        <p>{{ 'OR-' . str_pad($bakeryOrders->BakeryOrder_ID, 4, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="order-detail">
        <div class="order-headSell">
            <h5><strong>รายละเอียดรายการ</strong></h5>
            <div class="edit-del-OR">
                <form action="{{ route('editOrderDetail', $bakeryOrders->BakeryOrder_ID) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning" id="editOrderDeatil">
                        <i class='bx bx-edit icon'>แก้ไข</i>
                    </button>
                </form>
                <button type="button" value="{{ $bakeryOrders->BakeryOrder_ID }}"
                    class="btn btn-danger deleteORBtn">
                    <i class='bx bx-x-circle icon'>ลบ</i>
                </button>
            </div>
        </div>
        <div class="orderData">
            <h5><strong>ข้อมูล</strong></h5>
            <div class="Orderinfo">
                <div class="info-grid">
                    <div class="info-item">
                        <label>รายการ :</label>
                        <span>{{ 'OR-' . str_pad($bakeryOrders->BakeryOrder_ID, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-item">
                        <label>การชำระเงิน :</label>
                        <span>{{ $bakeryOrders->payment->Payment_Type }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <label>วันที่ขาย :</label>
                    <span>{{ $bakeryOrders->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
        <div class="orderItem">
            <h5><strong>สินค้า</strong></h5>
            <div class="orderT">
                <table class="table-listO">
                    <thead>
                        <tr>
                            <th scope="col">ลำดับ</th>
                            <th scope="col">รหัสสินค้า</th>
                            <th scope="col">ชื่อสินค้า</th>
                            <th scope="col">จำนวน(ชิ้น)</th>
                            <th scope="col">ราคาต่อชิ้น(บาท)</th>
                            <th scope="col">ราคารวม(บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bakeryOrders->orderItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ 'B-' . str_pad($item->bakery->Bakery_ID, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $item->bakery->Bakery_name }}</td>
                                <td>{{ $item->Sum_quantity }}</td>
                                <td>{{ $item->bakery->Bakery_price }}</td>
                                <td>{{ $item->Sum_price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="OrderP">
                <div>
                    <label>ราคารวม</label>
                    <p>{{ $bakeryOrders->Total_price }}</p>
                </div>
                <div>
                    <label><strong>ราคารวมสุทธิ</strong></label>
                    <p><strong>{{ $bakeryOrders->Total_price }}</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.deleteORBtn').click(function(e) {
                e.preventDefault();

                var Order_id = $(this).val();
                $('#order_id').val(Order_id);
                $('#deleteModal').modal('show');
            });
        });
        setTimeout(function() {
            var statusMessage = document.getElementById('status-message');
            if (statusMessage) {
                statusMessage.style.display = 'none';
            }
        }, 3000); 
    </script>
@endsection
