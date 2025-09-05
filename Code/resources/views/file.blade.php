@extends('layoutOwner')
@section('title')
    File
@endsection
@section('contents')
    <!-- Modal -->
    <div class="modal fade" id="deleteFileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('deleteF') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">ลบรายการไฟล์เอกสาร</h1>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_file_id" id="file_id">
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
        <h6 class="alert alert-success">{{ session('status') }}</h6>
    @endif
    <div class="list-product">
        <div class="list-head">
            <div class="file-upload-btn">
                {{-- <a href="{{ url('UploadAIB') }}" class="btn btn-primary">อัปโหลดรูปภาพ AI Bakery</a> --}}
                <a href="{{ url('UploadFile') }}" class="btn btn-primary">อัปโหลดไฟล์เอกสาร</a>
            </div>
        </div>
        <div class="mainT">
            <table class="table-listP">
                <thead>
                    <tr>
                        <th scope="col">ไฟล์</th>
                        <th scope="col">ชื่อไฟล์</th>
                        <th scope="col">ประเภท</th>
                        <th scope="col">วันที่อัปโหลด</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($file as $files)
                        <tr>
                            <td><span class="shortened-name"
                                    fullname="{{ $files->File }}">{{ Str::limit($files->File, 20) }}</span></td>
                            <td>{{ $files->File_name }}</td>
                            <td>{{ $files->File_Type }}</td>
                            <td>{{ $Date[$loop->index] }}</td>
                            <td>
                                <div class="edit-del-con">
                                    <form action="{{ route('editF', $files->File_ID) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class='bx bx-edit icon'></i>
                                        </button>
                                    </form>
                                    <button type="button" value="{{ $files->File_ID }}" class="btn btn-danger deleteFBtn">
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
        {{ $file->links('pagination::bootstrap-4') }}
    </div>
    
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shortenedNameElements = document.querySelectorAll('.shortened-name');
            shortenedNameElements.forEach(function(element) {
                element.addEventListener('mouseover', function() {
                    const fullName = this.getAttribute('fullname');
                    this.setAttribute('title', fullName);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.deleteFBtn').click(function(e) {
                e.preventDefault();

                var File_id = $(this).val();
                $('#file_id').val(File_id);
                $('#deleteFileModal').modal('show');
            });
        });
    </script>
@endsection