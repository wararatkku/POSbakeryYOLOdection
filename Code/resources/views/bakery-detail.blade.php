@extends('layoutOwner')
@section('title')
    Bakery-Stock
@endsection
@section('contents')
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    @if (session('status') == 'success')
                        <img src="/images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เพิ่มสต็อกสำเร็จ</h3>
                    @elseif(session('status') == 'deleteS')
                        <img src="/images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">ยกเลิกสต็อกสำเร็จ</h3>
                    @elseif(session('status') == 'update')
                        <img src="/images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">อัปเดตข้อมูลเบเกอรี่สำเร็จ</h3>
                    @elseif(session('status') == 'updateS')
                        <img src="/images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">อัปเดตสต็อกสำเร็จ</h3>
                    @else
                        <img src="/images/cancel.png" alt="Error" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เกิดข้อผิดพลาด</h3>
                    @endif

                </div>
                <div class="modal-footer @if (session('status') == 'success' or
                        session('status') == 'deleteS' or
                        session('status') == 'updateS' or
                        session('status') == 'update') bg-success @else bg-danger @endif">
                    <div class="w-100 text-center">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="color: #fff; width: 100%">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('deleteP') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">ลบรายการเบเกอรี่</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_bakery_id" id="bakery_id">
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
    <div class="modal fade" id="deleteStockModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('deleteS') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">ลบรายการสต็อก</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_stockbakery_id" id="stockbakery_id">
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
    <div class="modal fade" id="AddStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มสต็อก {{ $bakery->Bakery_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('addS', $bakery->Bakery_ID) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">จำนวน :</label>
                            <input type="number" class="form-control" name="quantity-bakery" min="1"
                                max="100" required>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">วันหมดอายุ :</label>
                            <input type="date" class="form-control" name="exp-date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required></input>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">เพิ่ม</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แก้ไขสต็อก {{ $bakery->Bakery_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit-stock-form" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">จำนวน :</label>
                            <input type="number" class="form-control" id="edit-quantity" name="quantity-bakery"
                                min="1" max="100" required>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">วันหมดอายุ :</label>
                            <input type="date" class="form-control" id="edit-exp-date" name="exp-date"
                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">แก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="navlink">
        <a href="/Product">รายการสินค้า</a>
        <label>-</label>
        <p>{{ $bakery->Bakery_name }}</p>
    </div>
    <div class="BD-head" style="margin-top: 30px">
        <div class="manageB" style="display: flex; justify-content:space-between; margin-bottom:20px">
            <h4>รายละเอียดสินค้า {{ $bakery->Bakery_name }}</h4>
            <div class="edit-del-con">
                <form action="{{ route('editP', $bakery->Bakery_ID) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning" id="editP">
                        <i class='bx bx-edit icon'></i>
                    </button>
                </form>
                <button type="button" value="{{ $bakery->Bakery_ID }}" class="btn btn-danger deletePBtn">
                    <i class='bx bx-x-circle icon'></i>
                </button>
            </div>
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
            <div class="B-btn">
                <button class="btn btn-warning" data-toggle="modal" data-target="#AddStock">เพิ่มสต็อก</button>
            </div>
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
                                    @elseif (!$stock->trashed())
                                        <button type="button" class="btn btn-warning editSk"
                                            data-id="{{ $stock->StockBakery_ID }}"
                                            data-quantity="{{ $stock->Bakery_quantity }}"
                                            data-exp-date="{{ \Carbon\Carbon::parse($stock->Bakery_exp)->format('Y-m-d') }}"
                                            data-toggle="modal" data-target="#EditStock"><i
                                                class='bx bx-edit icon'></i></button>
                                        <button type="button" class="btn btn-danger deleteSk"
                                            value="{{ $stock->StockBakery_ID }}"><i
                                                class='bx bx-x-circle icon'></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            @if (session('status'))
                $('#statusModal').modal('show');
            @endif

            $('.deletePBtn').click(function(e) {
                e.preventDefault();

                var Bakery_id = $(this).val();
                $('#bakery_id').val(Bakery_id);
                $('#deleteModal').modal('show');
            });
            $('.deleteSk').click(function(e) {
                e.preventDefault();

                var StockBakery_id = $(this).val();
                $('#stockbakery_id').val(StockBakery_id);
                $('#deleteStockModal').modal('show');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.editSk');
            const quantityInput = document.getElementById('edit-quantity');
            const expDateInput = document.getElementById('edit-exp-date');
            const editForm = document.getElementById('edit-stock-form');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const stockId = this.getAttribute('data-id');
                    const stockQuantity = this.getAttribute('data-quantity');
                    const stockExpDate = this.getAttribute('data-exp-date');

                    quantityInput.value = stockQuantity;
                    expDateInput.value = stockExpDate;

                    const stockExpDateFormatted = stockExpDate.split('-');
                    const formattedDate = stockExpDateFormatted[0] + '-' + stockExpDateFormatted[
                        1] + '-' + stockExpDateFormatted[2];
                    expDateInput.setAttribute('min',
                    formattedDate);

                    editForm.action = `/updateStock/${stockId}`;
                });
            });
        });
    </script>
@endsection
