@extends('layoutAdmin')
@section('title')
    MANAGE-USER
@endsection
@section('contents')
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('deleteUs') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">ลบข้อมูลพนักงาน</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_user_id" id="user_id">
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
                    @if (session('status') == 'success')
                        <img src="images\checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เพิ่มพนักงานสำเร็จ</h3>
                    @elseif(session('status') == 'delete')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">ลบพนักงานสำเร็จ</h3>
                    @elseif(session('status') == 'update')
                        <img src="images/checked.png" alt="Success" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">อัปเดตพนักงานสำเร็จ</h3>
                    @else
                        <img src="images\cancel.png" alt="Error" style="width:100px; margin-top: 30px">
                        <h3 style="margin-top: 20px">เพิ่มพนักงานไม่สำเร็จ</h3>
                    @endif
                </div>
                <div class="modal-footer @if (session('status') == 'success' or session('status') == 'delete' or session('status') == 'update') bg-success @else bg-danger @endif">
                    <div class="w-100 text-center">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="color: #fff; width: 100%">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="list-product">
        <div class="list-headpb" style="display: flex; justify-content: flex-end;">
            <a href="{{ url('createuser') }}" class="btn btn-primary">เพิ่มพนักงาน +</a>
        </div>
        <div class="mainT">
            <table class="table-listP">
                <thead>
                    <tr>
                        <th scope="col">รหัสรายการ</th>
                        <th scope="col">ชื่อ-นามสกุล</th>
                        <th scope="col">อีเมล</th>
                        <th scope="col">ตำแหน่ง</th>
                        <th scope="col">วันเริ่มทำงาน</th>
                        <th scope="col"></th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $item)
                        <tr>
                            <td>{{ 'ID-' . str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                @if ($item->is_admin == 0)
                                    พนักงาน
                                @elseif ($item->is_admin == 1)
                                    เจ้าของร้าน
                                @elseif ($item->is_admin == 2)
                                    แอดมิน
                                @else
                                    ไม่ทราบสถานะ
                                @endif
                            </td>
                            
                            <td>{{ date('d/m/Y', strtotime($item->work)) }}</td>
                            <td>
                                <div class="edit-del-con">
                                    <form action="{{ route('editUs', $item->id) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" id="editUs">
                                            <i class='bx bx-edit icon'></i>
                                        </button>
                                    </form>
                                    <button type="button" value="{{ $item->id }}"
                                        class="btn btn-danger delePbBtn">
                                        <i class='bx bx-x-circle icon'></i>
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
        {{ $user->links('pagination::bootstrap-4') }}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.delePbBtn').click(function(e) {
                e.preventDefault();

                var user_id = $(this).val();
                $('#user_id').val(user_id);
                $('#deleteModal').modal('show');
            });
        });
        @if (session('status'))
                $('#statusModal').modal('show');
            @endif
    </script>
@endsection
