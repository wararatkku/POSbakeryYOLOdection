@extends('layoutAdmin')
@section('title')
    AI-Bakery
@endsection
@section('contents')
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    @if (session('status') == 'success')
                        <img src="images\checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เปลี่ยนสถานะสำเร็จ</h3>
                    @else
                        <img src="images\cancel.png" alt="Error" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เปลี่ยนสถานะไม่สำเร็จ</h3>
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
    <div class="list-product" style="margin-top: 20px;">
        <div class="searchP-ai" style="margin-bottom: 20px;">
            <input type="text" name="findB-ai" class="form-control" id="findB-ai" placeholder="ค้นหาเบเกอรี่...">
        </div>
        <div class="mainT">
            <table class="table-listP">
                <thead>
                    <tr>
                        <th scope="col">รหัสสินค้า</th>
                        <th scope="col"></th>
                        <th scope="col">ชื่อสินค้า</th>
                        <th scope="col">ราคา(บาท)</th>
                        <th scope="col">คงเหลือ(ชิ้น)</th>
                        <th scope="col">AI</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="bakeryTable">
                    @foreach ($bakery as $item)
                        <tr class="bakery-row">
                            <td>{{ 'B-' . str_pad($item->Bakery_ID, 4, '0', STR_PAD_LEFT) }}</td>
                            <td><img src="{{ asset('uploads/bakeries/' . $item->Bakery_image) }}" alt=""
                                    width="50px" height="50px"></td>
                            <td class="bakery-name">{{ $item->Bakery_name }}</td>
                            <td>{{ $item->Bakery_price }}</td>
                            <td>{{ $item->stock->first()->Bakery_quantity ?? 0 }}</td>
                            <td><img src="{{ $IPS_array[$item->Bakery_ID] }}" alt="Status Image"
                                    style="width: 30px; height: 30px;"> </td>
                            <td>
                                <form action="{{ route('update.status', $item->Bakery_ID) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $item->IP_status ? 0 : 1 }}">
                                    <label class="switch">
                                        <input type="checkbox" name="iPStatus" onchange="this.form.submit()"
                                            {{ $item->IP_status ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
    <div class="pagination-his">
        {{ $bakery->links('pagination::bootstrap-4') }}
    </div>
@endsection

@section('scripts')
    <script>
        @if (session('status'))
            $('#statusModal').modal('show');
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $("#findB-ai").on("keyup", function() {
                var value = $(this).val().trim();
    
                if (value.length === 0) {
                    location.reload();
                    return;
                }
    
                $.ajax({
                    url: "{{ route('search.bakeryai') }}",
                    method: "GET",
                    data: { query: value },
                    success: function(response) {
                        $("#bakeryTable").empty();
                        if (response.length === 0) {
                            $("#bakeryTable").append("<tr><td colspan='7' class='text-center'>ไม่พบสินค้า</td></tr>");
                        } else {
                            response.forEach(function(item) {
                                $("#bakeryTable").append(`
                                    <tr class="bakery-row">
                                        <td>B-${String(item.Bakery_ID).padStart(4, '0')}</td>
                                        <td><img src="uploads/bakeries/${item.Bakery_image}" width="50px" height="50px"></td>
                                        <td class="bakery-name">${item.Bakery_name}</td>
                                        <td>${item.Bakery_price}</td>
                                        <td>${item.stock.length > 0 ? item.stock[0].Bakery_quantity : 0}</td>
                                        <td><img src="${item.IP_status ? 'images/checked.png' : 'images/cancel.png'}" style="width: 30px; height: 30px;"></td>
                                        <td>
                                            <form action="/update-status/${item.Bakery_ID}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="${item.IP_status ? 0 : 1}">
                                                <label class="switch">
                                                    <input type="checkbox" name="iPStatus" onchange="this.form.submit()" ${item.IP_status ? 'checked' : ''}>
                                                    <span class="slider"></span>
                                                </label>
                                            </form>
                                        </td>
                                    </tr>
                                `);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
