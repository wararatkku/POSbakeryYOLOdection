@extends('layoutOwner')
@section('title')
    ProductBuy
@endsection
@section('contents')
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('deletePb') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">ลบรายการสินค้า</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_product_id" id="Product_id">
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
    <div class="list-product">
        <div class="list-headpb" style="display: flex; justify-content: flex-end;">
            <div class="searchPB">
                <input type="text" name="findPB" class="form-control" id="findPB" placeholder="ค้นหารายการซื้อ...">
            </div>
            <a href="{{ url('productbuycreate') }}" class="btn btn-primary">เพิ่มรายการสินค้า +</a>
        </div>
        
        <div class="mainT">
            <table class="table-listP" style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 100px;">รหัสรายการ</th>
                        <th style="width: 30px;"></th>
                        <th style="width: 160px;">ชื่อสินค้า</th>
                        <th style="width: 80px;">ราคา(บาท)</th>
                        <th style="width: 60px;">จำนวน</th>
                        <th style="width: 60px;">หน่วย</th>
                        <th style="width: 100px;">วันที่ซื้อ</th>
                        <th style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @foreach ($productbuy as $item)
                        <tr>
                            <td>{{ 'PB-' . str_pad($item->Product_ID, 4, '0', STR_PAD_LEFT) }}</td>
                            <td><img src="{{ asset('uploads/productbuys/' . $item->Product_image) }}" alt=""
                                    width="50px" height="50px"></td>
                            <td>{{ $item->Product_Name }}</td>
                            <td>{{ $item->Product_price }}</td>
                            <td>{{ $item->Product_quantity }}</td>
                            <td>{{ $item->Product_unit }}</td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="edit-del-con">
                                    <form action="{{ route('editPb', $item->Product_ID) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="bx bx-edit icon"></i>
                                        </button>
                                    </form>
                                    <button type="button" value="{{ $item->Product_ID }}"
                                        class="btn btn-danger delePbBtn">
                                        <i class="bx bx-x-circle icon"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination-his">
        {{ $productbuy->links('pagination::bootstrap-4') }}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.delePbBtn').click(function(e) {
                e.preventDefault();

                var Product_id = $(this).val();
                $('#Product_id').val(Product_id);
                $('#deleteModal').modal('show');
            });
        });
        $(document).ready(function() {
            $('#findPB').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('searchProductBuy') }}",
                    type: "GET",
                    data: {
                        'findPB': query
                    },
                    success: function(data) {
                        $('#tbody').html(data);
                    }
                });
            });
        });
        @if(session('status'))
                $('#statusModal').modal('show');  
            @endif
    </script>
@endsection
