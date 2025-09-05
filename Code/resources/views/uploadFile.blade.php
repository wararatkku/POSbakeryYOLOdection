@extends('layoutOwner')
@section('title')
    Product
@endsection
@section('contents')
    @if (session('error'))
        <h6 class="alert alert-danger">{{ session('error') }}</h6>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 style="margin-top: 8%; margin-bottom: 20px; margin-left: 20px">อัปโหลดไฟล์เอกสาร</h3>
                <div class="card File">
                    <div class="card-body">
                        <h4 style="margin-left: 10%;">รายละเอียด</h4>
                        <div class="form-addP">
                            <form action="{{ url('UploadFile') }}" method="POST" enctype="multipart/form-data">
                                @csrf 
                                <p>ชื่อไฟล์เอกสาร : <input type="text" class="form-control-sm" name="fname" required></p>
                                <p>ประเภทไฟล์เอกสาร : 
                                    <select name="fileT" id="fileT">
                                       <option selected disabled>เลือกประเภทของไฟล์เอกสาร</option>
                                       <option value="รายการขาย">รายการขาย</option>
                                       <option value="รายการซื้อ">รายการซื้อ</option>
                                       <option value="ข้อมูลอื่น ๆ">ข้อมูลอื่น ๆ</option>
                                    </select></p>
                                <input type="file" class="form-control fileIP" name="file" required>
                                <div class="form-button">
                                    <a href="/File" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection